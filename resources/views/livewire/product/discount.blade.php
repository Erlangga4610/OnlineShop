<div>
    @if(auth()->user()->can('view-discount'))
    <div class="container py-4">
        <h3 class="mb-4">Kelola Diskon</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Form Tambah / Edit -->
        <div class="card mb-4">
            <div class="card-body">
                <form wire:submit.prevent="{{ $discountId ? 'update' : 'store' }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label>Kode</label>
                            <input type="text" wire:model="code" class="form-control">
                            @error('code') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4">
                            <label>Tipe Diskon</label>
                            <select wire:model="type" class="form-select">
                                <option value="percentage">Persentase</option>
                                <option value="fixed">Nominal</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Nilai Diskon</label>
                            <input type="number" wire:model="discount_value" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Minimal Pembelian</label>
                            <input type="number" wire:model="min_purchase" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Tanggal Mulai</label>
                            <input type="date" wire:model="start_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Tanggal Berakhir</label>
                            <input type="date" wire:model="end_date" class="form-control">
                        </div>
                        <div class="col-12">
                            <label>Deskripsi</label>
                            <textarea wire:model="description" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-success">
                            {{ $discountId ? 'Update' : 'Tambah' }}
                        </button>
                        <button type="button" wire:click="resetInput" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Diskon -->
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Tipe</th>
                            <th>Nilai</th>
                            <th>Min. Pembelian</th>
                            <th>Periode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($discounts as $discount)
                            <tr>
                                <td>{{ $discount->code }}</td>
                                <td>{{ ucfirst($discount->type) }}</td>
                                <td>
                                    @if ($discount->type === 'percentage')
                                        {{ $discount->discount_value }}%
                                    @elseif ($discount->type === 'fixed')
                                        Rp {{ number_format($discount->discount_value, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>Rp {{ number_format($discount->min_purchase, 0, ',', '.') }}</td>
                                <td>{{ $discount->start_date }} - {{ $discount->end_date }}</td>
                                <td>
                                    <button wire:click="edit({{ $discount->id }})" class="btn btn-sm btn-warning">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            wire:click="$set('deleteId', {{ $discount->id }})">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada diskon</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $discounts->links() }}
            </div>
        </div>

        <!-- Modal Konfirmasi Hapus -->
        <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow-lg">
                    <!-- Header -->
                    <div class="modal-header border-0 bg-danger bg-gradient text-white rounded-top-4">
                        <h5 class="modal-title fw-bold d-flex align-items-center" id="deleteModalLabel">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus
                        </h5>
                    <button type="button" class="btn btn-sm text-white" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: none;">
                            <i class="fa-solid fa-xmark fa-lg"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-trash3-fill text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <p class="mb-0 fs-6">
                            Apakah kamu yakin ingin <span class="fw-bold text-danger">menghapus diskon ini</span>?<br>
                            <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer border-0 justify-content-center gap-2 pb-4">
                        <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="button" class="btn btn-danger px-4 rounded-pill shadow-sm"
                                wire:click="delete"
                                data-bs-dismiss="modal">
                            <i class="bi bi-trash"></i> Ya, Hapus
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

    </div>

    @else
    <div class="mb-3">
        <h3 align="center">You do not have access to this page</h3>
    </div>
    @endif
</div>
