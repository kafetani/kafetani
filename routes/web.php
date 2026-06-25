<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
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

    // Produk
    Route::get('/products',         [ProductController::class, 'index'])->name('products.index');
    Route::post('/products/save',   [ProductController::class, 'save'])->name('products.save');
    Route::get('/products/delete',  [ProductController::class, 'delete'])->name('products.delete');

    // Petani
    Route::get('/farmers',              [FarmerController::class, 'index'])->name('farmers.index');
    Route::get('/farmers/create',       [FarmerController::class, 'create'])->name('farmers.create');
    Route::post('/farmers',             [FarmerController::class, 'store'])->name('farmers.store');
    Route::get('/farmers/{farmer}/edit', [FarmerController::class, 'edit'])->name('farmers.edit');
    Route::put('/farmers/{farmer}',     [FarmerController::class, 'update'])->name('farmers.update');
    Route::delete('/farmers/{farmer}',  [FarmerController::class, 'destroy'])->name('farmers.destroy');

    // Orders
    Route::get('/orders',               [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// ─── Kasir (admin dan kasir) ───────────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'role:admin,kasir'])->name('admin.')->group(function () {
    Route::get('/kasir',          [KasirController::class, 'index'])->name('kasir');
    Route::post('/kasir/order',   [KasirController::class, 'placeOrder'])->name('kasir.order');
});

// ─── Halaman publik (placeholder — sesuaikan dengan view lama) ─────────────────
Route::get('/',                  fn() => view('index'))->name('home');
Route::get('/menu',              fn() => view('menu'));
Route::get('/marketplace',       fn() => view('marketplace'));
Route::get('/cara-pesan',        fn() => view('cara-pesan'));
Route::get('/tentang-kami',      fn() => view('tentang-kami'));
Route::get('/kebijakan-privasi', fn() => view('kebijakan-privasi'));
Route::get('/syarat-ketentuan',  fn() => view('syarat-ketentuan'));
