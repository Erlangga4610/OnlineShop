@extends('components.layouts.shop')

@section('title', 'Keranjang Belanja')

@section('content')


@include('components.shop.topbar')
@include('components.shop.navbar')

    @livewire('shop.cart-page')
    
    @include('components.shop.footer')
@endsection
