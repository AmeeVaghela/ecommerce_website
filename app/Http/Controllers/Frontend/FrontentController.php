<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Product;
use App\Models\category;

class FrontentController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('status','0')->get();
        $trendingProducts = Product::where('trending','1')->latest()->take(15)->get();
        $newArrivalProducts = Product::latest()->take(14)->get();
        $featuredProducts = Product::where('featured','1')->latest()->take(14)->get();
        return  view('frontend.index',compact('sliders','trendingProducts','newArrivalProducts','featuredProducts'));
    }

    public function newArrival()
    {
        $newArrivalProducts = Product::latest()->take(16)->get();
        return  view('frontend.pages.new-arrival',compact('newArrivalProducts'));
    }

    public function featuredProducts()
    {
        $featuredProducts = Product::where('featured','1')->latest()->get();
        return  view('frontend.pages.featured-products',compact('featuredProducts'));
    }

    public function categories()
    {
        $categories = category::where('status','0')->get();
        return  view('frontend.collections.category.index',compact('categories'));
    }

    public function products($category_slug)
    {
        $category = category::where('slug',$category_slug)->first();
        if($category){
            return view('frontend.collections.products.index',compact('category'));
        }
        else{
            return redirect()->back();
        }
    }

    public function productView(string $category_slug, string $product_slug)
    {
        $category = category::where('slug',$category_slug)->first();
        if($category){

            $product = $category->products()->where('slug',$product_slug)->where('status','0')->first();
            if($product){
                return view('frontend.collections.products.view',compact('product','category'));
            }else{
                return redirect()->back();
            }
        }
        else{
            return redirect()->back();
        }
    }

    public function thankyou()
    {

        return view('frontend.thank-you');
    }
}
