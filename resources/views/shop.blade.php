@extends('components.layouts.shop')

@section('content')

    <!-- Top Info Bar -->
    <div class="row bg-primary text-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom border-2 border-light">
        <div class="col-auto">
            <i class="fas fa-envelope"></i> Nothing@gmail.com
        </div>
        <div class="col-auto d-flex align-items-center">
            <a href="login" class="text-white text-decoration-none me-3">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
            <span class="mx-2">|</span>
            <a href="register" class="text-white text-decoration-none ms-3">
                <i class="fas fa-user-plus"></i> Register
            </a>
        </div>
    </div>

    <!-- Navbar -->
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
                        <a class="nav-link fw-semibold text-primary px-3" href="shop">Home</a>
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
                    <a class="btn btn-primary btn-sm px-4 shadow-sm d-flex align-items-center" href="#">
                        <i class="fa-solid fa-cart-shopping"></i>Cart
                        <span class="badge bg-white text-primary ms-2">0</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Search Bar -->
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <form action="#" class="d-flex shadow-sm rounded-3 overflow-hidden bg-white" name="desktop-search" method="get">
                    <input type="text" name="s" class="form-control border-0 py-3 px-4" placeholder="Search products, categories, etc..." style="font-size: 1.1rem;">
                    <button class="btn btn-primary px-4" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">

        <!-- Section Title -->
        <div class="card bg-primary text-white border-0 shadow mb-5 rounded-3">
            <div class="row align-items-center p-5">
                <!-- Left Text -->
                <div class="col-md-6">
                    <h5 class="text-light">100% Organic</h5>
                    <h1 class="fw-bold display-5">Fresh Vegetables & Fruits</h1>
                    <p class="lead">A blend of freshly squeezed juices and naturally grown organic products delivered to your door.</p>
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
                                
                    <!-- Grapes -->
                    <div class="col-6 col-md-2">
                        <div class="category-item">
                            <i class="fa-solid fa-lemon fa-3x mb-2"></i>
                        </div>
                    </div>
                    <!-- Blueberries -->
                    <div class="col-6 col-md-2">
                        <div class="category-item">
                            <i class="fa-solid fa-circle-dot fa-3x mb-2"></i>
                            <h6 class="fw-semibold">Blueberries</h6>
                        </div>
                    </div>
                    <!-- Lemon -->
                    <div class="col-6 col-md-2">
                        <div class="category-item">
                            <i class="fa-solid fa-lemon fa-3x mb-2"></i>
                            <h6 class="fw-semibold">Lemon</h6>
                        </div>
                    </div>
                    <!-- Vegetables -->
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
        <div class="row g-4">
            <!-- Product Card -->
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <img src="https://dummyimage.com/300x200/eee/000&text=Apple" class="card-img-top" alt="Apple">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Fresh Apple</h5>
                        <p class="text-muted mb-2">$4.99 / kg</p>
                        <a href="#" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-cart-shopping me-2"></i>Add to Cart
                        </a>
                    </div>
                </div>
            </div>
            <!-- Product Card -->
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <img src="https://dummyimage.com/300x200/eee/000&text=Orange" class="card-img-top" alt="Orange">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Organic Orange</h5>
                        <p class="text-muted mb-2">$3.50 / kg</p>
                        <a href="#" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-cart-shopping me-2"></i>Add to Cart
                        </a>
                    </div>
                </div>
            </div>
            <!-- Product Card -->
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <img src="https://dummyimage.com/300x200/eee/000&text=Banana" class="card-img-top" alt="Banana">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Sweet Banana</h5>
                        <p class="text-muted mb-2">$2.20 / bunch</p>
                        <a href="#" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-cart-shopping me-2"></i>Add to Cart
                        </a>
                    </div>
                </div>
            </div>
            <!-- Product Card -->
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <img src="https://dummyimage.com/300x200/eee/000&text=Grapes" class="card-img-top" alt="Grapes">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Red Grapes</h5>
                        <p class="text-muted mb-2">$5.80 / kg</p>
                        <a href="#" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-cart-shopping me-2"></i>Add to Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTERi --}}
    <footer class="bg-light text-dark pt-5 pb-4 mt-5 border-top">
        <div class="container">
            <div class="row">
                <!-- Logo & Contact -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="fa-solid fa-leaf text-success me-2"></i>Nothing
                    </h4>
                    <p class="mb-2"><i class="fa-solid fa-headset me-2 text-primary"></i> Got Questions ?</p>
                    <p class="fw-bold">(700) 9001-1909 (900) 689-66</p>
                </div>
                <!-- Useful Links -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Useful Links</h6>
                    <div class="row">
                        <div class="col-6">
                            <ul class="list-unstyled">
                                <li><a href="#" class="text-dark text-decoration-none d-block py-1">About Us</a></li>
                                <li><a href="#" class="text-dark text-decoration-none d-block py-1">About Our Shop</a></li>
                                <li><a href="#" class="text-dark text-decoration-none d-block py-1">Secure Shopping</a></li>
                                <li><a href="#" class="text-dark text-decoration-none d-block py-1">Delivery Information</a></li>
                                <li><a href="#" class="text-dark text-decoration-none d-block py-1">Privacy Policy</a></li>
                                <li><a href="#" class="text-dark text-decoration-none d-block py-1">Our Sitemap</a></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul class="list-unstyled">
                                <li><a href="#" class="text-dark text-decoration-none d-block py-1">Who We Are</a></li>
                                <li><a href="#" class="text-dark text-decoration-none d-block py-1">Our Services</a></li>
                                <li><a href="#" class="text-dark text-decoration-none d-block py-1">Projects</a></li>
                                <li><a href="#" class="text-dark text-decoration-none d-block py-1">Contact Us</a></li>
                                <li><a href="#" class="text-dark text-decoration-none d-block py-1">Innovation</a></li>
                                <li><a href="#" class="text-dark text-decoration-none d-block py-1">Testimonials</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Transport Offices -->
                <div class="col-lg-4 col-md-12 mb-4">
                    <h6 class="fw-bold mb-3">Transport Offices</h6>
                    <p class="mb-2"><i class="fa-solid fa-location-dot text-primary me-2"></i> 7563 St. Vicent Place, Glasgow, Greater Newyork NH7689, UK</p>
                    <p class="mb-2"><i class="fa-solid fa-phone text-primary me-2"></i> (+067) 234 789 (+068) 222 888</p>
                    <p class="mb-2"><i class="fa-solid fa-envelope text-primary me-2"></i> contact@company.com</p>
                    <p class="mb-2"><i class="fa-solid fa-clock text-primary me-2"></i> Hours: 7 Days a week from 10:00 am</p>
                    <!-- Social Icons -->
                    <div class="mt-3">
                        <a href="#" class="text-dark me-3 fs-5"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-dark me-3 fs-5"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-dark me-3 fs-5"><i class="fab fa-pinterest"></i></a>
                        <a href="#" class="text-dark me-3 fs-5"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-dark fs-5"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0 small"><span>Copyright &copy; My App {{ date('Y') }}</span></p>
            </div>
        </div>
    </footer>

@endsection
