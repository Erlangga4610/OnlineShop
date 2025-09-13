<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class ShopIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function addToCart($productId)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Buat cart kalau belum ada
        $cart = $user->cart ?? Cart::create(['user_id' => $user->id]);

        // Cari product
        $product = Product::findOrFail($productId);

        // Cari item dalam cart
        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => 1,
                'price'      => $product->price, // ambil dari products table
            ]);
        }

        // Notifikasi toastr
        $this->dispatch('toastr', type: 'success', message: "{$product->name} berhasil ditambahkan ke keranjang 🛒");
    }

    public function render()
    {
        $products = Product::with('category')->paginate(12);

        return view('livewire.shop.shop-index', compact('products'));
    }
}
