<div>
    <div class="container py-4">
        <h2 class="mb-4">Daftar Pesanan</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th><th>Customer</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->order_date }}</td>
                                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td><span class="badge bg-warning">{{ $order->status }}</span></td>
                                <td>
                                    <a href="{{ route('orders.detail', ['order' => $order->id]) }}" class="btn btn-info btn-sm">Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
