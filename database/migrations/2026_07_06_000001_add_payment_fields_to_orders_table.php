<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('status');
            $table->string('payment_type', 50)->nullable()->after('snap_token');
            $table->string('payment_status', 50)->default('unpaid')->after('payment_type');
            $table->string('transaction_id')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('transaction_id');
            // Mengubah tipe kolom status menjadi string agar mendukung status baru 'pending_payment'
            // Secara default Laravel + SQLite/MySQL mendukung perubahan ini
            $table->string('status', 50)->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'payment_type', 'payment_status', 'transaction_id', 'paid_at']);
        });
    }
};
