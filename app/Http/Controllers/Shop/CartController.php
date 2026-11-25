<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\AddToCartRequest;
use App\Http\Requests\Shop\UpdateCartRequest;
use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private Cart $cart) {}

    /** Figma B3 · Cart */
    public function index(): View
    {
        $lines = $this->cart->lines();

        return view('shop.cart', [
            'lines' => $lines,
            'itemCount' => (int) $lines->sum('quantity'),
            'total' => (float) $lines->sum('total'),
        ]);
    }

    public function store(AddToCartRequest $request): RedirectResponse
    {
        $product = Product::findOrFail($request->integer('product_id'));
        $quantity = $request->integer('quantity', 1);

        $this->cart->add($product, $quantity);

        return back()->with('cart_added', "{$quantity} × {$product->name} added to your cart.");
    }

    public function update(UpdateCartRequest $request, Product $product): RedirectResponse
    {
        $this->cart->set($product, $request->integer('quantity'));

        return redirect()->route('cart.index');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->cart->remove($product);

        return redirect()->route('cart.index')->with('status', "{$product->name} removed from your cart.");
    }
}
