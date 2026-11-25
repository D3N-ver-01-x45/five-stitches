<?php

namespace App\Http\Controllers\Shop;

use App\Enums\ProductCategory;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    /** Figma B1 · Shop (Home) */
    public function index(Request $request): View
    {
        $category = ProductCategory::tryFrom((string) $request->query('category'));
        $search = trim((string) $request->query('q'));

        $products = Product::forSale()
            ->when($category, fn ($q) => $q->where('category', $category))
            ->search($search)
            ->get();

        return view('shop.home', [
            'products' => $products,
            'heroProducts' => Product::forSale()->whereNotNull('image')->take(4)->get(),
            'activeCategory' => $category,
            'search' => $search,
        ]);
    }

    /** Figma B2 · Product */
    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        return view('shop.product', ['product' => $product]);
    }
}
