<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 mt-3 rounded-3 px-3">
        <a class="navbar-brand fw-bold text-primary fs-3" href="#">
            <i class="fas fa-leaf me-2 text-success"></i>Nothing
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="mx-auto">
                <ul class="navbar-nav align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-primary px-3" href="{{ url('/shop') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-primary px-3" href="#">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-primary px-3" href="#">Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-primary px-3" href="#">Contact</a>
                    </li>
                </ul>
            </div>
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                    @livewire('shop.cart-badge')
                    @livewire('shop.wishlist-icon')
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-3 mb-4"> {{-- ganti my-4 jadi mt-3 --}}
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <form action="#" method="get" 
                  class="d-flex align-items-center bg-white shadow-lg rounded-pill overflow-hidden px-3 py-2">
                
                <i class="fas fa-search text-muted ms-2 me-3"></i>
                
                <input type="text" name="s" 
                       class="form-control border-0 shadow-none py-2 px-2"
                       placeholder="Search products, categories, etc..." 
                       style="font-size: 1rem;">
                
                <button class="btn btn-primary rounded-pill px-4" type="submit">
                    Search
                </button>
            </form>
        </div>
    </div>
</div>
