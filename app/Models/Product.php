<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Nama tabel tidak mengikuti konvensi Laravel (bukan 'products')
    protected $table = 'product';

    // Primary key tidak mengikuti konvensi Laravel
    protected $primaryKey = 'id_product';

    public $timestamps = false;

    protected $fillable = [
        'nama_produk',
        'harga',
        'stok',
        'deskripsi',
        'farmer_id',
        'gambar',
        'category_id',
        'type',
        'status',
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok'  => 'integer',
    ];

    /**
     * Scope: filter by type (cafe / market)
     */
    public function scopeType($query, string $type)
    {
        return in_array($type, ['cafe', 'market'])
            ? $query->where('type', $type)
            : $query;
    }

    /**
     * Scope: hanya yang masih ada stok
     */
    public function scopeAvailable($query)
    {
        return $query->where('stok', '>', 0);
    }

    /**
     * Scope: hanya produk yang boleh tampil di halaman publik (Marketplace).
     * Approved eksplisit, atau NULL (produk lama/diinput admin langsung yang
     * tidak melalui alur approval sama sekali — dianggap auto-approved).
     *
     * Sebelumnya scope ini hanya mengecek status produk itu sendiri, tanpa
     * peduli status verifikasi petaninya — jadi produk dari petani yang
     * belum (atau tidak) diverifikasi tetap muncul di Marketplace, padahal
     * direktori petani publik cuma menampilkan yang sudah verified (SRS
     * 3.4.2). Sekarang produk dengan farmer_id wajib farmer-nya verified;
     * produk tanpa farmer_id (mis. item kafe internal) tidak terpengaruh.
     */
    public function scopeVisibleToPublic($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'approved')->orWhereNull('status');
        })->where(function ($q) {
            $q->whereNull('farmer_id')
              ->orWhereHas('farmer', fn($fq) => $fq->verified());
        });
    }

    /**
     * Scope: hanya produk menunggu review admin
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Relasi: produk (marketplace) berasal dari satu petani (foreign key murni)
     */
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }

    /**
     * Relasi: produk milik satu kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: produk bisa ada di banyak order items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'id_product');
    }

    /**
     * Accessor: URL gambar lengkap
     */
    public function getGambarUrlAttribute(): string
    {
        return $this->gambar
            ? asset('products/' . $this->gambar)
            : '';
    }

    /**
     * Helper: format harga ke rupiah
     */
    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
