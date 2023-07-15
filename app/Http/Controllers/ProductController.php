<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function saveproduct(Request $request) {
        $this->validate($request , [
            'product_name'=> 'required',
            'product_price'=> 'required',
            'product_category'=> 'required',
            'product_image'=> 'image|nullable|max:1999'
        ]);

        $fileNameWithExt = $request->file('product_image')->getClientOriginalName();
        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        $ext = $request->file('product_image')->getClientOriginalExtension();
        $fileNameToStore = $fileName."_".time().".".$ext;

        $path = $request->file('product_image')->storeAs("public/product_images", $fileNameToStore);

        $product = new Product();
        $product->product_name = $request->input('product_name');
        $product->product_price = $request->input('product_price');
        $product->product_category = $request->input('product_category');
        $product->product_image = $fileNameToStore;
        $product->save();

        return back()->with('status', 'Votre produit a été créé avec succès.');
    }

    public function deleteproduct($id) {
        $product = Product::find($id);
        Storage::delete('public/product_images/'.$product->product_image);
        $product->delete();

        return back()->with('status', 'Votre produit a été supprimer avec succès.');
    }

    public function unactivateproduct($id) {
        $product = Product::find($id);
        $product->status = 0;
        $product->update();

        return back();
    }

    public function activateproduct($id) {
        $product = Product::find($id);
        $product->status = 1;
        $product->update();

        return back();
    }

    public function editproduct($id) {
        $product= Product::find($id);

        $data =  [];
        $data['category'] = Category::where('category_name', "!=", $product->product_category)->get();
        return view('admin.editproduct', $data)->with('product', $product);
    }

    public function updateproduct($id, Request $request) {
        $product = Product::find($id);
        $product->product_name = $request->input('product_name');
        $product->product_price = $request->input('product_price');
        $product->product_category = $request->input("product_category");

        if($request->file('product_image')){
            $this->validate($request , [
                'product_image'=> 'image|nullable|max:1999'
            ]);

            Storage::delete("public/product_images/". $product->product_image);

            $fileNameWithExt = $request->file('product_image')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $ext = $request->file('product_image')->getClientOriginalExtension();
            $fileNameToStore = $fileName."_".time().".".$ext;
            $path = $request->file('product_image')->storeAs("public/product_images", $fileNameToStore);

            $product->product_image = $fileNameToStore;


        }

        $product->update();

        return redirect('/admin/products')->with('status', 'Votre produit a été modifier avec succès.');
    }
}
