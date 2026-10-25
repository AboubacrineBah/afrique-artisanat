import { mkdir } from 'node:fs/promises';
import path from 'node:path';
import { chromium } from 'playwright';

const baseUrl = (process.env.CAPTURE_BASE_URL ?? 'http://127.0.0.1:8000').replace(/\/$/, '');
const captureDirectory = path.resolve(process.cwd(), 'captures');

const publicPages = [
    ['accueil', '/'],
    ['produits', '/produits'],
    ['artisans', '/artisans'],
    ['panier', '/panier'],
    ['contact', '/contact'],
    ['inscription', '/inscription'],
    ['connexion', '/connexion'],
    ['mentions-legales', '/mentions-legales'],
    ['conditions-generales-de-vente', '/conditions-generales-de-vente'],
    ['politique-de-confidentialite', '/politique-de-confidentialite'],
];

const clientPages = [
    ['commande', '/commande'],
    ['commandes', '/mon-compte/commandes'],
    ['profil', '/mon-compte/profil'],
    ['adresses', '/mon-compte/adresses'],
];

const adminPages = [
    ['tableau-de-bord', '/admin'],
    ['produits', '/admin/products'],
    ['creation-produit', '/admin/products/create'],
    ['categories', '/admin/categories'],
    ['creation-categorie', '/admin/categories/create'],
    ['artisans', '/admin/artisans'],
    ['creation-artisan', '/admin/artisans/create'],
    ['commandes', '/admin/orders'],
    ['clients', '/admin/customers'],
    ['messages', '/admin/messages'],
];

function requiredEnvironmentVariable(name) {
    const value = process.env[name];

    if (!value) {
        throw new Error(`La variable d'environnement ${name} est requise pour les captures privées.`);
    }

    return value;
}

async function capturePages(page, section, pages) {
    for (const [name, route] of pages) {
        const response = await page.goto(`${baseUrl}${route}`, { waitUntil: 'networkidle' });

        if (!response || response.status() >= 400) {
            throw new Error(`Impossible de capturer ${route} (HTTP ${response?.status() ?? 'inconnu'}).`);
        }

        const filename = path.join(captureDirectory, section, `${name}.png`);
        await page.screenshot({ path: filename, fullPage: true });
        console.log(`Capture créée : ${path.relative(process.cwd(), filename)}`);
    }
}

async function signIn(page, email, password, role) {
    await page.goto(`${baseUrl}/connexion`, { waitUntil: 'networkidle' });
    await page.locator('[name="email"]').fill(email);
    await page.locator('[name="password"]').fill(password);
    await page.locator('button[type="submit"]').click();
    await page.waitForURL((url) => !url.pathname.endsWith('/connexion'));

    if (role === 'admin' && !new URL(page.url()).pathname.startsWith('/admin')) {
        throw new Error('La connexion admin a échoué ou ce compte ne dispose pas des droits administrateur.');
    }

    if (role === 'client' && new URL(page.url()).pathname.startsWith('/admin')) {
        throw new Error('Utilisez un compte client pour les captures de l’espace client.');
    }
}

const clientEmail = requiredEnvironmentVariable('CAPTURE_CLIENT_EMAIL');
const clientPassword = requiredEnvironmentVariable('CAPTURE_CLIENT_PASSWORD');
const adminEmail = requiredEnvironmentVariable('CAPTURE_ADMIN_EMAIL');
const adminPassword = requiredEnvironmentVariable('CAPTURE_ADMIN_PASSWORD');

await mkdir(captureDirectory, { recursive: true });
await mkdir(path.join(captureDirectory, 'public'), { recursive: true });
await mkdir(path.join(captureDirectory, 'client'), { recursive: true });
await mkdir(path.join(captureDirectory, 'admin'), { recursive: true });

const browser = await chromium.launch();

try {
    const publicContext = await browser.newContext({ viewport: { width: 1440, height: 1000 } });
    const publicPage = await publicContext.newPage();
    await capturePages(publicPage, 'public', publicPages);
    await publicContext.close();

    const clientContext = await browser.newContext({ viewport: { width: 1440, height: 1000 } });
    const clientPage = await clientContext.newPage();
    await signIn(clientPage, clientEmail, clientPassword, 'client');
    await capturePages(clientPage, 'client', clientPages);
    await clientContext.close();

    const adminContext = await browser.newContext({ viewport: { width: 1440, height: 1000 } });
    const adminPage = await adminContext.newPage();
    await signIn(adminPage, adminEmail, adminPassword, 'admin');
    await capturePages(adminPage, 'admin', adminPages);
    await adminContext.close();
} finally {
    await browser.close();
}
