<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Wishlist as WishlistModel;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.shop')]
class Wishlist extends Component
{
    public $wishlistItems = [];
    public $selectedProductId = null;

    public function mount()
    {
        $this->loadWishlist();
    }

    public function loadWishlist()
    {
        $this->wishlistItems = Auth::check()
            ? Auth::user()->wishlists()->with('product')->get()
            : collect();
    }

    // ==== WISHLIST ====
    public function confirmRemoveWishlist($productId)
    {
        $this->selectedProductId = $productId;
        $this->dispatch('show-remove-wishlist-modal');
    }

    public function removeFromWishlist()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        WishlistModel::where('user_id', $user->id)
            ->where('product_id', $this->selectedProductId)
            ->delete();

        $this->dispatch('toastr', type: 'info', message: "Produk dihapus dari wishlist 🤍");
        $this->dispatch('hide-remove-wishlist-modal');

        $this->loadWishlist();
    }

    // ==== CART ====
    public function confirmAddToCart($productId)
    {
        $this->selectedProductId = $productId;
        $this->dispatch('show-add-to-cart-modal');
    }

    public function addToCart()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $cart = $user->cart ?? Cart::create(['user_id' => $user->id]);
        $product = Product::findOrFail($this->selectedProductId);

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity', 1);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => 1,
                'price'      => $product->price,
            ]);
        }

        // Auto hapus dari wishlist setelah masuk cart
        WishlistModel::where('user_id', $user->id)
            ->where('product_id', $this->selectedProductId)
            ->delete();

        $this->dispatch('toastr', type: 'success', message: "{$product->name} berhasil ditambahkan ke keranjang 🛒");
        $this->dispatch('hide-add-to-cart-modal');

        $this->loadWishlist();
    }

    public function render()
    {
        return view('livewire.shop.wishlist', [
            'wishlistItems' => $this->wishlistItems,
        ]);
    }
}
