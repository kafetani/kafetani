<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\FarmerResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Farmer;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class CatalogController extends Controller
{
    /**
     * GET /api/menu
     * Sama seperti PublicController::menu(), versi JSON.
     */
    public function menu(): JsonResponse
    {
        $products = Product::with('category')
            ->where('type', 'cafe')
            ->where('stok', '>', 0)
            ->orderBy('category_id')
            ->orderBy('nama_produk')
            ->get();

        $categories = $products
            ->pluck('category.name')
            ->filter()
            ->unique()
            ->values()
            ->prepend('Semua');

        return response()->json([
            'success'    => true,
            'products'   => ProductResource::collection($products),
            'categories' => $categories,
        ]);
    }

    /**
     * GET /api/marketplace
     */
    public function marketplace(): JsonResponse
    {
        $products = Product::where('type', 'market')->visibleToPublic()->with('farmer')->get();
        $farmers  = Farmer::verified()->orderBy('name')->get();

        return response()->json([
            'success'  => true,
            'products' => ProductResource::collection($products),
            'farmers'  => FarmerResource::collection($farmers),
        ]);
    }

    /**
     * GET /api/categories
     */
    public function categories(): JsonResponse
    {
        return response()->json([
            'success'    => true,
            'categories' => CategoryResource::collection(Category::orderBy('name')->get()),
        ]);
    }

    /**
     * GET /api/farmers
     */
    public function farmers(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'farmers' => FarmerResource::collection(Farmer::verified()->orderBy('name')->get()),
        ]);
    }

    /**
     * GET /api/products/{product}
     * Detail satu produk (dipakai layar detail di app).
     * Route model binding pakai kolom 'id_product' otomatis karena itu
     * primary key model Product.
     */
    public function product(Product $product): JsonResponse
    {
        $product->load(['category', 'farmer']);

        return response()->json([
            'success' => true,
            'product' => new ProductResource($product),
        ]);
    }
}
