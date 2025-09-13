
<div class="container py-5">
    
    <h1 class="mb-5">
        Keranjang Belanja <i class="fa-solid fa-cart-shopping"></i>
    </h1>

    <div class="row">
        <!-- Kolom kiri: daftar produk -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    @forelse($cart->items as $item)
                        <div class="row cart-item mb-4 align-items-center">
                            <!-- Gambar produk -->
                            <div class="col-md-3">
                                <img src="{{ $item->product->image ? asset('storage/'.$item->product->image) : 'https://via.placeholder.com/100' }}"
                                     alt="{{ $item->product->name }}"
                                     class="img-fluid rounded shadow-sm">
                            </div>

                            <!-- Nama & kategori -->
                            <div class="col-md-5">
                                <h5 class="card-title mb-1">{{ $item->product->name }}</h5>
                                <p class="text-muted mb-0">
                                    {{ $item->product->category->name ?? 'Tanpa Kategori' }}
                                </p>
                                <small class="text-primary fw-bold">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </small>
                            </div>

                            <!-- Quantity -->
                            <div class="col-md-2">
                                <div class="input-group input-group-sm">
                                    <button wire:click="decreaseQty({{ $item->id }})"
                                            class="btn btn-outline-secondary">-</button>
                                    <input type="text"
                                           class="form-control text-center"
                                           style="max-width:70px"
                                           value="{{ $item->quantity }}"
                                           readonly>
                                    <button wire:click="increaseQty({{ $item->id }})"
                                            class="btn btn-outline-secondary">+</button>
                                </div>
                            </div>

                            <!-- Subtotal + Hapus -->
                            <div class="col-md-2 text-end">
                                <p class="fw-bold mb-2 text-success">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </p>
                                <button wire:click="removeItem({{ $item->id }})"
                                        class="btn btn-sm btn-outline-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        @if(!$loop->last) <hr> @endif
                    @empty
                        <p class="text-muted">Keranjang masih kosong 🛒</p>
                    @endforelse
                </div>
            </div>

            <!-- Tombol lanjut belanja -->
            <div class="text-start mb-4">
                <a href="shop" class="btn btn-outline-primary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Lanjut Belanja
                </a>
            </div>
        </div>

        <!-- Kolom kanan: ringkasan order -->
        <div class="col-lg-4">
            <div class="card cart-summary">
                <div class="card-body">
                    <h5 class="card-title mb-4">Ringkasan Pesanan</h5>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal</span>
                        <span>
                            Rp {{ number_format($cart->items->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Ongkir</span>
                        <span>Rp 0</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total</strong>
                        <strong class="text-primary">
                            Rp {{ number_format($cart->items->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}
                        </strong>
                    </div>

                    <button class="btn btn-success w-100">
                        <i class="fa-solid fa-credit-card me-2"></i> Checkout
                    </button>
                </div>
            </div>

            <!-- Promo code -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Gunakan Kode Promo</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Masukkan kode promo">
                        <button class="btn btn-outline-secondary" type="button">Apply</button>
                    </div>
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
