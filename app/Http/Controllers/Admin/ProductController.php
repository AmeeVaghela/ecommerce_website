<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Color;
use App\Models\ProductColor;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Http\Requests\ProductFormRequest;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return  view('admin.products.index',compact('products'));
    }

    public function create()
    {
        $categories = category::all();
        $brands = Brand::all();
        $colors = Color::where('status','0')->get();
        return  view('admin.products.create',compact('categories','brands'),compact('colors'));
    }

    public function store(ProductFormRequest $request)
    {
        $validateData = $request->validated();

        $category = category::findOrFail($validateData['category_id']);
        $product = $category->products()->create([
            'category_id' => $validateData['category_id'],
            'name' => $validateData['name'],
            'slug' => Str::slug($validateData['slug']),
            'brand' => $validateData['brand'],
            'small_description' => $validateData['small_description'],
            'description' => $validateData['description'],
            'original_price' => $validateData['original_price'],
            'selling_price' => $validateData['selling_price'],
            'quantity' => $validateData['quantity'],
            'trending' => $request->trending == true ? '1':'0',
            'featured' => $request->featured == true ? '1':'0',
            'status' => $request->status == true ? '1':'0',
            'meta_title' => $validateData['meta_title'],
            'meta_description' => $validateData['meta_description'],
            'meta_keyword' => $validateData['meta_keyword']
        ]);


        if($request->hasFile('image')){
            $uploadPath = 'uploads/products/';

            $i = 1;
            foreach($request->file('image') as $imageFile){
                $extention = $imageFile->getClientOriginalExtension();
                $filename = time().$i++.'.'.$extention;
                $imageFile->move($uploadPath,$filename );
                $finalImagePathName = $uploadPath.$filename;

                $product->productImage()->create([
                    'product_id' => $product->id,
                    'image' => $finalImagePathName,
                ]);

            }
        }

        if($request->colors){
            foreach($request->colors as $key => $color){
                $product->productColors()->create([
                    'product_id' => $product->id,
                    'color_id' => $color,
                    'quantity' => $request->colorquantity[$key] ?? 0
                ]);
            }
        }

        return redirect('/admin/products')->with('message','Product Added Sucessfully');
    }

    public function edit(int $product_id)
    {
        $categories = category::all();
        $brands = Brand::all();
        $product = Product::findOrFail($product_id);
        $product_color = $product->productColors->pluck('color_id')->toArray();
        $colors = Color::whereNotIn('id',$product_color)->get();
        return  view('admin.products.edit',compact('categories','brands'),compact('product','colors'));
    }

    public function update(ProductFormRequest $request , int $product_id )
    {
            $validateData = $request->validated();

            $product = category::findOrFail($validateData['category_id'])
                            ->products()->where('id',$product_id)->first();
            if($product)
            {
                $product->update([
                    'category_id' => $validateData['category_id'],
                    'name' => $validateData['name'],
                    'slug' => Str::slug($validateData['slug']),
                    'brand' => $validateData['brand'],
                    'small_description' => $validateData['small_description'],
                    'description' => $validateData['description'],
                    'original_price' => $validateData['original_price'],
                    'selling_price' => $validateData['selling_price'],
                    'quantity' => $validateData['quantity'],
                    'trending' => $request->trending == true ? '1':'0',
                    'featured' => $request->featured == true ? '1':'0',
                    'status' => $request->status == true ? '1':'0',
                    'meta_title' => $validateData['meta_title'],
                    'meta_description' => $validateData['meta_description'],
                    'meta_keyword' => $validateData['meta_keyword']
                ]);



                if($request->hasFile('image')){
                    $uploadPath = 'uploads/products/';

                    $i = 1;
                    foreach($request->file('image') as $imageFile){
                        $extention = $imageFile->getClientOriginalExtension();
                        $filename = time().$i++.'.'.$extention;
                        $imageFile->move($uploadPath,$filename );
                        $finalImagePathName = $uploadPath.$filename;

                        $product->productImage()->create([
                            'product_id' => $product->id,
                            'image' => $finalImagePathName,
                        ]);

                    }
            }
            return redirect('/admin/products')->with('message','Product Updated Sucessfully');
        }
        else
        {
            return redirect('/admin/products')->with('message','No such product id found');
        }
    }

    public function destroyImage(int $product_image_id)
    {
        $productImage = ProductImage::findOrFail($product_image_id);
        if(File::exists($productImage->image)){
            File::delete($productImage->image);
         }

        $productImage->delete();
        return redirect()->back()->with('message','Product Image is deleted');
    }

    public function destroy(int $product_id)
    {
        $product = Product::findOrFail($product_id);
        if($product->productImage()){
            foreach($product->productImage() as $image){
                if(File::exists($image->image)){
                    File::delete($image->image);
                }
            }
        }
        $product->delete();
        return redirect()->back()->with('message','Product is Deleted with all its image');
    }

    public function updateProdColorQty(Request $request,$prod_color_id)
    {
        $productColorData = Product::findOrFail($request->product_id)
                                ->productColors()->where('id',$prod_color_id)->first();
        $productColorData->update([
            'quantity' =>$request->qty
        ]);

        return response()->json(['message' => 'Product Color Qty updated']);
    }

    public function deleteProdColor($prod_color_id)
    {
       $prodColor = ProductColor::findOrFail($prod_color_id);
       $prodColor->delete();
       return response()->json(['message' => 'Product Color Deleted']);
    }



}
