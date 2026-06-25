<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product', function (Blueprint $table) {
            $table->increments('id_product');
            $table->string('nama_produk', 100);
            $table->integer('harga');
            $table->integer('stok');
            $table->text('deskripsi')->nullable();
            $table->string('petani', 100)->nullable();
            $table->string('gambar', 255)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->enum('type', ['cafe', 'market'])->default('market');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
