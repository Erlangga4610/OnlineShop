 <div class="row bg-primary text-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom border-2 border-light">
            <div class="col-auto">
                <i class="fas fa-envelope"></i> Nothing@gmail.com
            </div>
            <div class="col-auto d-flex align-items-center">
        @guest
            <a href="{{ route('login') }}" class="text-white text-decoration-none me-3">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
            <span class="mx-2">|</span>
            <a href="{{ route('register') }}" class="text-white text-decoration-none ms-3">
                <i class="fas fa-user-plus"></i> Register
            </a>
        @endguest

        @auth
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link text-white text-decoration-none">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        @endauth
    </div>
    </div>