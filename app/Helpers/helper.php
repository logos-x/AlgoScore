<?php

use App\Models\Category;
use App\Models\ProductImage;

    function getCategories() {
        return Category::orderBy('name', 'ASC')
            ->where('status', 1)
            ->with('sub_category')
            ->where('show', 'Yes')->get();
    }

    function getProductImage($productId) {
        return ProductImage::where('product_id', $productId)->first();
    }
?>
