<div>
    @if (auth()->user()->can('view-product'))
    <h1 class="h3 mb-2 text-gray-800">Data Product</h1>
    <div class="card shadow mb-4">

        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">

                @can('create-product')
                    <button type="button" class="btn btn-sm btn-primary"
                        data-bs-toggle="modal" data-bs-target="#formModal" wire:click="create">
                        Add Product
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
                            <th wire:click="sortBy('name')" style="cursor: pointer;">
                                Name {!! sortIcons('name', $sortField, $sortDirection) !!}
                            </th>
                            <th wire:click="sortBy('brand_id')" style="cursor: pointer;">
                                Brand {!! sortIcons('brand_id', $sortField, $sortDirection) !!}
                            </th>
                             <th wire:click="sortBy('category_id')" style="cursor: pointer;">
                                Category {!! sortIcons('category_id', $sortField, $sortDirection) !!}
                            </th>
                            <th wire:click="sortBy('price')" style="cursor: pointer;">
                                Price {!! sortIcons('price', $sortField, $sortDirection) !!}
                            </th>
                            <th wire:click="sortBy('description')" style="cursor: pointer;">
                                Description {!! sortIcons('description', $sortField, $sortDirection) !!}
                            </th>
                           <th wire:click="sortBy('stock')" style="cursor: pointer;">
                                Stock {!! sortIcons('stock', $sortField, $sortDirection) !!}
                            </th>
                            <th wire:click="sortBy('image')" style="cursor: pointer;">
                                Image {!! sortIcons('image', $sortField, $sortDirection) !!}
                            </th>
                            <th style="width: 8%">Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $p)
                            <tr>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->brand?->name ?? '-' }}</td>
                                <td>{{ $p->category?->name ?? '-' }}</td>   
                                <td>Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                                <td>{{ $p->description }}</td>
                                <td>{{ $p->stock }}</td>
                                <td>
                                    <img src="{{ asset('/storage/'.$p->image) }}" class="rounded" style="max-width: 30px; object-fit: cover;">
                                </td>
                                <td>
                                    @can('edit-product')
                                        <button wire:click="edit({{ $p->id }})" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#formModal">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    @endcan
                                    @can('delete-product')
                                        <button wire:click="confirmDelete({{ $p->id }})" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No products available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $products->links('') }}
            </div>
        </div>
    </div>

   <!-- Modal Form -->
    <div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $modal_title }}</h5>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="{{ $mode == 'create' ? 'store' : 'update' }}" autocomplete="off">
                    <div class="modal-body">
                       <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" wire:model.defer="name"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select wire:model.defer="brand_id" 
                                    class="form-select @error('brand_id') is-invalid @enderror">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select wire:model.defer="category_id" 
                                    class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">-- Pilih Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" wire:model.defer="price"
                                    class="form-control @error('price') is-invalid @enderror">
                            </div>
                            @error('price') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" wire:model.defer="stock"
                                class="form-control @error('stock') is-invalid @enderror">
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea wire:model.defer="description"
                                    class="form-control @error('description') is-invalid @enderror"></textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" wire:model="image"
                                class="form-control @error('image') is-invalid @enderror">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail mt-2" width="100">
                            @elseif ($oldImage)
                                <img src="{{ asset('storage/'.$oldImage) }}" class="img-thumbnail mt-2" width="100" alt="">
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnToast" type="submit" class="btn btn-primary">
                            {{ $mode == 'create' ? 'Save' : 'Update' }}
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
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
                    <button wire:click="delete" type="button" class="btn btn-danger">
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
            // Default: ikon netral
            $icon = '<i class="bi bi-arrow-down-up text-muted"></i>';

            if ($sortField === $field) {
                if ($sortDirection === 'asc') {
                    $icon = '<i class="bi bi-sort-up text-dark"></i>';   // urut naik
                } else {
                    $icon = '<i class="bi bi-sort-down text-dark"></i>'; // urut turun
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


