<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Http\Requests\CategoryFormRequest;
use App\Models\category;


class CategoryController extends Controller
{
    public function index()
    {
        return  view('admin.category.index');
    }

    public function create()
    {
        return  view('admin.category.create');
    }

    public function store(CategoryFormRequest $request)
    {
        $validateData = $request->validated();

        $category = new category();
        $category->name = $validateData['name'];
        $category->slug = Str::slug($validateData['slug']);
        $category->description = $validateData['description'];

        $uploadPath = 'uploads/category/';
        if($request->hasFile('image')){
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;
            $file->move('uploads/category/',$filename );

            $category->image = $uploadPath.$filename;
        }

        $category->meta_title = $validateData['meta_title'];
        $category->meta_description = $validateData['meta_description'];
        $category->meta_keyword = $validateData['meta_keyword'];

        $category->status = $request->status == true ? '1':'0';
        $category->save();

        return redirect('admin/category')->with('message','Category added sucessfully');

    }

    public function edit(category $category)
    {
        return view('admin.category.edit',compact('category'));
    }


    public function update(CategoryFormRequest $request,$category)
    {
        $validateData = $request->validated();
        $category = category::findOrFail($category);

        $category->name = $validateData['name'];
        $category->slug = Str::slug($validateData['slug']);
        $category->description = $validateData['description'];

        if($request->hasFile('image')){

            $uploadPath = 'uploads/category/';

            $path = 'uploads/category/'.$category->image;

            if(File::exists($path)){
                File::delete($path);
            }
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;
            $file->move('uploads/category/',$filename );

            $category->image = $uploadPath.$filename;
        }

        $category->meta_title = $validateData['meta_title'];
        $category->meta_description = $validateData['meta_description'];
        $category->meta_keyword = $validateData['meta_keyword'];

        $category->status = $request->status == true ? '1':'0';
        $category->update();

        return redirect('admin/category')->with('message','Category updated sucessfully');

    }

    public function destroy(int $categoty_id)
    {
        $category = category::findOrFail($categoty_id);

        $path = 'uploads/category/'.$category->image;
        if(File::exists($path)){
            File::delete($path);
        }

        $category->delete();
        return redirect('admin/category')->with('message','Category is Deleted');
    }


}
