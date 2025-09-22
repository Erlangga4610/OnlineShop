<div>
    @if(auth()->user()->can('view-permission'))
    <h1 class="h3 mb-2 text-gray-800">Data Permission</h1>
    <div class="card shadow mb-4">

        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
                @can('create-permission')
                    <button type="button" class="btn btn-sm btn-primary" wire:click="create">
                        Add Permission
                    </button>
                @endcan

                <div>
                    <input type="text" wire:model.live.debounce.100ms="search" 
                           class="form-control form-control-sm" style="width: 200px;" 
                           placeholder="Search...">
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm small" width="100%" id="dataTable" cellspacing="0" style="font-size: 0.875;">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th wire:click="sortBy('name')" style="cursor: pointer;">
                                Name Permission {!! sortIcons('name', $sortField, $sortDirection) !!}
                            </th>
                            <th wire:click="sortBy('guard_name')" style="cursor: pointer;">
                                Guard Name {!! sortIcons('guard_name', $sortField, $sortDirection) !!}
                            </th>
                            <th style="width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permissions as $index => $permission)
                        <tr>
                            <td>{{ $permissions->firstItem() + $index }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->guard_name }}</td>
                            <td>
                                <button wire:click="edit({{ $permission->id }})" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fa fa-edit"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $permission->id }})" data-bs-target="#deleteModal" data-bs-toggle="modal" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" align="center">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-2">
                {{ $permissions->links() }}
            </div>
        </div>
    </div>

    <!-- Modal (Form & Delete Digabung) -->
    <div class="modal fade" id="actionModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                @if($modalMode === 'delete')
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
                    <button wire:click="delete" type="button" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-2"></i> Delete
                    </button>
                </div>
                @else
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $modal_title }}</h5>
                        <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <form wire:submit.prevent="{{ $mode == 'create' ? 'store' : 'update' }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" wire:model.defer="name"
                                       class="form-control @error('name') is-invalid @enderror">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Guard Name</label>
                                <input type="text" wire:model.defer="guard_name"
                                       class="form-control  @error('guard_name') is-invalid @enderror">
                                @error('guard_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="btnToast" type="submit" class="btn btn-success">
                                {{ $mode == 'create' ? 'Save' : 'Update' }}
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                @endif
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
            Livewire.on("toastr", (data) => {
                if (data.type === "success") toastr.success(data.message, "Sukses");
                else if (data.type === "error") toastr.error(data.message, "Error");
                else if (data.type === "warning") toastr.warning(data.message, "Peringatan");
                else toastr.info(data.message, "Info");
            });

            Livewire.on('open-modal', () => {
                const modalEl = document.getElementById('actionModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            });

            Livewire.on('close-modal', () => {
                const modalEl = document.getElementById('actionModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            });
        });
    </script>
</div>
