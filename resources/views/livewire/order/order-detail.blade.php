<div>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">Detail Pesanan #{{ $order->id }}</h2>
                <small class="text-muted">Dipesan pada: {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, H:i') }}</small>
            </div>
            <div class="d-flex align-items-center">
                <span class="me-2">Status Pesanan:</span>
                <span class="badge fs-6 {{ 
                    match($order->status) {
                        'pending', 'waiting_verification' => 'bg-warning text-dark',
                        'processing' => 'bg-info text-dark',
                        'shipped' => 'bg-primary',
                        'delivered' => 'bg-success',
                        'cancelled' => 'bg-danger',
                        default => 'bg-secondary'
                    } 
                }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0"><i class="fas fa-box-open me-2"></i>Item Pesanan</h5>
                        <span class="text-muted">{{ $order->orderItems->count() }} Produk</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-top-0 ps-4">Produk</th>
                                        <th class="text-center border-top-0">Jumlah</th>
                                        <th class="text-end border-top-0">Harga Satuan</th>
                                        <th class="text-end pe-4 border-top-0">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td class="ps-4">{{ $item->product->name ?? 'N/A' }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-end pe-4">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="m-0"><i class="fas fa-credit-card me-2"></i>Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        @if ($order->payment)
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Metode:</strong> {{ $order->payment->paymentMethod->method_name }}</p>
                                    <p><strong>Tgl Konfirmasi:</strong> {{ \Carbon\Carbon::parse($order->payment->payment_date)->format('d M Y') }}</p>
                                    <p class="mb-0"><strong>Status:</strong> <span class="badge bg-primary">{{ $order->payment->status }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    @if ($order->payment->proof_of_payment)
                                        <strong>Bukti Bayar:</strong><br>
                                        <a href="{{ asset('storage/' . $order->payment->proof_of_payment) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $order->payment->proof_of_payment) }}" class="img-fluid rounded border" style="max-height: 120px;" alt="Bukti Bayar">
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @if ($order->payment->status === 'pending')
                                <hr>
                                <button wire:click="verifyPayment" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i> Verifikasi Pembayaran
                                </button>
                            @endif
                        @else
                            <p class="text-muted text-center">Pelanggan belum melakukan konfirmasi pembayaran.</p>
                        @endif
                    </div>
                </div>

                </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="m-0"><i class="fas fa-user-circle me-2"></i>Detail Ringkasan</h5>
                    </div>
                    <div class="card-body">
                        <h6>Customer</h6>
                        <p class="mb-0">{{ $order->user->name }}</p>
                        <p class="text-muted">{{ $order->user->email }}</p>
                        <hr>
                        <h6>Alamat Pengiriman</h6>
                        <p class="text-muted">{{ $order->shipping_address }}</p>
                        <hr>
                        <h6>Ringkasan Biaya</h6>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Subtotal Produk</span>
                            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Ongkos Kirim</span>
                            <span>Rp {{ number_format($order->shipment->shipping_cost ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total Pesanan</span>
                            <span>Rp {{ number_format($order->total + ($order->shipment->shipping_cost ?? 0), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>