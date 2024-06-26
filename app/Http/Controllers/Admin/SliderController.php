<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\SliderFormRequest;
use App\Models\Slider;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        return  view('admin.slider.index',compact('sliders'));
    }

    public function create()
    {
        return  view('admin.slider.create');
    }

    public function store(SliderFormRequest $request)
    {
        $validateData = $request->validated();

        if($request->hasFile('image')){
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;
            $file->move('uploads/slider/',$filename );
            $validateData['image'] = "uploads/slider/.$filename";
        }

        $validateData['status'] = $request->status == true ? '1':'0';

        Slider::create([
            'title'=> $validateData['title'],
            'description'=> $validateData['description'],
            'image'=> $validateData['image'],
            'status'=> $validateData['status']
        ]);

        return redirect('/admin/sliders')->with('message','Slider Added Sucessfully');
    }

    public function edit(Slider $slider)
    {
        return view('admin.slider.edit',compact('slider'));
    }

    public function update(SliderFormRequest $request, Slider $slider)
    {
        $validateData = $request->validated();

        if($request->hasFile('image')){

            $destination = $slider->image;
            if(File::exists($destination)){
                File::delete($destination);
            }


            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('uploads/slider/',$filename );
            $validateData['image'] = "uploads/slider/.$filename";
        }

        $validateData['status'] = $request->status == true ? '1':'0';

        Slider::where('id',$slider->id)->update([
            'title'=> $validateData['title'],
            'description'=> $validateData['description'],
            'image'=> $validateData['image'] ?? $slider->image,
            'status'=> $validateData['status']
        ]);

        return redirect('/admin/sliders')->with('message','Slider Updated Sucessfully');
    }

    public function destroy(Slider $slider)
    {
        if($slider->count() > 0 ){
            $destination = $slider->image;
                if(File::exists($destination)){
                    File::delete($destination);
                }
            $slider->delete();
            return redirect('/admin/sliders')->with('message','Slider Deleted Sucessfully');
        }

        return redirect('/admin/sliders')->with('message','Something went wrong');
    }

}
