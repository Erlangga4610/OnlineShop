<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use App\Models\Address;

#[Layout('components.layouts.shop')]
class Checkout extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $shipping_address;

    public function mount()
    {
        $this->cartItems = [
            ['product_id' => 1, 'quantity'=> 2],
            ['product_id' => 2, 'quantity'=> 1],
        ];

        foreach ($this->cartItems as $key => $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $this->cartItems[$key]['product'] = $product;
                $this->total += $product->price * $item['quantity'];
            } else {
                // Hapus item jika produk tidak ditemukan
                unset($this->cartItems[$key]);
            }
        }
    }

    public function placeOrder()
    {
        $this->validate(['shipping_address' => 'required|string|min:10']);

        // 1. Siapkan variabel untuk menampung order di luar transaksi
        $order = null;

        try {
            // 2. Gunakan 'use (&$order)' agar variabel bisa diubah di dalam closure
            DB::transaction(function () use (&$order) {
                $order = Order::create([
                    'user_id'          => Auth::id(),
                    'order_date'       => now(),
                    'status'           => 'pending',
                    'total'            => $this->total,
                    'shipping_address' => $this->shipping_address,
                ]);

                foreach ($this->cartItems as $item) {
                    $order->orderItems()->create([
                        'product_id' => $item['product_id'],
                        'quantity'   => $item['quantity'],
                        'price'      => $item['product']->price,
                    ]);

                    // Di sini Anda juga bisa menambahkan logika pengurangan stok
                    Product::find($item['product_id'])->decrement('stock', $item['quantity']);
                }

                // (Opsional) Kosongkan keranjang di sini
                // Auth::user()->cart->items()->delete();
            });

        } catch (\Exception $e) {
            // Jika terjadi error, beri tahu pengguna
            $this->dispatch('toastr:error', ['message' => 'Terjadi kesalahan saat membuat pesanan.']);
            return;
        }

        // 3. Lakukan redirect SETELAH transaksi berhasil
        if ($order) {
            // Arahkan ke route detail pesanan milik pengguna
            return redirect()->route('orders.detail', ['order' => $order->id])
                            ->with('message', 'Pesanan Anda berhasil dibuat!');
        }
    }

    public function render()
    {
        return view('livewire.shop.checkout');
    }
}
