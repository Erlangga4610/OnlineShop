<div>
    @include('components.shop.topbar')
    @include('components.shop.navbar')
    <div class="container py-5">
        <h2>Checkout</h2>
        <div class="row">
            <div class="col-md-7">
                <h4>Alamat Pengiriman</h4>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Alamat Lengkap</label>
                            <textarea wire:model="shipping_address" id="shipping_address" rows="4" class="form-control @error('shipping_address') is-invalid @enderror" placeholder="Masukkan alamat lengkap Anda..."></textarea>
                            @error('shipping_address') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <h4>Ringkasan Pesanan</h4>
                <div class="card shadow-sm">
                    <div class="card-body">
                        @if(empty($cartItems))
                            <p class="text-center">Keranjang Anda kosong.</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($cartItems as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    {{ $item['product']->name }} (x{{ $item['quantity'] }})
                                    <span>Rp {{ number_format($item['product']->price * $item['quantity'], 0, ',', '.') }}</span>
                                </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <strong>Total</strong>
                        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="d-grid mt-3">
                    <button wire:click="placeOrder" class="btn btn-primary" @if(empty($cartItems)) disabled @endif>
                        Buat Pesanan
                    </button>
                </div>
            </div>
        </div>
    </div>
    @include('components.shop.footer')
</div>
