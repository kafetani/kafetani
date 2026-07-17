<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RegisterPetaniController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FarmerController;
use App\Http\Controllers\Admin\KasirController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Petani\PetaniController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Controllers\Api\MidtransController;
use Illuminate\Support\Facades\Route;

// ─── Auth ─────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'showForm'])->name('login');
    Route::post('/login',   [LoginController::class, 'login'])->name('login.post');

    Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // Registrasi khusus Petani Lokal (SRS Bab 2.3 & 6, FR-19)
    Route::get('/register-petani',  [RegisterPetaniController::class, 'showForm'])->name('register-petani');
    Route::post('/register-petani', [RegisterPetaniController::class, 'register'])->name('register-petani.post');

    // Lupa Password
    Route::get('/forgot-password',  [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendEmail'])->name('password.email');
    Route::get('/forgot-password/sent', [ForgotPasswordController::class, 'showSent'])->name('password.sent');
    Route::get('/reset-password',   [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password',  [ForgotPasswordController::class, 'reset'])->name('password.update');

    // Login dengan Google
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

Route::post('/logout', [LoginController::class, 'logout'])
     ->middleware('auth')
     ->name('logout');

// Webhook Midtrans (diakses oleh server Midtrans, dikecualikan dari CSRF & Auth)
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');

// ─── Orders API (dipanggil via fetch dari app.js saat checkout) ───────────────
// Didaftarkan di web.php (bukan routes/api.php) supaya route ini ikut masuk
// grup middleware 'web' — artinya sesi & verifikasi CSRF aktif, dan guard
// 'auth' di sini otomatis memakai guard default 'web' (session), sama seperti
// login/logout di atas. Aplikasi ini tidak memakai Sanctum, jadi 'auth:sanctum'
// akan selalu gagal karena guard tersebut tidak pernah didefinisikan.
Route::post('/api/orders', [ApiOrderController::class, 'store'])
     ->middleware('auth')
     ->name('api.orders.store');

// ─── Admin (hanya admin) ───────────────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/',          [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/products',        [ProductController::class, 'index'])->name('products.index');
    Route::post('/products/save',  [ProductController::class, 'save'])->name('products.save');
    Route::get('/products/delete', [ProductController::class, 'delete'])->name('products.delete');
    Route::post('/products/{product}/approve', [ProductController::class, 'approve'])->name('products.approve');
    Route::post('/products/{product}/reject',  [ProductController::class, 'reject'])->name('products.reject');

    Route::get('/categories',               [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create',        [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories',              [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}',    [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/farmers',               [FarmerController::class, 'index'])->name('farmers.index');
    Route::get('/farmers/create',        [FarmerController::class, 'create'])->name('farmers.create');
    Route::post('/farmers',              [FarmerController::class, 'store'])->name('farmers.store');
    Route::get('/farmers/{farmer}/edit', [FarmerController::class, 'edit'])->name('farmers.edit');
    Route::put('/farmers/{farmer}',      [FarmerController::class, 'update'])->name('farmers.update');
    Route::delete('/farmers/{farmer}',   [FarmerController::class, 'destroy'])->name('farmers.destroy');
    Route::post('/farmers/{farmer}/approve', [FarmerController::class, 'approve'])->name('farmers.approve');
    Route::post('/farmers/{farmer}/reject',  [FarmerController::class, 'reject'])->name('farmers.reject');

    Route::get('/orders',                [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// ─── Kasir (admin dan kasir) ───────────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'role:admin,kasir'])->name('admin.')->group(function () {
    Route::get('/kasir',        [KasirController::class, 'index'])->name('kasir');
    Route::post('/kasir/order', [KasirController::class, 'placeOrder'])->name('kasir.order');
});

// ─── Petani Lokal (hanya role petani) ──────────────────────────────────────────
Route::prefix('petani')->middleware(['auth', 'role:petani'])->name('petani.')->group(function () {
    Route::get('/',          [PetaniController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [PetaniController::class, 'dashboard']);

    Route::get('/profil',  [PetaniController::class, 'profil'])->name('profil');
    Route::post('/profil', [PetaniController::class, 'updateProfil'])->name('profil.update');

    Route::get('/produk',         [PetaniController::class, 'produkIndex'])->name('produk.index');
    Route::post('/produk/save',   [PetaniController::class, 'produkSave'])->name('produk.save');
    Route::get('/produk/delete',  [PetaniController::class, 'produkDelete'])->name('produk.delete');
});

// ─── Halaman Publik ────────────────────────────────────────────────────────────
Route::get('/',                  [PublicController::class, 'home'])->name('home');
Route::get('/menu',              [PublicController::class, 'menu'])->name('menu');
Route::get('/marketplace',       [PublicController::class, 'marketplace'])->name('marketplace');
Route::get('/cara-pesan',        [PublicController::class, 'caraPersan'])->name('cara-pesan');
Route::get('/tentang-kami',      [PublicController::class, 'tentangKami'])->name('tentang-kami');
Route::get('/kebijakan-privasi', [PublicController::class, 'kebijakanPrivasi'])->name('kebijakan-privasi');
Route::get('/syarat-ketentuan',  [PublicController::class, 'syaratKetentuan'])->name('syarat-ketentuan');
