<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Brand;
use Livewire\WithPagination;

class Brands extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $name, $brand_id;
    public $mode = '';
    public $modal_title = '';

    protected $rules = [
        'name' => 'required|string|max:255|unique:brands,name',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function create()
    {
        $this->resetInputFields();
        $this->mode = 'create';
        $this->modal_title = 'Create Brand';
    }

    public function store()
    {
        try {
            $this->validate();

            Brand::create([
                'name' => $this->name,
            ]);

            $this->dispatch('toastr', type: 'success', message: 'Brand created successfully ✅');
            $this->dispatch('close-modal');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        $this->brand_id = $brand->id;
        $this->name = $brand->name;

        $this->mode = 'edit';
        $this->modal_title = 'Edit Brand';
    }

    public function update()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:brands,name,' . $this->brand_id,
            ]);

            $brand = Brand::findOrFail($this->brand_id);
            $brand->update([
                'name' => $this->name,
            ]);

            $this->dispatch('toastr', type: 'success', message: 'Brand updated successfully ✅');
            $this->dispatch('close-modal');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->brand_id = $id;
    }

    public function destroy()
    {
        try {
            $brand = Brand::findOrFail($this->brand_id);
            $brand->delete();

            $this->dispatch('toastr', type: 'success', message: 'Brand deleted successfully 🗑️');
            $this->dispatch('close-modal');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->brand_id = null;
        $this->mode = '';
        $this->modal_title = '';
    }

    public function render()
    {
        $brands = Brand::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.product.brands', compact('brands'));
    }
}
