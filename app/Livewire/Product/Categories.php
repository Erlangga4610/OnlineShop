<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $name, $category_id;
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $mode = '';
    public $modal_title = '';
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255|unique:categories,name',
    ];

    public function resetInputFields()
    {
        $this->name = '';
        $this->category_id = null;
    }

    public function create()
    {
        $this->resetInputFields();
        $this->mode = 'create';
        $this->modal_title = 'Create Category';
    }

    public function store()
    {
        try {
            $this->validate();

            Category::create([
                'name' => $this->name,
            ]);

            $this->dispatch('toastr', type: 'success', message: 'Category created successfully ✅');
            $this->dispatch('close-modal');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;
        $this->mode = 'edit';
        $this->modal_title = 'Edit Category';
    }

    public function update()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $this->category_id,
            ]);

            $category = Category::findOrFail($this->category_id);
            $category->update([
                'name' => $this->name,
            ]);

            $this->dispatch('toastr', type: 'success', message: 'Category updated successfully ✅');
            $this->dispatch('close-modal');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->category_id = $id;
    }

    public function destroy()
    {
        try {
            $category = Category::findOrFail($this->category_id);
            $category->delete();

            $this->dispatch('toastr', type: 'success', message: 'Category deleted successfully ✅');
            $this->dispatch('close-modal');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: 'Error: ' . $e->getMessage());
        }
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

    public function render()
    {
        $categories = Category::where('name', 'like', "%{$this->search}%")
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.product.categories', compact('categories'));
    }
}
