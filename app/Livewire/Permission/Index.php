<?php

namespace App\Livewire\Permission;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Livewire\Attributes\Validate;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name, $guard_name = 'web', $permissionId;
    public $mode = "create";
    public $modalMode = "form"; // "form" atau "delete"
    public $search = '';
    public $modal_title = "";
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $deleteId = null;
    public $deleteName = null;

    public function create()
    {
        $this->resetInputFields();
        $this->mode = "create";
        $this->modal_title = "Add Permission";
        $this->modalMode = "form";
        $this->dispatch('open-modal');
    }

    public function store()
    {
        try {
            $validated = $this->validate([
                'name' => 'required|string|min:3|max:255|unique:permissions,name',
                'guard_name' => 'required|string|min:3|max:255',
            ]);

            Permission::create($validated);

            $this->dispatch('toastr', type: 'success', message: 'Permission added successfully ✅');
            $this->dispatch('close-modal');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: 'An error occurred while adding permission ❌');
        }
    }

    public function edit($id)
    {
        $this->resetInputFields();
        $this->mode = "edit";
        $permission = Permission::findOrFail($id);
        $this->name = $permission->name;
        $this->guard_name = $permission->guard_name;
        $this->permissionId = $permission->id;
        $this->modal_title = "Edit Permission";
        $this->modalMode = "form";
        $this->dispatch('open-modal');
    }

    public function update()
    {
        try {
            $validated = $this->validate([
                'name' => 'required|string|min:3|max:255|unique:permissions,name,' . $this->permissionId,
                'guard_name' => 'required|string|min:3|max:255',
            ]);

            Permission::findOrFail($this->permissionId)->update($validated);

            $this->dispatch('toastr', type: 'success', message: 'Permission updated successfully ✅');
            $this->dispatch('close-modal');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: 'An error occurred while updating permission ❌');
        }
    }

    public function confirmDelete($id)
    {
        $permission = Permission::findOrFail($id);
        $this->deleteId = $id;
        $this->deleteName = $permission->name;
        $this->modalMode = "delete";
        $this->dispatch('open-modal');
    }

    public function delete()
    {
        Permission::destroy($this->deleteId);

        $this->dispatch('toastr', type: 'success', message: 'Permission deleted successfully ✅');
        $this->dispatch('close-modal');
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->guard_name = '';
        $this->permissionId = null;
        $this->modal_title = "Add Permission";
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $permissions = Permission::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('guard_name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.permission.index', compact('permissions'));
    }
}
