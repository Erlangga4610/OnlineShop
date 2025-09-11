<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Contracts\Role;

// Halaman utama redirect ke login
Route::get('/', fn () => redirect()->route('login'));

// Rute yang butuh autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/products', App\Livewire\Product\Products::class)->name('products');
    Route::get('/profile', App\Livewire\Auth\Profile::class)->name('profile');
    Route::get('/permission', App\Livewire\Permission\Index::class)->name('permission');
    Route::get('/role-permission', App\Livewire\Permission\RolePermission::class)->name('role-permission');
    Route::get('/user-role', App\Livewire\Permission\UserRole::class)->name('user-role');
    Route::get('/brands', App\Livewire\Product\Brands::class)->name('brands');
    Route::get('/categories', App\Livewire\Product\Categories::class)->name('categories');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});

// Rute guest
Route::middleware(['guest'])->group(function () {
    Route::get('/login', App\Livewire\Auth\Login::class)->name('login');
    Route::get('/register', App\Livewire\Auth\Register::class)->name('register');
    Route::get('/shop', function () {
        return view('shop');
    })->name('shop');
});

