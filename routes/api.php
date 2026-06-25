<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

// ─── Orders API ────────────────────────────────────────────────────────────────
// Endpoint ini dipakai oleh app.js untuk pesanan online dari keranjang belanja.
// Harus sudah login (auth:sanctum atau auth:web sesuai konfigurasi)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
});
