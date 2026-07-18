<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'user',
        ];
    }

    /**
     * Role admin: akses penuh ke dashboard admin (SRS Bab 2.3).
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Role kasir: akses ke halaman kasir dan sebagian dashboard admin.
     */
    public function kasir(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'kasir',
        ]);
    }

    /**
     * Role petani: aktor sistem penuh untuk petani lokal (SRS Bab 6).
     */
    public function petani(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'petani',
        ]);
    }
}
