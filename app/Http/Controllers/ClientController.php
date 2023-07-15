<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Cart;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    public function addtocart($id) {
        $product = Product::find($id);

        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        // dd($oldCart);
        $cart = new Cart($oldCart);
        $cart->add($product);
        Session::put('cart', $cart);
        Session::put('topcart', $cart->items);

        // dd(Session::get('cart'));
        return back();
    }

    public function updateqty($id, Request $request) {
        // $product = Product::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->updateQty($id, $request->qty);
        Session::put('cart', $cart);
        Session::put('topcart', $cart->items);

        return back();
    }

    public function removeitem($id) {
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        Session::put('cart', $cart);
        Session::put('topcart', $cart->items);

        return back();
    }

    public function createaccount(Request $request) {
        $this->validate($request, [
            'email' => 'email|required|unique:clients',
            'password' => 'required|min:4'
        ]);

        $client = new Client();
        $client->email = $request->input('email');
        $client->password = bcrypt($request->input('password')) ;

        $client->save();

        return redirect('/')->with('status', 'Votre compte a été créé avec succès');
    }

    public function accessaccount(Request $request) {
        $this->validate($request, [
            'email' => 'email|required'
        ]);

        $client = Client::where('email', $request->input('email'))->first();
        if($client) {
            if(Hash::check($request->input("password"), $client->password)) {
                Session::put('client', $client);
                return redirect('/shop');
            }
            return back()->with('error', 'Votre email ou mot de passe est incorect');
        }

        return back()->with('error', "Votre email n'excite pas");
    }

    public function logout() {
        Session::forget('client');
        return redirect('/');
    }

    public function checkout() {
        if(Session::has('client')) {
            return view('client.checkout');
        } else {
            return redirect("/signin");
        }
    }

    public function pages($page) {
        $data =  [];
        switch ($page) {
            case 'home':
                $data['sliders'] = Slider::where('status', 1)->get();
                $data['products'] = Product::where('status', 1)->get();
                break;
            case 'shop':
                $data['products'] = Product::where('status', 1)->get();
                break;
            // case 'addproduct':
            //     $data['category'] = Category::get();
            //     break;
            // case 'products':
            //     $data['products'] = Product::get();
            //     break;
            default:
                null;
                break;
        }
        return view('client.' . $page, $data);
    }

}
