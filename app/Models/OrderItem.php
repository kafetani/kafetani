<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price'    => 'integer',
        'subtotal' => 'integer',
    ];

    /**
     * Relasi: item milik satu order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi: item merujuk satu produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }
}
