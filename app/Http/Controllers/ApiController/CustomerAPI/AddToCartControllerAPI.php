<?php

namespace App\Http\Controllers\ApiController\CustomerAPI;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Http\Request;
use Carbon\Carbon;



class AddToCartControllerAPI extends Controller
{
    public function AddToCartList(Request $req){
        try{
            $req->validate([
                'UserId' => 'required',
                ]);
            $id     = $req->UserId;
            $data   = [];
            $cart   = DB::table('add_to_cart as ac')
                    ->join('products as pd','pd.id','ac.product_id')
                    ->select(
                        'ac.id',
                        'pd.item',
                        'pd.photo',
                        'pd.price',
                        'ac.quantity',
                        'ac.product_id',
                        'ac.customer_id'
                    )
                    ->where([
                        'ac.customer_id'   => $id,
                        'ac.status'        => 0,
                    ])
                    ->get();
            foreach($cart as $val){
                $photo = $val->photo
                        ? env('APP_URL') . 'storage/Product/' . $val->photo
                        : env('APP_URL') . 'storage/Product/default.jpg';
                $data[] = [
                    'ID'            => $val->id,
                    'UserId'        => $val->customer_id,
                    'ProductID'     => $val->product_id,
                    'ProductName'   => $val->item,
                    'ProductImage'  => $photo,
                    'ProductPrice'  => $val->price,
                    'Quantity'      => $val->quantity,
                ];
            }
            $output['response'] = 'success';
            $output['message'] = 'Add to Cart data fetched successfully';
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