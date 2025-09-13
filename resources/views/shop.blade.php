@extends('components.layouts.shop')

@section('content')

    <!-- Top Info Bar -->
   @include('components.shop.topbar')

    <!-- Navbar -->
    @include('components.shop.navbar')

    <!-- Search Bar -->
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <form action="#" class="d-flex shadow-sm rounded-3 overflow-hidden bg-white" method="get">
                    <input type="text" name="s" class="form-control border-0 py-3 px-4"
                           placeholder="Search products, categories, etc..." style="font-size: 1.1rem;">
                    <button class="btn btn-primary px-4" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <livewire:shop.shop-index />

    <!-- FOOTER -->
    @include('components.shop.footer')

@endsection
