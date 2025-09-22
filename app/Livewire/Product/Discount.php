<?php

namespace App\Livewire\Product;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Discount as D;

class Discount extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $code, $description, $type = 'percentage', $discount_value, $min_purchase, $start_date, $end_date;
    public $discountId; // untuk edit
    public $deleteId;   // untuk konfirmasi hapus

    protected $rules = [
        'code'           => 'required|string|unique:discounts,code',
        'description'    => 'nullable|string',
        'type'           => 'required|in:percentage,fixed',
        'discount_value' => 'required|numeric|min:0',
        'min_purchase'   => 'nullable|numeric|min:0',
        'start_date'     => 'nullable|date',
        'end_date'       => 'nullable|date|after_or_equal:start_date',
    ];

    public function resetInput()
    {
        $this->code = $this->description = $this->type = $this->discount_value = 
        $this->min_purchase = $this->start_date = $this->end_date = null;

        $this->discountId = null;
        $this->type = 'percentage';
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        D::create([
            'code'           => $this->code,
            'description'    => $this->description,
            'type'           => $this->type,
            'discount_value' => $this->discount_value,
            'min_purchase'   => $this->min_purchase ?? 0,
            'start_date'     => $this->start_date,
            'end_date'       => $this->end_date,
        ]);

        $this->dispatch('toastr', type: 'success', message: 'Discount berhasil ditambahkan 🎉');
        $this->resetInput();
    }

    public function edit($id)
    {
        $discount = D::findOrFail($id);

        $this->discountId     = $discount->id;
        $this->code           = $discount->code;
        $this->description    = $discount->description;
        $this->type           = $discount->type;
        $this->discount_value = $discount->discount_value;
        $this->min_purchase   = $discount->min_purchase;
        $this->start_date     = $discount->start_date;
        $this->end_date       = $discount->end_date;
    }

    public function update()
    {
        $this->validate([
            'code'           => 'required|string|unique:discounts,code,' . $this->discountId,
            'description'    => 'nullable|string',
            'type'           => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase'   => 'nullable|numeric|min:0',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
        ]);

        $discount = D::findOrFail($this->discountId);

        $discount->update([
            'code'           => $this->code,
            'description'    => $this->description,
            'type'           => $this->type,
            'discount_value' => $this->discount_value,
            'min_purchase'   => $this->min_purchase ?? 0,
            'start_date'     => $this->start_date,
            'end_date'       => $this->end_date,
        ]);

        $this->dispatch('toastr', type: 'success', message: 'Discount berhasil diupdate ✨');
        $this->resetInput();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id; // simpan id di property
    }

    public function delete()
    {
        if ($this->deleteId) {
            D::findOrFail($this->deleteId)->delete();
            $this->dispatch('toastr', type: 'success', message: 'Discount berhasil dihapus 🗑️');
            $this->deleteId = null;
        }
    }

    public function render()
    {
        $discounts = D::latest()->paginate(10);
        return view('livewire.product.discount', compact('discounts'));
    }
}
