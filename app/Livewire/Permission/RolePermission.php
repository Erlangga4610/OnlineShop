<?php

namespace App\Livewire\Permission;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermission extends Component
{

    use WithPagination;
    
    public $roles, $permissions, $guard_name;
    public $role_name, $role_id;
    public $modal_title, $mode;
    public $sortField = 'id'; 
    public $sortDirection = 'asc'; 
    public $search = '';
    public $selectedPermissions = [];


    public function mount()
    {
        $this->loadRoles();
        $this->permissions = Permission::all();
    }

   public function loadRoles()
    {
        $query = Role::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('guard_name', 'like', '%' . $this->search . '%');
        }

        $this->roles = $query->orderBy($this->sortField, $this->sortDirection)->get();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->modal_title = 'Add Role';
        $this->mode = 'create';
        $this->dispatch('openModal');
    }

    public function store()
    {
        try {
            $this->validate([
                'role_name' => 'required|unique:roles,name',
                'guard_name' => 'required|string|min:1|max:3',
            ]);

            $role = Role::create([
                'name' => $this->role_name,
                'guard_name' => $this->guard_name
            ]);

            if (!empty($this->selectedPermissions)) {
                $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();

                if ($permissions->count() === count($this->selectedPermissions)) {
                    $role->syncPermissions($permissions);
                } else {
                    session()->flash('error', 'One or more permissions do not exist.');
                }
            }

            $this->dispatch('toastr', type: 'success', message: 'Permission successfully added ✅');
            $this->loadRoles();
            $this->dispatch('close-modal');
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: 'An error occurred while adding the role ❌');
        }
    }

    public function edit($id)
    {
        $this->modal_title = 'Edit Role';
        $this->mode = 'edit';
        $this->role_id = $id;

        $role = Role::findOrFail($id);
        $this->role_name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();

        $this->dispatch('openModal');
    }

    public function update()
    {
        try {
            $this->validate([
                'role_name' => 'required|unique:roles,name,' . $this->role_id,
                'guard_name' => 'required|string|min:1|max:3',
            ]);

            $role = Role::findOrFail($this->role_id);
            $role->name = $this->role_name;
            $role->guard_name = $this->guard_name;
            $role->save();

            if (!empty($this->selectedPermissions)) {
                $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();

                if ($permissions->count() === count($this->selectedPermissions)) {
                    $role->syncPermissions($permissions);
                } else {
                    session()->flash('error', 'One or more permissions do not exist.');
                }
            } else {
                $role->syncPermissions([]); // Remove all permissions if none selected
            }

            $this->dispatch('toastr', type: 'success', message: 'Permission successfully added ✅');
            $this->loadRoles();
            $this->dispatch('close-modal');
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: 'An error occurred while updating the role ❌');
        }
    }

    public function confirmDelete($id) //konfirmasi delete
    {

        $role = Role::find($id);
        $this->role_id = $id;
        $this->role_name = $role ? $role->name : '';
        
        $this->dispatch('openDeleteModal');
    }

    public function destroy()
    {
        try {
            Role::destroy($this->role_id);
            $this->dispatch('toastr', type: 'success', message: 'Role successfully deleted ✅');
            $this->loadRoles();
            $this->dispatch('close-modal');
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: 'An error occurred while deleting the role ❌');
        }
    }

    public function show($id)
    {
        $this->modal_title = 'View Role';
        $this->mode = 'view';
        $this->role_id = $id;
    
        // Fetch the role details
        $role = Role::findOrFail($id);
        $this->role_name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray(); 
        // Dispatch the event to open the modal
        $this->dispatch('openViewModal');
    }    

    public function resetInputFields()
    {
        $this->role_name = '';
        $this->guard_name = '';
        $this->role_id = null;
        $this->selectedPermissions = [];
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->loadRoles(); 
    }

    public function updatedSearch()
    {
        $this->loadRoles(); 
    }

    

    public function render()
    {

        $roles = $this->roles;

        return view('livewire.permission.role-permission', [
            'roles' => $this->roles,
            'permissions' => $this->permissions,
        ]);
    }
}
