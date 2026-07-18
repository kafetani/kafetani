<?php

namespace Tests\Feature;

use App\Models\Farmer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Memverifikasi aturan kepemilikan produk marketplace milik petani, yang
 * dipusatkan di app/Policies/ProductPolicy.php: seorang petani hanya boleh
 * mengubah/menghapus produk dengan farmer_id miliknya sendiri, walau ia
 * menyisipkan id produk milik petani lain secara manual lewat form.
 */
class PetaniProductOwnershipTest extends TestCase
{
    use RefreshDatabase;

    private function buatPetaniDenganFarmer(): array
    {
        $petani = User::factory()->petani()->create();

        $farmer = Farmer::create([
            'user_id'  => $petani->id,
            'name'     => $petani->nama,
            'location' => 'Gayo, Aceh',
            'status'   => 'approved',
        ]);

        return [$petani, $farmer];
    }

    public function test_petani_bisa_edit_produk_miliknya_sendiri(): void
    {
        [$petani, $farmer] = $this->buatPetaniDenganFarmer();

        $produk = Product::create([
            'nama_produk' => 'Kopi Arabica Gayo',
            'harga'       => 85000,
            'stok'        => 10,
            'farmer_id'   => $farmer->id,
            'type'        => 'market',
            'status'      => 'approved',
        ]);

        $response = $this->actingAs($petani)->post('/petani/produk/save', [
            'id'          => $produk->id_product,
            'nama_produk' => 'Kopi Arabica Gayo (Update)',
            'harga'       => 90000,
            'stok'        => 8,
        ]);

        $response->assertRedirect(route('petani.produk.index'));
        $response->assertSessionHas('success');
        $this->assertSame('Kopi Arabica Gayo (Update)', $produk->fresh()->nama_produk);
    }

    public function test_petani_tidak_bisa_edit_produk_milik_petani_lain(): void
    {
        [, $farmerLain] = $this->buatPetaniDenganFarmer();
        [$petani]       = $this->buatPetaniDenganFarmer();

        $produkOrangLain = Product::create([
            'nama_produk' => 'Gula Aren Organik',
            'harga'       => 45000,
            'stok'        => 20,
            'farmer_id'   => $farmerLain->id,
            'type'        => 'market',
            'status'      => 'approved',
        ]);

        $response = $this->actingAs($petani)->post('/petani/produk/save', [
            'id'          => $produkOrangLain->id_product,
            'nama_produk' => 'Diklaim Petani Lain',
            'harga'       => 1,
            'stok'        => 0,
        ]);

        $response->assertSessionHasErrors('id');
        $this->assertSame('Gula Aren Organik', $produkOrangLain->fresh()->nama_produk);
    }

    public function test_petani_tidak_bisa_hapus_produk_milik_petani_lain(): void
    {
        [, $farmerLain] = $this->buatPetaniDenganFarmer();
        [$petani]       = $this->buatPetaniDenganFarmer();

        $produkOrangLain = Product::create([
            'nama_produk' => 'Susu Sapi Segar',
            'harga'       => 28000,
            'stok'        => 15,
            'farmer_id'   => $farmerLain->id,
            'type'        => 'market',
            'status'      => 'approved',
        ]);

        $response = $this->actingAs($petani)->get('/petani/produk/delete?hapus=' . $produkOrangLain->id_product);

        $response->assertForbidden();
        $this->assertNotNull($produkOrangLain->fresh());
    }
}
