<?php

namespace App\Livewire\Payment;

use Livewire\Component;
use App\Models\PaymentMethod;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

class PaymentMethods extends Component
{
    use WithPagination;

    public $method_id, $method_name;
    public $is_active = true;
    public $search = '';
    public $method_id_to_delete;

    public function resetForm()
    {
        $this->reset('method_id', 'method_name', 'method_id_to_delete');
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'formModal');
    }

    public function store()
    {
        $rules = [
            'method_name' => 'required|string|max:255|unique:payment_methods,method_name,' . $this->method_id . ',method_id',
            'is_active' => 'required|boolean',
        ];
    
        $validatedData = $this->validate($rules);
    
        PaymentMethod::updateOrCreate(['method_id' => $this->method_id], $validatedData);
        $this->dispatch('toastr', type: 'success', message : 'Metode pembayaran berhasil ditambahkan.');

        $this->dispatch('close-modal', 'formModal');
    }

    public function edit($id)
    {
        $this->resetErrorBag();
        $method = PaymentMethod::findOrFail($id);
        $this->method_id = $id;
        $this->method_name = $method->method_name;
        $this->is_active = $method->is_active;

        $this->dispatch('open-modal', 'formModal');
    }

    public function confirmDelete($id)
    {
        $this->method_id_to_delete = $id;
        $this->dispatch('open-modal', 'deleteModal');
    }

    public function destroy()
    {
        if ($this->method_id_to_delete) {
            PaymentMethod::find($this->method_id_to_delete)->delete();
            $this->dispatch('toastr:success', ['message' => 'Metode pembayaran berhasil dihapus.']);
            $this->dispatch('toastr', type: 'succes', message : 'Metode pembayaran berhasil dihapus.');

        }
        
        $this->dispatch('close-delete-modal', 'deleteModal');
        $this->resetForm();
    }
    
    public function render()
    {
        $methods = PaymentMethod::where('method_name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.payment.payment-methods', [
            'methods' => $methods
        ]);
    }
}
