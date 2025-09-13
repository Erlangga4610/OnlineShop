<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CartBadge extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function render()
    {
        $count = 0;

        if (Auth::check() && Auth::user()->cart) {
            $count = Auth::user()->cart->items()->sum('quantity');
        }

        return view('livewire.shop.cart-badge', compact('count'));
    }
}
