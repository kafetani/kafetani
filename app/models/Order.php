<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false; // Hanya punya created_at manual di DB

    protected $fillable = [
        'user_id',
        'total',
        'type',
        'source',
        'customer_name',
        'status',
    ];

    protected $casts = [
        'total'      => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Relasi: order milik satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: order punya banyak items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Helper: label status dalam bahasa Indonesia
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'Masuk',
            'processing' => 'Proses',
            'ready'      => 'Siap',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
            default      => ucfirst($this->status),
        };
    }

    /**
     * Helper: format total ke rupiah
     */
    public function getTotalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}
