<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class ShopIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selectedProduct = null;
    public $quantity = 1;

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }


    public function showProduct($productId)
    {
        $this->selectedProduct = Product::with('category')->findOrFail($productId);
        $this->quantity = 1;

        $this->dispatch('openProductModal');
    }

    public function isInWishlist($productId)
    {
        $user = Auth::user();
        if (!$user) return false;

        return \App\Models\Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();
    }

    public function toggleWishlist($productId)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $this->dispatch('toastr', type: 'info', message: "Produk dihapus dari wishlist 🤍");
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            $this->dispatch('toastr', type: 'success', message: "Produk ditambahkan ke wishlist ❤️");
        }
    }

    public function addToCart($productId)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $cart = $user->cart ?? Cart::create(['user_id' => $user->id]);
        $product = Product::findOrFail($productId);

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity', $this->quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => $this->quantity,
                'price'      => $product->price,
            ]);
        }

        $this->dispatch('toastr', type: 'success', message: "{$product->name} berhasil ditambahkan ke keranjang 🛒");
        $this->dispatch('closeProductModal');
    }

    public function buyNow()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if (!$this->selectedProduct) {
            $this->dispatch('toastr', type: 'error', message: 'Produk tidak ditemukan ❌');
            return;
        }

        $product = Product::findOrFail($this->selectedProduct->id);

        // Tutup modal
        $this->dispatch('closeProductModal');

        // Toastr notifikasi
        $this->dispatch('toastr', type: 'success', message: "Order untuk {$product->name} berhasil dibuat ✅");
    }

    public function render()
    {
        $products = Product::with('category')->paginate(12);

        return view('livewire.shop.shop-index', compact('products'));
    }
}
