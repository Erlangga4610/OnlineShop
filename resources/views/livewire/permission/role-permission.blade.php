<div>
    @if(auth()->user()->can('view-permission'))
    
    <h1 class="h3 mb-2 text-gray-800">Data Permissions</h1>
    <div class="card shadow mb-4">

        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">

                @can('create-role')
                    <button type="button" class="btn btn-sm btn-primary"
                        data-bs-toggle="modal"  wire:click="create">
                        Add New Role
                    </button>
                @endcan

                <div>
                    <input type="text" wire:model.live.debounce.100ms="search" name="query" class="form-control form-control-sm" style="width: 200px;" placeholder="Search...">
                </div>
            </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm small" width="100%" id="dataTable" cellspacing="0" style="font-size: 0.875;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th wire:click="sortBy('name')" style="cursor: pointer;">
                            Role {!! sortIcons('name', $sortField, $sortDirection) !!}
                        </th>
                        <th wire:click="sortBy('guard_name')" style="cursor: pointer;">
                            Guard {!! sortIcons('guard_name', $sortField, $sortDirection) !!}
                        </th>
                        <th style="width: 14%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->guard_name }}</td>
                            <td>
                                <button wire:click="edit({{ $role->id }})" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fa fa-edit"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $role->id }})" data-bs-target="#deleteModal" data-bs-toggle="modal" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="fa fa-trash"></i>
                                </button>
                                <button wire:click="show({{ $role->id }})" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail"><i class="fa fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Modal for Create and Edit -->
    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">{{ $modal_title }}</h5>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark fa-lg"></i>
                    </button>

                </div>
                <form wire:submit.prevent="{{ $mode == 'create' ? 'store' : 'update' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="role_name" class="form-label">Role Name</label>
                            <input type="text" class="form-control" id="role_name" wire:model="role_name" required>
                        </div>    
                        <div class="mb-3">
                            <label for="guard_name" class="form-label">Guard Name</label>
                            <input type="text" class="form-control" id="guard_name" wire:model="guard_name" required>
                        </div>
                        <div class="row">
                            @if(!$permissions->isEmpty())
                                @foreach ($permissions as $item)
                                    <div class="col-md-4">
                                        <input 
                                            type="checkbox" 
                                            value="{{ $item->id }}" 
                                            wire:model="selectedPermissions" 
                                            id="permission-{{ $item->id }}" 
                                        />
                                        <label for="permission-{{ $item->id }}">{{ $item->name }}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>                                          
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            {{ $mode == 'create' ? 'Save' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Role Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-3">
    <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="viewModalLabel">
            <i class="fa-solid fa-eye me-2"></i> {{ $modal_title }}
        </h5>
        <button type="button" class="btn btn-sm text-white" data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-xmark fa-lg"></i>
        </button>
    </div>
    <div class="modal-body">
        <div class="mb-4">
            <label for="view_role_name" class="form-label fw-bold">
                <i class="fa-solid fa-id-badge me-2 text-primary"></i> Role Name
            </label>
            <input type="text" class="form-control bg-light" id="view_role_name" wire:model="role_name" readonly>
        </div>

        <div class="mb-2">
            <label for="view_permissions" class="form-label fw-bold">
                <i class="fa-solid fa-lock me-2 text-success"></i> Permissions
            </label>
            <div class="row g-2">
                @foreach ($selectedPermissions as $permission)
                    <div class="col-md-4">
                        <div class="form-check border rounded-2 p-2 bg-light">
                            <input type="checkbox" class="form-check-input" id="permission-{{ $loop->index }}" disabled checked>
                            <label class="form-check-label fw-semibold text-dark" for="permission-{{ $loop->index }}">
                                {{ $permission }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa-solid fa-xmark me-2"></i> Close
        </button>
    </div>
</div>

        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                    <button wire:click="destroy" type="button" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-2"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="mb-3">
        <h3 align="center">You do not have access to this page</h3>
    </div>
    @endif

    {{-- sort icon --}}
    @php
        function sortIcons($field, $sortField, $sortDirection) {
            $icon = '<i class="bi bi-arrow-down-up text-muted"></i>';
            if ($sortField === $field) {
                $icon = $sortDirection === 'asc'
                    ? '<i class="bi bi-sort-up text-dark"></i>'
                    : '<i class="bi bi-sort-down text-dark"></i>';
            }
            return '<span class="ms-1">'.$icon.'</span>';
        }
    @endphp

    <style>
        .btn-sm i {
        font-size: 14px; /* Adjust icon size */
        margin-right: 5px; /* Add some spacing between icon and text */
        }

        .btn-info i {
        font-size: 14px; /* Adjust icon size */
        margin-right: 5px; /* Add spacing between icon and text */
        }
    </style>

    <script>
    $(document).ready(function () {
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };
    });

    document.addEventListener("livewire:init", () => {
        // Toast handler
        Livewire.on("toastr", (data) => {
            if (data.type === "success") toastr.success(data.message, "Sukses");
            else if (data.type === "error") toastr.error(data.message, "Error");
            else if (data.type === "warning") toastr.warning(data.message, "Peringatan");
            else toastr.info(data.message, "Info");
        });

        // Close semua modal
        Livewire.on("close-modal", () => {
            ["formModal", "deleteModal", "viewModal"].forEach(id => {
                const modalEl = document.getElementById(id);
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            });
            Livewire.dispatch("resetForm");
        });

        // Open form modal
        Livewire.on("openModal", () => {
            const formModal = new bootstrap.Modal(document.getElementById("formModal"));
            formModal.show();

            const deleteModal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
            if (deleteModal) deleteModal.hide();

                Livewire.dispatch("resetForm");
            });

            // Open view modal
            Livewire.on("openViewModal", () => {
                const viewModal = new bootstrap.Modal(document.getElementById("viewModal"));
                viewModal.show();
                Livewire.dispatch("resetForm");
            });
        });
    </script>


    
</div>
