<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function getProducts()
    {
        // Example Data (Normally, you would fetch this from a database)
        $products = [
            [
                "name" => "Product A",
                "category" => "Category 1",
                "remaining_stock" => 100,
                "status" => true,
                "images" => ["image1.jpg", "image2.jpg", "image3.jpg"],
            ],
            [
                "name" => "Product B",
                "category" => "Category 2",
                "remaining_stock" => 50,
                "status" => false,
                "images" => ["image4.jpg", "image5.jpg", "image6.jpg"],
            ],
        ];

        // Define the columns (this can also be dynamically generated if needed)
        $columns = [
            ["title" => "PRODUCT NAME", "field" => "name", "visible" => true, "print" => true, "download" => true],
            ["title" => "CATEGORY", "field" => "category", "visible" => true, "print" => true, "download" => true],
            ["title" => "REMAINING STOCK", "field" => "remaining_stock", "visible" => true, "print" => true, "download" => true],
            ["title" => "STATUS", "field" => "status", "visible" => true, "print" => true, "download" => true, "formatter" => "tickCross"],
            ["title" => "IMAGE 1", "field" => "images[0]", "visible" => true, "print" => true, "download" => true],
            ["title" => "IMAGE 2", "field" => "images[1]", "visible" => false, "print" => true, "download" => true],
            ["title" => "IMAGE 3", "field" => "images[2]", "visible" => false, "print" => true, "download" => true],
        ];

        // Combine columns and data into the response
        return response()->json([
            "columns" => $columns,
            "data" => $products,
        ]);
    }
}
