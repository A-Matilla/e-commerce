<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;

class AdminController extends Controller
{

    public function pages($page) {
        $data =  [];
        switch ($page) {
            case 'sliders':
                $data['sliders'] = Slider::get();
                break;
            case 'category':
                $data['category'] = Category::get();
                break;
            case 'addproduct':
                $data['category'] = Category::get();
                break;
            case 'products':
                $data['products'] = Product::get();
                break;
            default:
                null;
                break;
        }
        return view('admin.' . $page, $data);
    }

}
