<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class WishlistIcon extends Component
{
    public $count = 0;

    protected $listeners = [
        'wishlistUpdate' => 'updateCount'
    ];

    public function mount()
    {
        $this->updateCount();
    }

    public function updateCount()
    {
        $this->count = Auth::check()
        ? Wishlist::where('user_id', Auth::id())->count()
        : 0;
    }

    public function render()
    {
        return view('livewire.shop.wishlist-icon');
    }
}
