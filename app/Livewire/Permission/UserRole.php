<?php
namespace App\Livewire\Permission;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserRole extends Component
{
    public $user_id, $role_id;
    public $user_name, $user_email;
    public $roles;
    public $isEdit = false;
    public $selectedUserId = null;
    public $search = '';
    
    protected $listeners = ['resetForm' => 'resetFields'];// Mendengarkan event 'resetForm' dan menjalankan metode 'resetInputFields' saat event dipicu.


    use WithPagination; // Trait untuk pagination
    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'user_id' => 'required',
        'role_id' => 'required',
        
    ];

    // Method untuk mengambil data role
    public function mount()
    {
        $this->roles = Role::all();  // Mengambil semua role
        $this->resetFields();
    }

    public function create()
    {
       try {
            $this->resetFields();
            $this->isEdit = false;
            $this->dispatch('openModal');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    // Method untuk menyimpan role pada user
    public function store()
{
    try {
        $this->validate();

        $user = User::find($this->user_id);
        $role = Role::find($this->role_id);

        if ($user && $role) {
            if ($user->hasRole($role->name)) {
                $this->dispatch('toastr', type: 'error', message: 'User sudah memiliki role ini ❌');
                $this->dispatch('close-modal'); // auto close
                return;
            }

            // 🔍 Cek apakah user sudah punya role lain
            if ($user->roles->count() > 0) {
                $this->dispatch('toastr', type: 'warning', message: 'User sudah memiliki role lain. Silakan update, bukan assign.');
                $this->dispatch('close-modal'); // auto close
                return;
            }

            $user->assignRole($role);
            $this->dispatch('toastr', type: 'success', message: 'Role successfully assigned ✅');
            $this->resetFields();
            $this->dispatch('close-modal');
        }
    } catch (\Exception $e) {
        $this->dispatch('toastr', type: 'error', message: 'Terjadi error: ' . $e->getMessage());
    }
}


    // Method untuk mengedit role pada user
    public function edit($id)
    {
        $this->isEdit = true;

        $user = User::find($id);
        $this->user_id = $user->id;
        $this->user_name = $user->name;
        $this->user_email = $user->email;
        $this->role_id = $user->roles->first()->id ?? null;  // Ambil role pertama jika ada
        $this->dispatch('close-modal');

    }

    // Method untuk mengupdate role pada user
    public function update()
    {
        try {
            $this->validate();

            $user = User::find($this->user_id);
            $role = Role::find($this->role_id);

            if ($user && $role) {
                $user->syncRoles([$role]);  // Mengupdate role pada user
                $this->dispatch('toastr', type: 'success', message: 'Role successfully updated ✅');
                $this->resetFields();
                $this->dispatch('close-modal');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    // Method untuk menghapus role pada user
    public function confirmDeleteRole($userId)
    {
        $this->selectedUserId = $userId;
        $user = User::find($userId);
        $this->user_name = $user->name;  // Menyimpan nama user untuk konfirmasi
    }

    public function removeRoleFromUser()
    {
        try {
            if ($this->selectedUserId) {
                $user = User::find($this->selectedUserId);
                if ($user) {
                    $user->syncRoles([]);  // Menghapus semua role dari user
                    $this->dispatch('toastr', type: 'success', message: 'Role successfully removed from user ✅');
                    $this->resetFields();
                    $this->dispatch('close-modal');
                }
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    // Method untuk reset form
    public function resetFields()
    {
        $this->role_id = null;
        $this->user_id = null;
        $this->user_name = '';
        $this->user_email = '';
        $this->isEdit = false;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


    // Render the view
    public function render()
    {
        // Memanggil data users dengan pagination
        $users = User::where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%')
                            ->paginate(10);

        return view('livewire.permission.user-role', compact('users'));
    }
}
