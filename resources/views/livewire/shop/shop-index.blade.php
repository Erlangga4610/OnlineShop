<div>
    <div class="container my-5">

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
                                 style="height: 180px; object-fit: cover;">

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
                                    @if (!empty($product->unit))
                                        / {{ $product->unit }}
                                    @endif
                                </p>

                                <!-- Add to Cart -->
                                <div class="mt-auto">
                                    @auth
                                        <button 
                                            wire:click="addToCart({{ $product->id }})"
                                            wire:loading.attr="disabled"
                                            class="btn btn-sm btn-primary w-100 mb-2 d-flex align-items-center justify-content-center">
                                            <i class="fa-notdog fa-solid fa-cart-shopping"></i>
                                            <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                                                Add to Cart
                                            </span>
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" 
                                           class="btn btn-sm btn-outline-secondary w-100 mb-2 d-flex align-items-center justify-content-center">
                                            <i class="fa-solid fa-sign-in-alt me-2"></i>
                                            Login untuk beli
                                        </a>
                                    @endauth
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

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </section>

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
