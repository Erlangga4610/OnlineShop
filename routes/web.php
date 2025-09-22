<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ====================
// Halaman Utama & Shop
// ====================
Route::get('/', fn () => redirect()->route('shop'));
Route::get('/shop', fn () => view('shop'))->name('shop');

// ====================
// Bagian Admin / Dashboard
// ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Master Data
    Route::get('/products', App\Livewire\Product\Products::class)->name('products');
    Route::get('/brands', App\Livewire\Product\Brands::class)->name('brands');
    Route::get('/categories', App\Livewire\Product\Categories::class)->name('categories');
    Route::get('/discount', App\Livewire\Product\Discount::class)->name('discount');
    Route::get('/payment-methods', App\Livewire\Payment\PaymentMethods::class)->name('payment-methods');
    Route::get('orders', App\Livewire\Order\ManageOrders::class)->name('orders');
    Route::get('orders/{order}', App\Livewire\Order\OrderDetail::class)->name('orders.detail');
    Route::get('orders', App\Livewire\Order\ManageOrders::class)->name('orders');

    // User & Permission
    Route::get('/profile', App\Livewire\Auth\Profile::class)->name('profile');
    Route::get('/permission', App\Livewire\Permission\Index::class)->name('permission');
    Route::get('/role-permission', App\Livewire\Permission\RolePermission::class)->name('role-permission');
    Route::get('/user-role', App\Livewire\Permission\UserRole::class)->name('user-role');
});

// ====================
// Cart & Wishlist (user biasa, bukan admin)
// ====================
use App\Livewire\Shop\CartPage;
use App\Livewire\Shop\Wishlist;


Route::middleware(['auth'])->group(function () {
    Route::get('/cart', fn () => view('cart'))->name('cart');
    Route::get('/wishlist', Wishlist::class)->name('wishlist');
    Route::get('/checkout', App\Livewire\Shop\Checkout::class)->middleware('auth')->name('checkout');
    Route::get('/order-success', fn () => session('message') ? '<h1>'.session('message').'</h1>' : redirect('/'))->middleware('auth')->name('order.success');
     // RUTE BARU DITAMBAHKAN DI SINI
    Route::get('/my-orders/{order}', App\Livewire\Order\OrderDetailPage::class)->name('order.orders.detail');

});

// ====================
// Logout
// ====================
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// ====================
// Route Guest (login / register)
// ====================
Route::middleware(['guest'])->group(function () {
    Route::get('/login', App\Livewire\Auth\Login::class)->name('login');
    Route::get('/register', App\Livewire\Auth\Register::class)->name('register');
});
