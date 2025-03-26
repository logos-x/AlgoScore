<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Song;
use App\Models\SubCategory;
use App\Models\TempImage;
use http\Env\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::latest('id')->with('product_images');
        if ($request->get('keyword') != "") {
            $products = $products->where('name', 'like', '%' . $request->keyword . '%');
        }
        $products = $products->paginate();
        $data['products'] = $products;
        return view('admin.product.list', $data);
    }

    public function create() {
        $data = [];
        $categories = Category::orderBy('name', 'asc')->get();
        $songs = Song::orderBy('title', 'asc')->get();
        $data['categories'] = $categories;
        $data['songs'] = $songs;
        return view('admin.product.create', $data);
    }

    public function store(Request $request) {
        $rules = [
            'name' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'category' => 'required|numeric',
            'song' => 'required|numeric',
            'is_featured' => 'required|in:1,0',
            'level' => 'nullable|in:Beginner,Intermediate,Advanced',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product = new Product();
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->status = $request->status;
            $product->level = $request->level;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->song_id = $request->song;
            $product->is_featured = $request->is_featured;
            $product->short_descriptions = $request->short_description;
            $product->save();

            // Save Gallery Pictures
            if ($request->image_array) {
                foreach ($request->image_array as $temp_image_id) {

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);


                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    // Generate Product Thumbnails


                    $manage = new ImageManager(new Driver());
                    // Large Image
                    $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                    $destPath = public_path().'/uploads/product/large/'.$imageName;
                    $image = $manage->read($sourcePath);
                    $image->scale(1400);
                    $image->save($destPath);

                    // Small Image
                    $destPath = public_path().'/uploads/product/small/'.$imageName;
                    $image = $manage->read($sourcePath);
                    $image->resize(300, 300);
                    $image->save($destPath);
                }
            }

            $request->session()->flash('success', 'Product added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product added successfully'
            ]);
        } else {
            return response()->json([
               'status' => false,
               'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request) {
        $product = Product::find($id);

        if (empty($product)) {
            return redirect()->route('products.index')->with('error', 'Product not found');
        }

        $productImages = ProductImage::where('product_id', $product->id)->get();

        $subCategories = SubCategory::where('category_id', $product->category_id)->get();

        $data = [];
        $data['product'] = $product;
        $categories = Category::orderBy('name', 'asc')->get();
        $songs = Song::orderBy('title', 'asc')->get();
        $data['categories'] = $categories;
        $data['subCategories'] = $subCategories;
        $data['productImages'] = $productImages;
        $data['songs'] = $songs;
        return view('admin.product.edit', $data);
    }

    public function update($id, Request $request) {
        $product = Product::find($id);

        $rules = [
            'name' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price' => 'required|numeric',
            'category' => 'required|numeric',
            'song' => 'required|numeric',
            'is_featured' => 'required|in:1,0',
            'level' => 'nullable|in:Beginner,Intermediate,Advanced',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->status = $request->status;
            $product->level = $request->level;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->song_id = $request->song;
            $product->is_featured = $request->is_featured;
            $product->short_descriptions = $request->short_description;
            $product->save();

            // Save Gallery Pictures



            $request->session()->flash('success', 'Product updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request) {
        $product = Product::find($id);

        if (empty($product)) {
            $request->session()->flash('error', 'Product not found');

            return response()->json([
               'status' => false,
               'notFound' => true
            ]);
        }

        $productImages = ProductImage::where('product_id', $id)->get();

        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                File::delete(public_path().'/uploads/product/large'.$productImage->image);
                File::delete(public_path().'/uploads/product/small'.$productImage->image);
            }

            ProductImage::where('product_id', $id)->delete();
        }

        $product->delete();

        $request->session()->flash('success', 'Product deleted successfully');

        return response()->json([
           'status' => true,
           'message' => 'Product deleted successfully'
        ]);
    }
}
