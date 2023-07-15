<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    public function saveslider(Request $request) {
        $this->validate($request , [
            'description1'=> 'required',
            'description2'=> 'required',
            'image'=> 'image|nullable|max:1999'
        ]);

        //!getting file name with extension
        // print($request->file('image')->getClientOriginalName());
        $fileNameWithExt = $request->file('image')->getClientOriginalName();

        //! getting file
        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        // print($fileName);

        //! getting extension
        $ext = $request->file('image')->getClientOriginalExtension();
        // print($ext);

        //! getting file to store
        $fileNameToStore = $fileName."_".time().".".$ext;
        // print($fileNameToStore);

        //? uploading image in the laravel file
        $path = $request->file('image')->storeAs("public/slider_image", $fileNameToStore);

        $slider = new Slider();
        $slider->description1 = $request->input('description1');
        $slider->description2 = $request->input('description2');
        $slider->image = $fileNameToStore;
        $slider->save();

        return back()->with('status', 'Votre slider a été créé avec succès.');


    }

    public function deleteslider($id) {
        $slider = Slider::find($id);
        Storage::delete("public/slider_image/$slider->image");
        $slider->delete();

        return back()->with('status', 'Votre slider a été supprimer avec succès.');
    }

    public function editslider($id) {
        $slider = Slider::find($id);

        return view('admin.editslider')->with('slider', $slider);
    }

    public function updateslider($id, Request $request) {
        $slider = Slider::find($id);
        $slider->description1 = $request->input('description1');
        $slider->description2 = $request->input('description2');

        if($request->file('image')) {
            $this->validate($request , [
                'image' => 'image|nullable|max:1999'
            ]);

            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $ext = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName."_".time().".".$ext;

            Storage::delete("public/slider_image/".$slider->image);

            $path = $request->file('image')->storeAs("public/slider_image", $fileNameToStore);

            $slider->image = $fileNameToStore;
        }

        $slider->update();

        return redirect('/admin/sliders')->with('status', 'Votre slider a été modifier avec succès.');
    }

    public function unactivateslider($id) {
        $slider = Slider::find($id);
        $slider->status = 0;
        $slider->update();

        return back();
    }

    public function activateslider($id) {
        $slider = Slider::find($id);
        $slider->status = 1;
        $slider->update();

        return back();
    }

}
