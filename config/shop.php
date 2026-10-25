<?php

/*
 * Réglages métier de la boutique Afrique Artisanat.
 * Valeurs de démonstration : à adapter dans le fichier .env ou ici.
 */
return [
    'name' => env('SHOP_NAME', 'Afrique Artisanat'),
    'contact_email' => env('SHOP_CONTACT_EMAIL', 'bahaboubacrine1@gmail.com'),
    'admin_email' => env('SHOP_ADMIN_EMAIL', env('SHOP_CONTACT_EMAIL', 'bahaboubacrine1@gmail.com')),
    'currency' => 'FCFA',

    // Livraison : tarif forfaitaire, offert au-delà du seuil (montants entiers en FCFA)
    'shipping_fee' => (int) env('SHOP_SHIPPING_FEE', 2000),
    'free_shipping_threshold' => (int) env('SHOP_FREE_SHIPPING_THRESHOLD', 50000),

    // Catalogue
    'per_page' => 12,
    'admin_per_page' => 15,
    'max_images_per_product' => 6,

    // Panier
    'max_quantity_per_line' => 20,

    // Alerte de stock faible (tableau de bord admin)
    'low_stock_threshold' => 5,
];
