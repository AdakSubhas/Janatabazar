<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Hash;

class ProductCategoryController extends Controller
{
    public function category()
    {
        $data = ProductCategory::all();
        return view('categories.list', compact('data'));
    }
}
