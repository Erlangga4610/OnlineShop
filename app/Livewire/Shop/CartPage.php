<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartPage extends Component
{
  
    public function removeItem($itemId)
    {
        $cart = Auth::user()->cart;

        if ($cart) {
            $item = $cart->items()->where('id', $itemId)->first();

            if ($item) {
                $productName = $item->product->name ?? 'Produk';
                $item->delete();

                $this->dispatch('toastr', type: 'success', message: "{$productName} berhasil dihapus dari keranjang 🗑️");
            }
        }
    }

    // Tambah quantity item
    public function increaseQty($itemId)
    {
        $cart = Auth::user()->cart;

        if ($cart) {
            $item = $cart->items()->where('id', $itemId)->first();
            if ($item) {
                $item->quantity += 1;
                $item->save();
            }
        }
    }

    // Kurangi quantity item
    public function decreaseQty($itemId)
    {
        $cart = Auth::user()->cart;

        if ($cart) {
            $item = $cart->items()->where('id', $itemId)->first();
            if ($item && $item->quantity > 1) {
                $item->quantity -= 1;
                $item->save();
            } elseif ($item && $item->quantity <= 1) {
                $productName = $item->product->name ?? 'Produk';
                $item->delete();

                
                $this->dispatch('toastr', type: 'success', message: "{$productName} dihapus karena jumlah 0 🗑️");
            }
        }
    }

    // Render halaman cart
    public function render()
    {
        $cart = Auth::user()
            ->cart()
            ->with('items.product.category')
            ->first();

        $total = $cart?->items->sum(fn($item) => $item->price * $item->quantity) ?? 0;

        return view('livewire.shop.cart-page', compact('cart', 'total'));
    }
}
