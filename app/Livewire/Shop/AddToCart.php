<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class AddToCart extends Component
{
    public $productId;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function add()
    {
        $product = Product::findOrFail($this->productId);

        // Ambil / buat cart user
        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id()
        ]);

        // Cek apakah product sudah ada
        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'quantity'   => 1,
                'price'      => $product->price, // ambil dari Product
            ]);
        }

        // Emit event agar UI bisa tahu ada perubahan
        $this->dispatch('cartUpdated');

        session()->flash('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function render()
    {
        return view('livewire.shop.add-to-cart');
    }
}
