<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Daftar semua kategori
     */
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Form tambah kategori baru
     */
    public function create()
    {
        return view('admin.categories.form', ['category' => new Category(), 'action' => 'add']);
    }

    /**
     * Simpan kategori baru
     */
    public function store(Request $request)
    {
        $data = $this->validateCategory($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        Category::create($data);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Form edit kategori
     */
    public function edit(Category $category)
    {
        return view('admin.categories.form', ['category' => $category, 'action' => 'edit']);
    }

    /**
     * Update data kategori
     */
    public function update(Request $request, Category $category)
    {
        $data = $this->validateCategory($request, $category->id);

        if ($data['name'] !== $category->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $category->id);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil diupdate!');
    }

    /**
     * Hapus kategori
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return redirect()->route('admin.categories.index')
                             ->with('error', 'Kategori tidak dapat dihapus karena masih dipakai oleh produk.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil dihapus!');
    }

    // ─── Private helpers ───────────────────────────────────────────────────────

    private function validateCategory(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => [
                'required', 'string', 'max:50',
                'unique:categories,name' . ($ignoreId ? ',' . $ignoreId : ''),
            ],
        ]);
    }

    /**
     * Buat slug unik dari nama kategori (mis. "Kopi" -> "kopi", "kopi-2" jika bentrok).
     */
    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
