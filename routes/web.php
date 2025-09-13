<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Shop\CartPage; // Import CartPage

// Halaman utama redirect ke shop
Route::get('/', fn () => redirect()->route('shop'));

// Shop bisa diakses tanpa login
Route::get('/shop', fn () => view('shop'))->name('shop');

// ====================
// Bagian admin/dashboard
// ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/products', App\Livewire\Product\Products::class)->name('products');
    Route::get('/profile', App\Livewire\Auth\Profile::class)->name('profile');
    Route::get('/permission', App\Livewire\Permission\Index::class)->name('permission');
    Route::get('/role-permission', App\Livewire\Permission\RolePermission::class)->name('role-permission');
    Route::get('/user-role', App\Livewire\Permission\UserRole::class)->name('user-role');
    Route::get('/brands', App\Livewire\Product\Brands::class)->name('brands');
    Route::get('/categories', App\Livewire\Product\Categories::class)->name('categories');
});

// ====================
// Cart dipisah dari admin
// ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', fn () => view('cart'))->name('cart');
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
// Route Guest
// ====================
Route::middleware(['guest'])->group(function () {
    Route::get('/login', App\Livewire\Auth\Login::class)->name('login');
    Route::get('/register', App\Livewire\Auth\Register::class)->name('register');
});
