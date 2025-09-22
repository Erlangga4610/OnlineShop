<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\Cart; // Tambahkan ini jika belum ada

#[Layout('components.layouts.shop')]
class CartPage extends Component
{
    public $itemToDelete = null;
    public $productNameToDelete = null;
    
    // Properti baru untuk menyimpan data cart agar tidak perlu query berulang
    public $cart;

    /**
     * Menggunakan computed property untuk total agar selalu terupdate
     * setiap kali data cart berubah.
     */
    public function getTotalProperty()
    {
        return $this->cart?->items->sum(function($item) {
            // Pastikan menghitung dari harga produk untuk akurasi
            return $item->product->price * $item->quantity;
        }) ?? 0;
    }

    public function mount()
    {
        // Muat data cart sekali saat komponen di-mount
        $this->loadCart();
    }

    public function loadCart()
    {
        // Ambil cart milik user dan eager load relasi yang dibutuhkan
        $this->cart = Cart::where('user_id', Auth::id())
                          ->with('items.product.category')
                          ->first();
    }

    public function confirmRemove($itemId)
    {
        $item = $this->cart?->items->find($itemId);
        if ($item) {
            $this->itemToDelete = $item->id;
            $this->productNameToDelete = $item->product->name ?? 'Produk';
            $this->dispatch('openRemoveModal');
        }
    }

    public function removeItem()
    {
        $item = $this->cart?->items->find($this->itemToDelete);
        if ($item) {
            $productName = $item->product->name ?? 'Produk';
            $item->delete();
            $this->dispatch('toastr:success', ['message' => "{$productName} berhasil dihapus 🗑️"]);
            $this->loadCart(); // Muat ulang data cart
        }
        $this->dispatch('closeRemoveModal');
    }

    public function increaseQty($itemId)
    {
        $item = $this->cart?->items->find($itemId);
        if ($item) {
            $item->increment('quantity');
            $this->loadCart(); // Muat ulang data cart
        }
    }

    public function decreaseQty($itemId)
    {
        $item = $this->cart?->items->find($itemId);
        if ($item && $item->quantity > 1) {
            $item->decrement('quantity');
            $this->loadCart(); // Muat ulang data cart
        } elseif ($item && $item->quantity <= 1) {
            $this->removeItem(); // Panggil fungsi hapus jika kuantitas jadi 0
        }
    }

    public function buyNow()
    {
        return $this->redirect(route('checkout'), navigate: true);
    }
    
    public function render()
    {
        // Kirim view TANPA mengirimkan variabel secara manual.
        // Livewire akan otomatis membuat properti public ($cart) tersedia di view.
        return view('livewire.shop.cart-page');
    }
}