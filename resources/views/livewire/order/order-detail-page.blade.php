<div>
   <div class="container py-5">
    <h2>Detail Pesanan #{{ $order->id }}</h2>
    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h5 class="m-0">Status Pembayaran</h5>
        </div>
        <div class="card-body">
            @if ($order->payment)
                <p><strong>Status:</strong> <span class="badge bg-info">{{ $order->payment->status }}</span></p>
                <p>Anda telah melakukan konfirmasi pada tanggal {{ $order->payment->payment_date }}.</p>
                <p>Admin akan segera memverifikasi pembayaran Anda.</p>
            @else
                <p>Silakan lakukan pembayaran dan unggah bukti transfer di bawah ini.</p>
                <form wire:submit="confirmPayment">
                    <div class="mb-3">
                        <label for="payment_method_id" class="form-label">Metode Pembayaran</label>
                        <select wire:model="payment_method_id" id="payment_method_id" class="form-select">
                            <option value="">Pilih Metode</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->method_id }}">{{ $method->method_name }}</option>
                            @endforeach
                        </select>
                        @error('payment_method_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="proof_of_payment" class="form-label">Unggah Bukti Pembayaran</label>
                        <input type="file" wire:model="proof_of_payment" id="proof_of_payment" class="form-control">
                        @error('proof_of_payment') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Konfirmasi Pembayaran</button>
                </form>
            @endif
        </div>
    </div>
    </div>

    <div class="card shadow-sm mt-4">
    <div class="card-header"><h5 class="m-0">Pengembalian Barang</h5></div>
    <div class="card-body">
        @if ($order->returnRequest)
            <p>Anda telah meminta pengembalian pada {{ $order->returnRequest->created_at->format('d M Y') }}.</p>
            <p><strong>Status:</strong> <span class="badge bg-info">{{ $order->returnRequest->status }}</span></p>
            <p><strong>Alasan:</strong> {{ $order->returnRequest->reason }}</p>
        @elseif ($order->status === 'delivered')
            <form wire:submit="requestReturn">
                <div class="mb-3">
                    <label for="return_reason" class="form-label">Alasan Pengembalian</label>
                    <textarea wire:model="return_reason" class="form-control" rows="3"></textarea>
                    @error('return_reason') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-warning">Ajukan Pengembalian</button>
            </form>
        @else
            <p class="text-muted">Opsi pengembalian akan tersedia setelah pesanan diterima.</p>
        @endif
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">Manajemen Pengembalian & Refund</h6></div>
    <div class="card-body">
        @if ($order->returnRequest)
            <p><strong>Permintaan dari Customer:</strong><br>{{ $order->returnRequest->reason }}</p>
            <p><strong>Status Saat Ini:</strong> <span class="badge bg-info">{{ $order->returnRequest->status }}</span></p>
            <hr>

            @if ($order->returnRequest->status === 'requested')
                <div class="d-flex gap-2">
                    <button wire:click="updateReturnStatus('approved')" class="btn btn-success">Setujui</button>
                    <button wire:click="updateReturnStatus('rejected')" class="btn btn-danger">Tolak</button>
                </div>
            @endif

            @if ($order->returnRequest->status === 'approved' && !$order->returnRequest->refund)
                <h6 class="mt-3">Proses Pengembalian Dana (Refund)</h6>
                <form wire:submit="issueRefund">
                    <div class="mb-3"><input type="number" wire:model="refund_amount" class="form-control" placeholder="Jumlah Refund"></div>
                    <div class="mb-3"><input type="text" wire:model="refund_method" class="form-control" placeholder="Metode (cth: Transfer Bank)"></div>
                    <button type="submit" class="btn btn-primary">Proses Refund</button>
                </form>
            @endif

            @if ($order->returnRequest->refund)
                <h6 class="mt-3">Detail Refund</h6>
                <p><strong>Jumlah:</strong> Rp {{ number_format($order->returnRequest->refund->amount, 0, ',', '.') }}</p>
                <p><strong>Metode:</strong> {{ $order->returnRequest->refund->method }}</p>
                <p><strong>Status:</strong> <span class="badge bg-success">{{ $order->returnRequest->refund->status }}</span></p>
            @endif
        @else
            <p class="text-muted text-center">Tidak ada permintaan pengembalian untuk pesanan ini.</p>
        @endif
    </div>
</div>
</div>
