<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $fillable = ['user_id', 'name', 'location', 'contact', 'bio', 'avatar', 'status'];

    // Tabel tidak pakai updated_at
    const UPDATED_AT = null;

    /**
     * Scope: hanya petani yang menunggu verifikasi admin (FR — verifikasi kemitraan).
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: hanya petani yang sudah terverifikasi/resmi masuk jaringan.
     * Dipakai di semua tampilan publik (marketplace, direktori petani, API).
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Relasi: satu petani bisa punya banyak produk (foreign key murni: farmer_id)
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'farmer_id');
    }

    /**
     * Relasi: profil farmer ini terhubung ke satu akun user (role: petani).
     * Nullable — farmer lama yang belum onboarding akun tidak punya user_id.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: URL avatar lengkap
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('farmers/' . $this->avatar)
            : asset('farmers/default.webp');
    }
}
