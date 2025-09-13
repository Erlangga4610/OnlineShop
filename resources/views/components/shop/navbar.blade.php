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
                </li>
            </ul>
        </div>
    </nav>