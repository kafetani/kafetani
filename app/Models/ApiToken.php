<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'token', 'device_name', 'last_used_at', 'created_at'];

    protected $casts = [
        'last_used_at' => 'datetime',
        'created_at'   => 'datetime',
    ];

    /**
     * Relasi: token milik satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Buat token baru untuk user dan kembalikan versi plain-text-nya.
     * Plain text hanya muncul sekali di sini — yang disimpan ke DB cuma hash-nya,
     * jadi kalau tabelnya bocor, token asli tidak bisa dipakai orang lain.
     */
    public static function issueFor(User $user, ?string $deviceName = null): string
    {
        $plain = bin2hex(random_bytes(32)); // 64 karakter hex

        static::create([
            'user_id'     => $user->id,
            'token'       => hash('sha256', $plain),
            'device_name' => $deviceName,
            'created_at'  => now(),
        ]);

        return $plain;
    }
}
