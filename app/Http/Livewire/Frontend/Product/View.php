<?php

namespace App\Http\Livewire\Frontend\Product;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use App\Models\Carts;

class View extends Component
{
    public $category , $product , $prodColorSelectedQuantity , $quantityCount = 1;

    public function addToWishList($productId)
    {
        //dd($productId);
        if(Auth::check())
        {
            if(Wishlist::where('user_id',auth()->user()->id)->where('product_id',$productId )->exists())
            {
                session()->flash('message','Already Added To Wishlist');
                $this->dispatchBrowserEvent('message',[
                    'text' => 'Already Added To Wishlist',
                    'type' => 'warning',
                    'status' => 409
                ]);
                return false;
            }
            else
            {
                Wishlist::create([
                'user_id' => auth()->user()->id,
                'product_id' => $productId
                ]);
                $this->emit('wishlistAddedUpdated');
                session()->flash('message','Added To Wishlist Successfully');
                $this->dispatchBrowserEvent('message',[
                    'text' => 'Added To Wishlist Successfully',
                    'type' => 'success',
                    'status' => 200
                ]);
            }

        }
        else
        {
            session()->flash('message','Please Login To Continue');
            $this->dispatchBrowserEvent('message',[
                'text' => 'Please Login To Continue',
                'type' => 'info',
                'status' => 401
            ]);
            return false;
        }
    }

    public function addToCart(int $productId)
    {
        if(Auth::check())
        {
            //dd('am in');
            if($this->product->where('id',$productId)->where('status','0')->exists())
            {
                //check for product color quantity and add to cart
                if($this->product->productColors()->count() > 1)
                {
                    if($this->prodColorSelectedQuantity != NULL)
                    {
                        if(Carts::where('user_id',auth()->user()->id)
                                ->where('product_id',$productId)
                                ->where('product_color_id',$this->productColorId)
                                ->exists())
                        {
                            $this->dispatchBrowserEvent('message',[
                                'text' => 'Product already addet to cart',
                                'type' => 'warning',
                                'status' => 200
                            ]);
                        }
                        else
                        {
                            $productColor = $this->product->productColors()->where('id',$this->productColorId)->first();
                            if($productColor->quantity > 0)
                            {
                                if( $productColor->quantity > $this->quantityCount)
                                {
                                    //insert product to cart with color
                                    Carts::create([
                                        'user_id' => auth()->user()->id,
                                        'product_id' => $productId,
                                        'quantity' => $this->quantityCount ,
                                        'product_color_id' => $this->productColorId
                                    ]);

                                    $this->emit('CartAddedUpdated');
                                    $this->dispatchBrowserEvent('message',[
                                        'text' => 'Product added to cart',
                                        'type' => 'success',
                                        'status' => 200
                                    ]);
                                }
                                else
                                {
                                    $this->dispatchBrowserEvent('message',[
                                        'text' => 'Only '.$productColor->quantity.' Quantity Available',
                                        'type' => 'warning',
                                        'status' => 404
                                    ]);
                                }
                            }
                            else
                            {
                                $this->dispatchBrowserEvent('message',[
                                    'text' => 'Out of stock',
                                    'type' => 'warning',
                                    'status' => 404
                                ]);
                            }
                        }

                    }
                    else
                    {
                        $this->dispatchBrowserEvent('message',[
                            'text' => 'Select your product color',
                            'type' => 'info',
                            'status' => 404
                        ]);
                    }
                }
                else
                {
                    if(Carts::where('user_id',auth()->user()->id)->where('product_id',$productId)->exists())
                    {
                        $this->dispatchBrowserEvent('message',[
                            'text' => 'Product already addet to cart',
                            'type' => 'warning',
                            'status' => 200
                        ]);
                    }
                    else
                    {
                        if( $this->product->quantity > 0)
                        {
                            if( $this->product->quantity > $this->quantityCount)
                            {
                                //insert product to cart without color
                                Carts::create([
                                    'user_id' => auth()->user()->id,
                                    'product_id' => $productId,
                                    'quantity' => $this->quantityCount ,
                                ]);

                                $this->emit('CartAddedUpdated');
                                $this->dispatchBrowserEvent('message',[
                                    'text' => 'Product added to cart',
                                    'type' => 'success',
                                    'status' => 200
                                ]);
                            }
                            else
                            {
                                $this->dispatchBrowserEvent('message',[
                                    'text' => 'Only '.$this->product->quantity.' Quantity Available',
                                    'type' => 'warning',
                                    'status' => 404
                                ]);
                            }
                        }
                        else
                        {
                            $this->dispatchBrowserEvent('message',[
                                'text' => 'Out of stock',
                                'type' => 'warning',
                                'status' => 404
                            ]);
                        }
                    }

                }
            }
            else
            {
                $this->dispatchBrowserEvent('message',[
                    'text' => 'Product does not exists',
                    'type' => 'warning',
                    'status' => 404
                ]);
            }
        }
        else
        {
            $this->dispatchBrowserEvent('message',[
                'text' => 'Please Login To add cart',
                'type' => 'info',
                'status' => 401
            ]);
        }
    }

    public function mount($category , $product)
    {
        $this->category = $category;
        $this->product = $product;
    }

    public function colorSelected($productColorId)
    {
        //dd($productColorId);
        $this->productColorId = $productColorId;
        $productColor = $this->product->productColors()->where('id',$productColorId)->first();
        $this->prodColorSelectedQuantity = $productColor->quantity;

        if( $this->prodColorSelectedQuantity == 0)
        {
            $this->prodColorSelectedQuantity = 'outOfStock';
        }
    }

    public function incrementQuantity()
    {
        if($this->quantityCount < 10){
            $this->quantityCount++;
        }

    }

    public function decrementQuantity()
    {
        if($this->quantityCount > 1){
            $this->quantityCount--;
        }
    }


    public function render()
    {
        return view('livewire.frontend.product.view' , [
            'category' => $this->category,
            'product' => $this->product
        ]);
    }
}
