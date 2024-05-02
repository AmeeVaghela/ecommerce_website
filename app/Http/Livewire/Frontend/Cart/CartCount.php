<?php

namespace App\Http\Livewire\Frontend\Cart;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Carts;

class CartCount extends Component
{
    public $cartCount;

    protected $listeners = ['CartAddedUpdated' => 'checkCartCount'];

    public function checkCartCount()
    {
        if(Auth::check()){
            return $this->cartCount = Carts::where('user_id',auth()->user()->id)->count();
        }
        else
        {
            return $this->cartCount = 0;
        }
    }

    public function render()
    {
        $this->cartCount = $this->checkCartCount();
        return view('livewire.frontend.cart.cart-count' , [
            'cartCount' => $this->cartCount
        ]);
    }
}
