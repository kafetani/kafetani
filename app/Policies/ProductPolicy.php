<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

/**
 * Otorisasi kepemilikan produk marketplace milik petani.
 *
 * Sebelumnya aturan "petani hanya boleh ubah/hapus produk miliknya sendiri"
 * ditulis manual dan berulang di setiap method PetaniController (query
 * where('farmer_id', ...) lalu cek null). Risiko: kalau ada method baru
 * lupa menyalin cek yang sama, itu jadi celah keamanan diam-diam. Policy
 * ini memusatkan aturannya di satu tempat, sesuai konvensi Laravel untuk
 * otorisasi berbasis model/kepemilikan (beda dari RoleMiddleware yang
 * menangani otorisasi berbasis rute/peran, bukan kepemilikan resource).
 *
 * Catatan: ini HANYA untuk produk milik petani via portal Petani. CRUD
 * produk oleh Admin (Admin\ProductController) sengaja tidak lewat policy
 * ini — admin memang berwenang penuh atas semua produk (cafe & market).
 */
class ProductPolicy
{
    /**
     * Petani boleh mengubah produk hanya jika farmer_id produk itu cocok
     * dengan profil farmer milik user yang sedang login.
     */
    public function update(User $user, Product $product): bool
    {
        return $this->ownsProduct($user, $product);
    }

    /**
     * Aturan hapus sama dengan aturan ubah: hanya pemilik produk.
     */
    public function delete(User $user, Product $product): bool
    {
        return $this->ownsProduct($user, $product);
    }

    private function ownsProduct(User $user, Product $product): bool
    {
        return $user->farmer !== null
            && $product->farmer_id !== null
            && $product->farmer_id === $user->farmer->id;
    }
}
