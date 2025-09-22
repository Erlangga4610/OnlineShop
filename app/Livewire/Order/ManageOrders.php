<?php

namespace App\Livewire\Order;

use Livewire\Component;
use App\Models\Order;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[layout('components.layouts.app')]
class ManageOrders extends Component
{
    use WithPagination;

    public function render()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('livewire.order.manage-orders', ['orders' => $orders]);
    }
}
