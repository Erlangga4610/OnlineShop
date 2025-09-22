<?php
namespace App\Livewire\Order;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
class OrderDetail extends Component
{
    public Order $order;

    public $courier;
    public $tracking_number;
    public $shipping_cost;
    public $refund_amount, $refund_method;

    public function mount(Order $order)
    {
        $this->order = $order->load('user', 'orderItems.product', 'payment.paymentMethod', 'shipment');
    }

    public function updateReturnStatus($status)
    {
        if (!in_array($status, ['approved', 'rejected'])) return;

        if ($this->order->returnRequest) {
            $this->order->returnRequest->update(['status' => $status]);
            $this->dispatch('toastr:success', ['message' => 'Status pengembalian diperbarui.']);
            $this->mount($this->order);
        }
    }

    public function issueRefund()
    {
        $this->validate([
            'refund_amount' => 'required|numeric|min:1',
            'refund_method' => 'required|string',
        ]);

        if ($this->order->returnRequest && $this->order->returnRequest->status === 'approved') {
            $this->order->returnRequest->refund()->create([
                'amount' => $this->refund_amount,
                'method' => $this->refund_method,
                'status' => 'completed',
            ]);

            $this->order->returnRequest->update(['status' => 'completed']);
            $this->order->update(['status' => 'refunded']);

            $this->dispatch('toastr:success', ['message' => 'Refund berhasil diproses.']);
            $this->mount($this->order);
        } else {
            $this->dispatch('toastr:error', ['message' => 'Pengembalian belum disetujui.']);
        }
    }

    public function saveShipment()
    {
        // 1. Validasi: Pastikan pesanan sudah lunas dan siap kirim
        if ($this->order->status !== 'processing') {
            $this->dispatch('toastr:error', ['message' => 'Pesanan belum bisa dikirim.']);
            return;
        }

        // 2. Validasi Input Form
        $validated = $this->validate([
            'courier'         => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'shipping_cost'   => 'required|numeric|min:0',
        ]);

        // 3. Gunakan Transaksi Database untuk keamanan data
        DB::transaction(function () use ($validated) {
            // Buat record baru di tabel shipments
            $this->order->shipment()->create([
                'courier'          => $validated['courier'],
                'tracking_number'  => $validated['tracking_number'],
                'shipping_cost'    => $validated['shipping_cost'],
                'shipment_date'    => now(),
                'status'           => 'shipped', // Langsung set status 'shipped'
                'full_address'     => $this->order->shipping_address,
                'receiver_name'    => $this->order->user->name, // Ambil dari data user/customer
                'receiver_phone'   => $this->order->user->customer->phone ?? 'N/A',
            ]);

            // Update status pesanan utama menjadi 'shipped'
            $this->order->update(['status' => 'shipped']);
        });
        
        // Kirim notifikasi sukses
        $this->dispatch('toastr:success', ['message' => 'Data pengiriman berhasil disimpan!']);

        // Muat ulang data untuk me-refresh tampilan
        $this->mount($this->order);
    }

    public function verifyPayment()
    {
        if ($this->order->payment) {
            // 1. Update status pembayaran menjadi 'completed'
            $this->order->payment->update(['status' => 'completed']);
            
            // 2. Update status order utama menjadi 'processing' (siap dikirim)
            $this->order->update(['status' => 'processing']);

            $this->dispatch('toastr:success', ['message' => 'Pembayaran berhasil diverifikasi!']);
            $this->mount($this->order); // Refresh data
        }
    }

    public function render()
    {
        return view('livewire.order.order-detail');
    }
}