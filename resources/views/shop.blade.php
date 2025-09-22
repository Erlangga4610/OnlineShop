@extends('components.layouts.shop')

@section('content')

    <!-- Top Info Bar -->
   @include('components.shop.topbar')

    <!-- Navbar -->
    @include('components.shop.navbar')

    <!-- Main Content -->
    <livewire:shop.shop-index />

    <!-- FOOTER -->
    @include('components.shop.footer')

@endsection
