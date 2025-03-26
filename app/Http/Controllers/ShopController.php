<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null) {

        $categorySelected = '';
        $subCategorySelected = '';
        $levelsArray = $request->filled('level') ? explode(',', $request->get('level')) : [];




        $categories = Category::orderBy('name', 'ASC')
            ->with('sub_category')
            ->where('status', 1)->get();
        $data['categories'] = $categories;

        $products = Product::where('status', 1);

        // Apply Filters
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            $products = $products->where('category_id', $category->id);
            $categorySelected = $category->id;
        }

        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            $products = $products->where('sub_category_id', $subCategory->id);
            $subCategorySelected = $subCategory->id;
        }

        if (!empty($levelsArray)) {
            $products = $products->whereIn('level', $levelsArray);
        }

        if ($request->get('price_max') != '' && $request->get('price_min') != '') {
            if ($request->get('price_max') == 500000) {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), 1000000]);
            } else {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
            }

        }


        if ($request->get('sort')) {
            if ($request->get('sort') == 'latest') {
                $products = $products->orderBy('name', 'DESC');
            } else if ($request->get('sort') == 'price_desc') {
                $products = $products->orderBy('price', 'DESC');
            } else if ($request->get('sort') == 'price_asc') {
                $products = $products->orderBy('price', 'ASC');
            }
        } else {
            $products = $products->orderBy('name', 'DESC');
        }

        $products = $products->paginate(6);
        $data['products'] = $products;
        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;
        $data['levelsArray'] = $levelsArray;
        $data['priceMax'] = (intval($request->get('price_max')) == 0) ? 100000 : intval($request->get('price_max'));
        $data['priceMin'] = intval($request->get('price_min'));
        $data['sort'] = $request->get('sort');

        return view('front.shop', $data);
    }

    public function product($slug) {
        $product = Product::where('slug', $slug)->with('product_images')->first();

        if ($product == null) {
            abort(404);
        }

        $relatedProducts = [];
        if ($product->related_products != null) {
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->with('product_images')->get();
        }

        $data['product'] = $product;
        $data['relatedProducts'] = $relatedProducts;

        return view('front.product', $data);
    }
}
