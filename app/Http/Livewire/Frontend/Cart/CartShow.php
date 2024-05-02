<?php

namespace App\Http\Livewire\Frontend\Cart;

use Livewire\Component;
use App\Models\Carts;

class CartShow extends Component
{
    public $cart , $totalprice = 0;

    public function incrementQuantity(int $cartId)
    {
        $cartData = Carts::where('id',$cartId)->where('user_id',auth()->user()->id)->first();
        if($cartData)
        {
            if($cartData->productColor()->where('id',$cartData->product_color_id)->exists())
            {
                $productColor = $cartData->productColor()->where('id',$cartData->product_color_id)->first();
                if($productColor->quantity > $cartData->quantity)
                {
                    $cartData->increment('quantity');

                    $this->dispatchBrowserEvent('message',[
                        'text' => 'Quantity Updated',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
                else
                {
                    $this->dispatchBrowserEvent('message',[
                        'text' =>  'Only '.$productColor->quantity.' Quantity Available',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
            }
            else
            {
                if($cartData->product->quantity > $cartData->quantity)
                {
                    $cartData->increment('quantity');

                    $this->dispatchBrowserEvent('message',[
                        'text' => 'Quantity Updated',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
                else
                {
                    $this->dispatchBrowserEvent('message',[
                        'text' =>  'Only '.$cartData->product->quantity.' Quantity Available',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
            }

        }
        else
        {
            $this->dispatchBrowserEvent('message',[
                'text' => 'Something went wrong !!',
                'type' => 'error',
                'status' => 404
            ]);
        }
    }

    public function decrementQuantity(int $cartId)
    {

        $cartData = Carts::where('id',$cartId)->where('user_id',auth()->user()->id)->first();
        if($cartData)
        {
            if($cartData->productColor()->where('id',$cartData->product_color_id)->exists())
            {
                $productColor = $cartData->productColor()->where('id',$cartData->product_color_id)->first();
                if($productColor->quantity > $cartData->quantity)
                {
                    $cartData->decrement('quantity');

                    $this->dispatchBrowserEvent('message',[
                        'text' => 'Quantity Updated',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
                else
                {
                    $this->dispatchBrowserEvent('message',[
                        'text' =>  'Only '.$productColor->quantity.' Quantity Available',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
            }
            else
            {
                if($cartData->product->quantity > $cartData->quantity)
                {
                    $cartData->decrement('quantity');

                    $this->dispatchBrowserEvent('message',[
                        'text' => 'Quantity Updated',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
                else
                {
                    $this->dispatchBrowserEvent('message',[
                        'text' =>  'Only '.$cartData->product->quantity.' Quantity Available',
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
            }

        }
        else
        {
            $this->dispatchBrowserEvent('message',[
                'text' => 'Something went wrong !!',
                'type' => 'error',
                'status' => 404
            ]);
        }
    }

    public function removeCartItem(int $cartId)
    {
        $cartRemoveData = Carts::where('user_id',auth()->user()->id)->where('id',$cartId)->first();
        if($cartRemoveData)
        {
            $cartRemoveData->delete();

            $this->emit('CartAddedUpdated');
            $this->dispatchBrowserEvent('message',[
                'text' => 'Cart Item Removed Successfully',
                'type' => 'error',
                'status' => 500
            ]);
        }
        else
        {
            $this->dispatchBrowserEvent('message',[
                'text' => 'Someting went wrong !!',
                'type' => 'success',
                'status' => 200
            ]);
        }

    }

    public function render()
    {
        $this->cart = Carts::where('user_id',auth()->user()->id)->get();
        return view('livewire.frontend.cart.cart-show' , [
            'cart' => $this->cart
        ]);
    }
}
