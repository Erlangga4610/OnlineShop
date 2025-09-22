<div>
    @include('components.shop.topbar')
    @include('components.shop.navbar')

    <div class="container my-4">
        <h3 class="mb-3">My Wishlist</h3>

        @if($wishlistItems->isEmpty())
            <div class="text-center py-5">
                <i class="fa-solid fa-heart-crack fa-3x text-muted mb-3"></i>
                <p class="text-muted">Wishlist kamu masih kosong 🤍</p>
                <a href="{{ route('shop') }}" class="btn btn-primary">
                    <i class="fa-solid fa-store"></i> Belanja Sekarang
                </a>
            </div>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3">
                @foreach($wishlistItems as $item)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                class="card-img-top" 
                                alt="{{ $item->product->name }}" 
                                style="height: 180px; object-fit: cover;">

                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title mb-1">{{ $item->product->name }}</h6>
                                <p class="card-text text-muted small mb-2">
                                    {{ Str::limit($item->product->description, 40) }}
                                </p>
                                <strong class="text-primary mb-3">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </strong>

                                <div class="mt-auto d-flex justify-content-between">
                                    <button wire:click="confirmRemoveWishlist({{ $item->product->id }})" 
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-heart"></i>
                                    </button>
                                    <button wire:click="confirmAddToCart({{ $item->product->id }})" 
                                            class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Modal Konfirmasi --}}
    <div class="modal fade" id="addToCartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                
                {{-- Header --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-cart-plus me-2"></i> Konfirmasi Tambah ke Keranjang
                    </h5>
                    <button type="button" class="btn btn-sm text-white" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: none;">
                        <i class="fa-solid fa-xmark fa-lg"></i>
                    </button>                
                </div>

                {{-- Body --}}
                <div class="modal-body text-center">
                    <i class="fa-solid fa-circle-question fa-3x text-warning mb-3"></i>
                    <p class="fs-6">Apakah kamu yakin ingin menambahkan <strong>produk ini</strong> ke keranjang?</p>
                    <small class="text-muted">Produk akan langsung tersimpan di keranjangmu 🛒</small>
                </div>

                {{-- Footer --}}
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="addToCart">
                        <i class="fa-solid fa-check me-1"></i> Ya, Tambahkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Wishlist --}}
    <div class="modal fade" id="removeWishlistModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                
                {{-- Header --}}
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-heart-crack me-2"></i> Hapus dari Wishlist
                    </h5>
                    <button type="button" class="btn btn-sm text-white" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: none;">
                        <i class="fa-solid fa-xmark fa-lg"></i>
                    </button> 
                </div>

                {{-- Body --}}
                <div class="modal-body text-center">
                    <i class="fa-solid fa-circle-exclamation fa-3x text-danger mb-3"></i>
                    <p class="fs-6">Apakah kamu yakin ingin menghapus <strong>produk ini</strong> dari wishlist?</p>
                    <small class="text-muted">Produk akan hilang dari daftar wishlist-mu 💔</small>
                </div>

                {{-- Footer --}}
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="removeFromWishlist">
                        <i class="fa-solid fa-trash me-1"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('components.shop.footer')

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
        document.addEventListener('livewire:init', () => {
            // === CART ===
            Livewire.on('show-add-to-cart-modal', () => {
                const modal = new bootstrap.Modal(document.getElementById('addToCartModal'));
                modal.show();
            });
            Livewire.on('hide-add-to-cart-modal', () => {
                const modalEl = document.getElementById('addToCartModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
            });

            // === WISHLIST ===
            Livewire.on('show-remove-wishlist-modal', () => {
                const modal = new bootstrap.Modal(document.getElementById('removeWishlistModal'));
                modal.show();
            });
            Livewire.on('hide-remove-wishlist-modal', () => {
                const modalEl = document.getElementById('removeWishlistModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
            });
        });
    </script>


</div>
