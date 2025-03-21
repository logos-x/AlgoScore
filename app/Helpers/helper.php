<?php

use App\Models\Category;

    function getCategories() {
        return Category::orderBy('name', 'ASC')
            ->where('status', 1)
            ->with('sub_category')
            ->where('show', 'Yes')->get();
    }
?>
