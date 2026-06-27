<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FarmerController;
use App\Http\Controllers\Admin\KasirController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

// ─── Auth ─────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'showForm'])->name('login');
    Route::post('/login',   [LoginController::class, 'login'])->name('login.post');

    Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LoginController::class, 'logout'])
     ->middleware('auth')
     ->name('logout');

// ─── Admin (hanya admin) ───────────────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/',          [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/products',        [ProductController::class, 'index'])->name('products.index');
    Route::post('/products/save',  [ProductController::class, 'save'])->name('products.save');
    Route::get('/products/delete', [ProductController::class, 'delete'])->name('products.delete');

    Route::get('/farmers',               [FarmerController::class, 'index'])->name('farmers.index');
    Route::get('/farmers/create',        [FarmerController::class, 'create'])->name('farmers.create');
    Route::post('/farmers',              [FarmerController::class, 'store'])->name('farmers.store');
    Route::get('/farmers/{farmer}/edit', [FarmerController::class, 'edit'])->name('farmers.edit');
    Route::put('/farmers/{farmer}',      [FarmerController::class, 'update'])->name('farmers.update');
    Route::delete('/farmers/{farmer}',   [FarmerController::class, 'destroy'])->name('farmers.destroy');

    Route::get('/orders',                [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// ─── Kasir (admin dan kasir) ───────────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'role:admin,kasir'])->name('admin.')->group(function () {
    Route::get('/kasir',        [KasirController::class, 'index'])->name('kasir');
    Route::post('/kasir/order', [KasirController::class, 'placeOrder'])->name('kasir.order');
});

// ─── Halaman Publik ────────────────────────────────────────────────────────────
Route::get('/',                  [PublicController::class, 'home'])->name('home');
Route::get('/menu',              [PublicController::class, 'menu'])->name('menu');
Route::get('/marketplace',       [PublicController::class, 'marketplace'])->name('marketplace');
Route::get('/cara-pesan',        [PublicController::class, 'caraPersan'])->name('cara-pesan');
Route::get('/tentang-kami',      [PublicController::class, 'tentangKami'])->name('tentang-kami');
Route::get('/kebijakan-privasi', [PublicController::class, 'kebijakanPrivasi'])->name('kebijakan-privasi');
Route::get('/syarat-ketentuan',  [PublicController::class, 'syaratKetentuan'])->name('syarat-ketentuan');
