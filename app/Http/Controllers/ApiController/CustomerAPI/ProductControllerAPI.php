<?php

namespace App\Http\Controllers\ApiController\CustomerAPI;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Http\Request;
use Carbon\Carbon;



class ProductControllerAPI extends Controller
{
    public function ProductCategories(){
        try{
            $data   = [];
            $fetch  = DB::table('product_categories')
                    ->where('status',1)->get();
            
            foreach($fetch as $val){
                $data[] = [
                            'Id'    => $val->id,
                            'Name'  => $val->category_name,
                        ];
            }
            $output['response'] = 'success';
            $output['message'] = 'Product category data fetched successfully';
            $output['data'] = $data;
            $output['error'] = null;
        }
        catch (\Exception $e) {
            // Log the exception
            Log::error('ProductList error: ' . $e->getMessage());

            $output = [
                'response' => 'failed',
                'message'  => 'An error occurred while fetching product category data',
                'error'    => $e->getMessage(),
            ];
        }

        return response()->json($output);
    }
    public function ProductList(){
        try {
            $output = [];
            $data   = [];

            $PCat   = DB::table('product_categories')
                    ->where('status', 1)
                    ->get();

            foreach ($PCat as $val) {
                $products   = DB::table('products')
                            ->where(['category_id' => $val->id, 'status' => 1])
                            ->get();
                foreach ($products as $product) {
                    $photo = $product->photo
                        ? env('APP_URL') . 'storage/Product/' . $product->photo
                        : env('APP_URL') . 'storage/Product/default.jpg';
                        
                    $data[] = [
                        'ProductCategoryId'   => $val->id,
                        'ProductCategoryName' => $val->category_name,
                        'ProductName'         => $product->item,
                        'ProductImage'        => $photo,
                        'ProductPrice'        => $product->price,
                        'ProductUnits'        => $product->units,
                        'ProductMinOrder'     => $product->min_order,
                        'ProductMaxOrder'     => $product->max_order,
                        'ProductDescription'  => $product->description,
                    ];
                }
            }

            $output['response'] = 'success';
            $output['message'] = 'Product data fetched successfully';
            $output['data'] = $data;
            $output['error'] = null;
        }
        catch (\Exception $e) {
            // Log the exception
            Log::error('ProductList error: ' . $e->getMessage());

            $output = [
                'response' => 'failed',
                'message'  => 'An error occurred while fetching product data',
                'error'    => $e->getMessage(),
            ];
        }

        return response()->json($output);
    }
}