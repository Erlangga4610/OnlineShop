<?php
namespace App\Livewire\Order;

use App\Models\Order;
use App\Models\PaymentMethod;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class OrderDetailPage extends Component
{
    use WithFileUploads;

    public Order $order;

    public $payment_method_id;
    public $proof_of_payment;
    public $return_reason;

    public function requestReturn()
    {
        if ($this->order->status !== 'delivered') {
            $this->dispatch('toastr::error', ['message' => 'Pesanan belum bisa dikembalikan']);
            return;
        }

        $this->validate(['return_reason' => 'required|string|min:10']);

        $this->order->returnRequest()->create([
            'reason' => $this->return_reason,
            'status' => 'requested',
        ]);

        $this->order->update(['status' => 'return_requested']);

        $this->dispatch('toastr:success', ['message' => 'Permintaan pengembalian telah dikirim.']);
        $this->mount($this->order);
    }


    public function mount(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        $this->order = $order->load('payment');
    }

    public function confirmPayment()
    {
        $this->validate([
            'payment_method_id' => 'required|exists:payment_methods,method_id',
            'proof_of_payment' => 'required|image|max:2048', // Maks 2MB
        ]);

        $path = $this->proof_of_payment->store('proofs', 'public');

        $this->order->payment()->create([
            'payment_method_id' => $this->payment_method_id,
            'payment_date' => now(),
            'amount' => $this->order->total,
            'status' => 'pending', // Status awal: menunggu verifikasi admin
            'proof_of_payment' => $path,
        ]);

        // Update status order utama
        $this->order->update(['status' => 'waiting_verification']);
        
        $this->dispatch('toastr:success', ['message' => 'Konfirmasi pembayaran berhasil diunggah!']);
        $this->mount($this->order); // Refresh data
    }

    public function render()
    {
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        return view('livewire.order.order-detail-page', [
            'paymentMethods' => $paymentMethods
        ]);
    }
}