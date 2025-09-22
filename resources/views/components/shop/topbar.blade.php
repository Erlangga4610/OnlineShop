<div class="row bg-primary text-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom border-2 border-light">
    <div class="col-auto">
         <i class="fas fa-envelope"></i> Nothing@gmail.com
    </div>

    <div class="col-auto d-flex align-items-center">
        @guest
            <a href="{{ route('login') }}" class="text-white text-deciration-none me-3">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
            <span class="mx-2">|</span>
            <a href="{{ route('register') }}" class="text-white text-decoration-none ms-3">
                <i class="fas fa-user-plus"></i> Register
            </a>
        @endguest

        @auth
            <!-- Dropdown Profile -->
            <div class="dropdown">
                <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                   href="#" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="me-2">Hi, {{ Auth::user()->name }}</span>
                    <img src="https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg"
                         alt="Avatar"
                         class="rounded-circle border border-2 border-light"
                         width="35" height="35">
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2"
                    aria-labelledby="userDropdown" style="min-width: 250px;">
                    
                    <!-- Profile Header -->
                    <li class="px-3 py-2 border-bottom">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/50"
                                 class="rounded-circle me-3"
                                 width="50" height="50">
                            <div>
                                <strong>{{ Auth::user()->name }}</strong>
                                <div class="text-muted small">{{ '@'.Str::slug(Auth::user()->name) }}</div>
                                <a href="{{ route('profile') }}" class="small text-primary fw-bold">View Profile</a>
                            </div>
                        </div>
                    </li>

                    <!-- Menu -->
                    <li><a class="dropdown-item py-2" href="">My Orders</a></li>
                    <li><a class="dropdown-item py-2" href="#">Shipping Address</a></li>
                    <li><a class="dropdown-item py-2" href="#">Review</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('wishlist') }}">Wishlist</a></li>

                    <li><hr class="dropdown-divider"></li>

                    <!-- Logout -->
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="d-flex align-items-center px-3 py-2">
                            @csrf
                            <button type="submit" class="btn btn-link text-danger text-decoration-none p-0">
                                <i class="fas fa-sign-out-alt me-2"></i> Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</div>