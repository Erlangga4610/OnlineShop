<div>
    @if(auth()->user()->can('view-categories'))
    <h1 class="h3 mb-2 text-gray-800">Data Category</h1>
    <div class="card shadow mb-4">

        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">

                @can('create-categories')
                    <button type="button" class="btn btn-sm btn-primary"
                        data-bs-toggle="modal" data-bs-target="#formModal" wire:click="create">
                        Add Category
                    </button>
                @endcan

                <div>
                    <input type="text" wire:model.live.debounce.100ms="search" name="query"
                        class="form-control form-control-sm" style="width: 200px;" placeholder="Search...">
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm small" width="100%" id="dataTable"
                    cellspacing="0" style="font-size: 0.875;">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('name')" style="cursor: pointer;">
                                Name {!! sortIcons('name', $sortField, $sortDirection) !!}
                            </th>
                            <th style="width: 8%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $c)
                            <tr>
                                <td>{{ $c->name }}</td>
                                <td>
                                    @can('edit-categories')
                                        <button wire:click="edit({{ $c->id }})" class="btn btn-sm btn-outline-warning"
                                            data-bs-toggle="modal" data-bs-target="#formModal">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    @endcan
                                    @can('delete-categories')
                                        <button wire:click="confirmDelete({{ $c->id }})"
                                            class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endcan
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-3">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div wire:ignore.self class="modal fade" id="formModal" tabindex="-1"
        aria-labelledby="formModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">{{ $modal_title }}</h5>
                    <button type="button" class="btn btn-sm text-white" data-bs-dismiss="modal"
                        aria-label="Close" style="background: transparent; border: none;">
                        <i class="fa-solid fa-xmark fa-lg"></i>
                    </button>
                </div>
                <form wire:submit.prevent="{{ $mode === 'edit' ? 'update' : 'store' }}" autocomplete="off">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" wire:model.defer="name" id="name" class="form-control">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            {{ $mode === 'edit' ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i> Confirm Delete
                    </h5>
                    <button type="button" class="btn btn-sm text-white" data-bs-dismiss="modal"
                        aria-label="Close" style="background: transparent; border: none;">
                        <i class="fa-solid fa-xmark fa-lg"></i>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-0">Are you sure you want to delete this category?<br>
                        <strong>This action cannot be undone.</strong>
                    </p>
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
                if ($sortDirection === 'asc') {
                    $icon = '<i class="bi bi-sort-up text-dark"></i>';
                } else {
                    $icon = '<i class="bi bi-sort-down text-dark"></i>';
                }
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

        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', () => {
                ['#formModal', '#deleteModal'].forEach(m => $(m).modal('hide'));
            });

            Livewire.on("toastr", (data) => {
                if (data.type === "success") {
                    toastr.success(data.message, "Sukses");
                } else if (data.type === "error") {
                    toastr.error(data.message, "Error");
                } else if (data.type === "warning") {
                    toastr.warning(data.message, "Peringatan");
                } else {
                    toastr.info(data.message, "Info");
                }
            });
        });
    </script>
</div>
