<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $fillable = ['name', 'location', 'contact', 'bio', 'avatar'];

    // Tabel tidak pakai updated_at
    const UPDATED_AT = null;

    /**
     * Relasi: satu petani bisa punya banyak produk (foreign key murni: farmer_id)
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'farmer_id');
    }

    /**
     * Accessor: URL avatar lengkap
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('img/farmers/' . $this->avatar)
            : asset('img/farmers/default.webp');
    }
}
