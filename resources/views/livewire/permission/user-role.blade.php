<div>
    @if(auth()->user()->can('view-permission'))
    <h1 class="h3 mb-2 text-gray-800">User Role</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
                @can('create-user-role')
                    <button type="button" class="btn btn-sm btn-primary"
                        data-bs-toggle="modal" data-bs-target="#roleModal" wire:click="create">
                        Add New Role
                    </button>
                @endcan
                <div>
                    <input type="text" wire:model.live.debounce.100ms="search" name="query" class="form-control form-control-sm" style="width: 200px;" placeholder="Search...">
                </div>
            </div>
    </div>

   
    <!-- Tabel untuk menampilkan user dan role -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th style="width: 9%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($user->roles as $role)
                                    <span class="badge bg-info text-dark">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @can('edit-user-role')
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fa fa-edit" wire:click="edit({{ $user->id }})" data-bs-toggle="modal" data-bs-target="#roleModal"></i></button>
                                @endcan
                                @can('delete-user-role')
                                    <button wire:click="confirmDeleteRole({{ $user->id }}, '{{ $user->name }}')"data-bs-toggle="modal" data-bs-target="#removeRoleModal" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="fa fa-trash"></i></button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" align="center">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

     <!-- Modal untuk Create atau Edit Role pada User -->
    <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg"> 
            <div class="modal-content shadow-lg border-0 rounded-3">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="roleModalLabel">
                        <i class="fa fa-user-shield me-2"></i> 
                        {{ $isEdit ? 'Edit Role User' : 'Assign Role to User' }}
                    </h5>   
                </div>
                <div class="modal-body p-4">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">

                        <div class="mb-3">
                            <label for="user" class="form-label fw-semibold">👤 Pilih User</label>
                            <select wire:model="user_id" id="user" name="user_id" class="form-select shadow-sm">
                                <option value="">-- Pilih User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('user_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label fw-semibold">🔑 Pilih Role</label>
                            <select wire:model="role_id" class="form-select shadow-sm">
                                <option value="">-- Pilih Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @error('role_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                    </form>
                </div>

                <div class="modal-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4" wire:click.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        <i class="fa fa-save me-1"></i> {{ $isEdit ? 'Update Role' : 'Assign Role' }}
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Link Pagination -->
    <div>
        {{ $users->links() }}
    </div>

    <!-- Modal Konfirmasi Hapus Role -->
    <div class="modal fade" id="removeRoleModal" tabindex="-1" aria-labelledby="removeRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i> Confirm Delete
                    </h5>
                    <button type="button" class="btn btn-sm text-white" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: none;">
                        <i class="fa-solid fa-xmark fa-lg"></i>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-0">Are you sure you want to delete this item?<br>
                    <strong>This action cannot be undone.</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                    <button wire:click="removeRoleFromUser" type="button" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-2"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- css untuk table --}}
    <style>
        .table th, .table td {
            padding: 5px 10px; /* Mengurangi padding untuk membuat tabel lebih kecil */
            font-size: 12px; /* Mengurangi ukuran font */
        }

        .table img {
            max-width: 50px; /* Membuat gambar lebih kecil */
            height: auto;
        }

        #roleModal .modal-content {
        border-radius: 12px;
        overflow: hidden;
        }
        #roleModal .form-label {
            font-size: 14px;
        }
        #roleModal .form-select {
            font-size: 14px;
        }
    </style>

    @else
    <div class="mb-3">
        <h3 align="center">You do not have access to this page</h3>
    </div>
    @endif

    <script>
            $(document).ready(function () {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };
        });
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', () => {
                $('#roleModal').modal('hide');
                $('#removeRoleModal').modal('hide');
                
            });
            Livewire.dispatch('resetForm');

            // Toast handler
        Livewire.on("toastr", (data) => {
            if (data.type === "success") toastr.success(data.message, "Sukses");
            else if (data.type === "error") toastr.error(data.message, "Error");
            else if (data.type === "warning") toastr.warning(data.message, "Peringatan");
            else toastr.info(data.message, "Info");
        });

            Livewire.on('openModal', () => {
                    $('#roleModal').modal('show');
                    $('#removeRoleModal').modal('hide');
                    
                });
                Livewire.dispatch('resetForm');
        });
    </script>

</div>

    


