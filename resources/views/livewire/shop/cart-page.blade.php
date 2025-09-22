<div>

    <div class="container py-5">
        <h1 class="mb-5">
            Keranjang Belanja <i class="fa-solid fa-cart-shopping"></i>
        </h1>

        <div class="row">
            <!-- Kolom kiri -->
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body p-3">
                        @forelse($cart->items as $item)
                            <div class="row cart-item mb-3 align-items-center small">
                                <!-- Gambar produk -->
                                <div class="col-md-2">
                                    <img src="{{ $item->product->image ? asset('storage/'.$item->product->image) : 'https://via.placeholder.com/80' }}"
                                        alt="{{ $item->product->name }}"
                                        class="img-fluid rounded shadow-sm"
                                        style="max-height: 60px; object-fit: cover;">
                                </div>

                                <!-- Nama, kategori & deskripsi -->
                                <div class="col-md-5">
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    <p class="text-muted mb-0 small">
                                        {{ $item->product->category->name ?? 'Tanpa Kategori' }}
                                    </p>
                                    <small class="d-block text-truncate" style="max-width: 200px;">
                                        {{ $item->product->description ?? 'Tidak ada deskripsi' }}
                                    </small>
                                    <small class="text-primary fw-bold">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </small>
                                </div>

                                <!-- Quantity -->
                                <div class="col-md-3">
                                    <div class="d-inline-flex align-items-center">
                                        <button wire:click="decreaseQty({{ $item->id }})"
                                                class="btn btn-light btn-sm border rounded-start px-2">−</button>

                                        <input type="text"
                                            class="form-control form-control-sm text-center border-start-0 border-end-0"
                                            style="width:55px; box-shadow:none;"
                                            value="{{ $item->quantity }}" readonly>

                                        <button wire:click="increaseQty({{ $item->id }})"
                                                class="btn btn-light btn-sm border rounded-end px-2">+</button>
                                    </div>
                                </div>

                                <!-- Subtotal + Hapus -->
                                <div class="col-md-2 d-flex flex-column align-items-center justify-content-center text-center">
                                    <p class="fw-bold mb-2 text-success small">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </p>
                                    <button wire:click="confirmRemove({{ $item->id }})"
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @if(!$loop->last) <hr class="my-2"> @endif
                        @empty
                            <p class="text-muted small">Keranjang masih kosong 🛒</p>
                        @endforelse
                    </div>
                </div>

                <!-- Tombol aksi -->
                <div class="d-flex gap-2 mb-4">
                    <button onclick="window.location.href='shop'" class="btn btn-outline-primary btn-sm">
                        <i class="fa-solid fa-arrow-left me-2"></i> Lanjut Belanja
                    </button>

                    <button wire:click="buyNow" class="btn btn-danger px-3">
                        <i class="fa-solid fa-credit-card me-2"></i> Buy Now
                    </button>
                </div>
            </div>

            <!-- Kolom kanan (promo) -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body p-3 small">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa-solid fa-ticket me-2 text-danger"></i>
                            <h6 class="card-title text-danger fw-bold mb-0">SPECIAL PROMO</h6>
                        </div>
                        <p class="text-muted mb-3" style="font-size: 13px;">
                            Save more by using vouchers for all products in your shopping bag
                        </p>

                        <div class="border-top py-2 d-flex align-items-center">
                            <i class="fa-solid fa-gift text-danger me-2"></i>
                            <div>
                                <div class="fw-bold">FREE GIFT FOR YOU</div>
                                <small class="text-muted">17 Sep 2025</small>
                            </div>
                        </div>

                        <div class="border-top py-2 d-flex align-items-center">
                            <i class="fa-solid fa-percent text-danger me-2"></i>
                            <div>
                                <div class="fw-bold">REDEEM BEAUTY VOUCHER</div>
                                <small class="text-muted">17 Sep 2025</small>
                            </div>
                        </div>

                        <div class="border-top py-2 d-flex align-items-center">
                            <i class="fa-solid fa-tags text-danger me-2"></i>
                            <div>
                                <div class="fw-bold">REDEEM EXTRA DISCOUNT</div>
                                <small class="text-muted">17 Sep 2025</small>
                            </div>
                        </div>

                        <div class="border-top text-center mt-2">
                            <a href="#" class="text-danger fw-bold d-block py-2">See all</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Hapus --}}
        <div class="modal fade" id="removeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 shadow">
                    
                    {{-- Header --}}
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fa-solid fa-heart-crack me-2"></i> Hapus
                        </h5>
                        <button type="button" class="btn btn-sm text-white" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: none;">
                            <i class="fa-solid fa-xmark fa-lg"></i>
                        </button> 
                    </div>

                    {{-- Body --}}
                    <div class="modal-body text-center">
                        <i class="fa-solid fa-circle-exclamation fa-3x text-danger mb-3"></i>
                        <p class="fs-6">Apakah kamu yakin ingin menghapus <strong>{{ $productNameToDelete }}</strong> dari keranjang?</p>
                        <small class="text-muted">Produk akan hilang dari daftar keranjangmu 🛒</small>
                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i> Batal
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="removeItem" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="removeItem">
                                <i class="fa-solid fa-trash me-1"></i> Ya, Hapus
                            </span>
                            <span wire:loading wire:target="removeItem">
                                <i class="fa-solid fa-spinner fa-spin me-1"></i> Menghapus...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- JS --}}
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

                Livewire.on("openRemoveModal", () => {
                    const modal = new bootstrap.Modal(document.getElementById("removeModal"));
                    modal.show();
                });

                Livewire.on("closeRemoveModal", () => {
                    const modalEl = document.getElementById("removeModal");
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                });
            });
        </script>
    </div>
    @include('components.shop.footer')
</div>
