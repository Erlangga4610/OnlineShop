<div>
     <!-- Hero Section -->
        <div class="card bg-primary text-white border-0 shadow mb-5 rounded-3">
            <div class="row align-items-center p-5">
                <!-- Left Text -->
                <div class="col-md-6">
                    <h5 class="text-light">100% Organic</h5>
                    <h1 class="fw-bold display-5">Fresh Vegetables & Fruits</h1>
                    <p class="lead">
                        A blend of freshly squeezed juices and naturally grown organic products
                        delivered to your door.
                    </p>
                    <a href="#shop" class="btn btn-light btn-lg text-primary fw-bold me-3 shadow-sm">
                        <i class="fa-solid fa-cart-shopping me-2"></i> Shop Now
                    </a>
                    <a href="#lookbook" class="btn btn-outline-light btn-lg shadow-sm">View Lookbook</a>
                </div>
                <!-- Right Image -->
                <div class="col-md-6 text-center">
                    <img src="https://image.idntimes.com/post/20200419/kid-child-luffy-eats-devil-fruit-gomu-gomu-no-mi-one-piece-1c47d32b85b5d33a57ace591689b75be.jpg"
                         alt="Organic Juice"
                         class="img-fluid rounded-3 shadow">
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <section class="py-5 bg-white">
            <div class="container text-center">
                <p class="text-muted fst-italic mb-1">All the best item for You</p>
                <h2 class="fw-bold mb-5">Related Products</h2>
                <div class="row justify-content-center g-4">
                    <div class="col-6 col-md-2">
                        <div class="category-item">
                            <i class="fa-solid fa-lemon fa-3x mb-2"></i>
                            <h6 class="fw-semibold">Orange</h6>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="category-item">
                            <i class="fa-solid fa-circle-dot fa-3x mb-2"></i>
                            <h6 class="fw-semibold">Blueberries</h6>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="category-item">
                            <i class="fa-solid fa-lemon fa-3x mb-2"></i>
                            <h6 class="fw-semibold">Lemon</h6>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="category-item">
                            <i class="fa-solid fa-carrot fa-3x mb-2"></i>
                            <h6 class="fw-semibold">Vegetables</h6>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <!-- Product Grid -->
    <section id="shop" class="py-5">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            @forelse ($products as $product)
                <div class="col d-flex">
                    <div class="card shadow-sm border-0 h-100 w-100 d-flex flex-column">
                        <!-- Product Image -->
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://dummyimage.com/300x200/eee/000&text=Product' }}"
                            class="card-img-top object-fit-cover img-fluid"
                            alt="{{ $product->name }}"
                            style="height: 180px; object-fit: cover; cursor:pointer;"
                            wire:click="showProduct({{ $product->id }})">

                        <!-- Card Body -->
                        <div class="card-body d-flex flex-column text-center">
                            <h6 class="card-subtitle mb-1 text-muted text-truncate">
                                {{ $product->category->name ?? '-' }}
                            </h6>
                            <h5 class="card-title fw-bold text-black mb-2 text-truncate">
                                {{ $product->name }}
                            </h5>
                            <p class="text-muted mb-2 small">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <div class="mt-auto">
                                <button wire:click="showProduct({{ $product->id }})"
                                    class="btn btn-sm btn-outline-primary w-100">
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">Produk tidak ditemukan.</div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    </section>

    <!-- Modal Detail Produk -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                @if($selectedProduct)
                <div class="modal-body p-4">
                    <div class="row g-4">
                        
                        <!-- Gambar -->
                        <div class="col-md-5 text-center">
                            <img src="{{ $selectedProduct->image ? asset('storage/'.$selectedProduct->image) : 'https://dummyimage.com/400x400/eee/000&text=Product' }}"
                                class="img-fluid rounded shadow-sm mb-3"
                                style="max-height: 210px; object-fit: cover;">
                        </div>

                        <!-- Detail -->
                        <div class="col-md-7">
                            <h4 class="fw-bold mb-1">{{ $selectedProduct->name }}</h4>
                            <p class="text-muted small">{{ $selectedProduct->category->name ?? '-' }}</p>

                            <!-- Harga -->
                            <h5 class="text-danger fw-bold mb-2">
                                Rp {{ number_format($selectedProduct->price, 0, ',', '.') }}
                            </h5>

                            <!-- Deskripsi -->
                            <p class="small text-muted">{{ $selectedProduct->description ?? 'Tidak ada deskripsi' }}</p>

                            <!-- Quantity -->
                            <div class="d-flex align-items-center mb-4">
                                <label class="me-3 fw-semibold small text-uppercase">Quantity</label>
                                <button class="btn btn-sm btn-light border" wire:click="decrementQuantity">−</button>
                                <input type="text" class="form-control form-control-sm text-center mx-2"
                                    style="width:60px;" value="{{ $quantity }}" readonly>
                                <button class="btn btn-sm btn-light border" wire:click="incrementQuantity">+</button>
                            </div>

                            <!-- Tombol aksi -->
                            <div class="d-flex align-items-center gap-3 mt-3">

                                {{-- Add to Bag --}}
                                <button wire:click="addToCart({{ $selectedProduct->id }})"
                                    class="btn btn-outline-dark d-flex align-items-center px-4 py-2">
                                    <i class="fa-solid fa-bag-shopping me-2"></i>
                                    Add to bag
                                </button>

                                {{-- Buy Now --}}
                                <button wire:click="buyNow"
                                    class="btn text-white px-4 py-2 fw-bold"
                                    style="background-color:#dc2f52; border-radius:6px;">
                                    Buy now
                                </button>

                                {{-- Wishlist --}}
                                <button wire:click="toggleWishlist({{ $selectedProduct->id }})"
                                    class="btn border-0 bg-transparent p-2 ms-2">
                                    @if($this->isInWishlist($selectedProduct->id))
                                        <i class="fa-solid fa-heart text-danger fa-lg"></i>
                                    @else
                                        <i class="fa-regular fa-heart text-secondary fa-lg"></i>
                                    @endif
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                @endif
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
        document.addEventListener("livewire:init", () => {
            Livewire.on("openProductModal", () => {
                const modal = new bootstrap.Modal(document.getElementById("productModal"));
                modal.show();
            });

            Livewire.on("closeProductModal", () => {
                const modalEl = document.getElementById("productModal");
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            });
        });
    </script>
</div>