<div>
    @if(auth()->user()->can('view-payment-methods'))
    <h1 class="h3 mb-2 text-gray-800">Data method Payment</h1>
    <div class="card shadow mb-4">

        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">

                @can('create-payment-methods')
                    <button type="button" class="btn btn-sm btn-primary"
                        data-bs-toggle="modal" data-bs-target="#formModal" wire:click="create">
                        Add Method
                    </button>
                @endcan

                <div>
                    <input type="text" wire:model.live.debounce.100ms="search" name="query" class="form-control form-control-sm" style="width: 200px;" placeholder="Search...">
                </div>
            </div>
    </div>

    <div class="container py-4">    
        <div class="table-responsive">
            <table class="table table-bordered table-sm small" width="100%" id="dataTable" cellspacing="0" style="font-size: 0.875;">
                <thead class="table-light">
                    <tr>

                        <th>Nama Metode</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($methods as $method)
                        <tr>
                            <td>{{ $method->method_name }}</td>
                            <td>
                                @if($method->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                @can('edit-payment-methods')
                                    <button wire:click="edit({{ $method->method_id }})" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#formModal">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                @endcan
                                @can('delete-payment-methods')
                                    <button wire:click="confirmDelete({{ $method->method_id}})" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada metode pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $methods->links() }}
        </div>

        {{-- modal --}}
        <div wire:ignore.self class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-bottom-primary">
                        <h1 class="modal-title fs-5" id="formModalLabel">
                            <i class="fas fa-credit-card me-2"></i>
                            {{ $method_id ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran' }}
                        </h1>
                        <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    </div>
                    
                    <form wire:submit="store">
                        <div class="modal-body">
                            
                            <div class="form-floating mb-3">
                                <label for="method_name">Nama Metode</label>
                                <input type="text" wire:model="method_name" id="method_name" class="form-control @error('method_name') is-invalid @enderror" placeholder="Nama Metode">
                                
                                @error('method_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                   
                                @enderror
                            </div>

                            <div class="form-floating">
                                <select wire:model="is_active" id="is_active" class="form-select @error('is_active') is-invalid @enderror">
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                                <label for="is_active">Status</label>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i> Batal
                            </button>
                            
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading wire:target="store" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span wire:loading wire:target="store">Menyimpan...</span>

                                <span wire:loading.remove wire:target="store">
                                    <i class="fas fa-save me-2"></i> Simpan
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- modal delete --}}
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

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', () => {
                const modalEl = document.getElementById('formModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
            });

            Livewire.on('close-delete-modal', () => {
                const modalEl = document.getElementById('deleteModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
            });

            const formModal = document.getElementById('formModal');
            formModal.addEventListener('hidden.bs.modal', function () {
                Livewire.dispatch('reset-form');
            });
        });
    </script>
    </div>

    @else
    <div class="mb-3">
        <h3 align="center">You do not have access to this page</h3>
    </div>
    @endif
</div>
