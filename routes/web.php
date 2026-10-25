<?php

use App\Http\Controllers\Account;
use App\Http\Controllers\Admin;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderPdfController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Site public
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');

Route::get('/produits', [ProductController::class, 'index'])->name('products.index');
Route::get('/produits/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/artisans', [ArtisanController::class, 'index'])->name('artisans.index');
Route::get('/artisans/{artisan}', [ArtisanController::class, 'show'])->name('artisans.show');

// Panier (session, accessible sans compte)
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier', [CartController::class, 'store'])->name('cart.store');
Route::patch('/panier/{product:id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/{product:id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/panier', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

Route::view('/mentions-legales', 'pages.legal')->name('pages.legal');
Route::view('/conditions-generales-de-vente', 'pages.terms')->name('pages.terms');
Route::view('/politique-de-confidentialite', 'pages.privacy')->name('pages.privacy');

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/inscription', [RegisterController::class, 'create'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/connexion', [LoginController::class, 'create'])->name('login');
    Route::post('/connexion', [LoginController::class, 'store'])->middleware('throttle:6,1')->name('login.store');
});

Route::post('/deconnexion', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Espace client (connexion requise)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/commande', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/commande', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::prefix('mon-compte')->name('account.')->group(function () {
        Route::get('/commandes', [Account\OrderController::class, 'index'])->name('orders.index');
        Route::get('/commandes/{order}', [Account\OrderController::class, 'show'])->name('orders.show');
        Route::get('/commandes/{order}/pdf', [OrderPdfController::class, 'download'])->name('orders.pdf');

        Route::get('/profil', [Account\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profil', [Account\ProfileController::class, 'update'])->name('profile.update');

        Route::get('/adresses', [Account\AddressController::class, 'index'])->name('addresses.index');
        Route::post('/adresses', [Account\AddressController::class, 'store'])->name('addresses.store');
        Route::put('/adresses/{address}', [Account\AddressController::class, 'update'])->name('addresses.update');
        Route::delete('/adresses/{address}', [Account\AddressController::class, 'destroy'])->name('addresses.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Back-office (administrateurs uniquement)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::resource('products', Admin\ProductController::class)->except('show');
    Route::delete('/products/{product}/images/{image}', [Admin\ProductImageController::class, 'destroy'])->name('products.images.destroy');
    Route::patch('/products/{product}/images/{image}/primary', [Admin\ProductImageController::class, 'makePrimary'])->name('products.images.primary');

    Route::resource('categories', Admin\CategoryController::class)->except('show');
    Route::resource('artisans', Admin\ArtisanController::class)->except('show');

    Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders/{order}/pdf', [OrderPdfController::class, 'download'])->name('orders.pdf');

    Route::get('/customers', [Admin\CustomerController::class, 'index'])->name('customers.index');

    Route::get('/messages', [Admin\ContactMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}', [Admin\ContactMessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{message}', [Admin\ContactMessageController::class, 'destroy'])->name('messages.destroy');
});
