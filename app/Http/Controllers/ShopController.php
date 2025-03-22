<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index() {

        $categories = Category::orderBy('name', 'ASC')
            ->with('sub_category')
            ->where('status', 1)->get();
        $data['categories'] = $categories;

        $products = Product::orderBy('name', 'DESC')
            ->where('status', 1)->get();
        $data['products'] = $products;

        return view('front.shop', $data);
    }
}
