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
    public function AddToCart(Request $req){
        try{
            $req->validate([
                'ProductId'     => 'required',
                'Quantity'      => 'required',
                'CustomerId'    => 'required',
            ]);
            $id     = $req->input('ProductId');
            $cId    = $req->input('CustomerId');
            $qun    = $req->input('Quantity');
            $data   = [];
            $check  = DB::table('add_to_cart')
                    ->where([
                        'customer_id'   => $cId,
                        'product_id'    => $id,
                        'status'        => 0,
                    ])
                    ->count();
            if($check <1){
                $insert_data    = [
                                    'product_id'    => $id,
                                    'customer_id'   => $cId,
                                    'quantity'      => $qun,
                                    'status'        => 0,
                                    'created_at'    => now()
                                ];

                $insert = DB::table('add_to_cart')
                        ->insertGetId($insert_data);
                if($insert){
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
                    $output['message']  = 'Item add in add to cart';
                    $output['data']     = $data;
                    $output['error']    = null;
                }
                else{
                    $output['response'] = 'failed';
                    $output['message']  = 'Item add failed';
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'failed';
                $output['message']  = 'Item allready in cart';
                $output['data']     = NULL;
                $output['error']    = null;
            }
        }
        catch (\Exception $e) {
            // Log the exception
            Log::error('ProductList error: ' . $e->getMessage());

            $output = [
                'response' => 'failed',
                'message'  => 'An error occurred while update quantity',
                'error'    => $e->getMessage(),
            ];
        }
        return response()->json($output);
    }
    public function AddToCartListItemQuantityUpdate(Request $req){
        try {
            $req->validate([
                'CartId'    => 'required',
                'Quantity'  => 'required',
            ]);
            $id     = $req->input('CartId');
            $qun    = $req->input('Quantity');
            $count  = DB::table('add_to_cart')
                    ->where([
                        'id'        => $id,
                        'deleted_by'=> NULL
                    ])
                    ->count();
            if($count==1){
                $update = DB::table('add_to_cart')
                        ->where('id',$id)
                        ->update([
                            'quantity'  => $qun,
                            'updated_at'=> now(),
                        ]);
                if($update){
                    $output['response'] = 'success';
                    $output['message']  = 'Quantity update successfull';
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
                else{
                    $output['response'] = 'failed';
                    $output['message']  = 'Quantity update failed. Please try again';
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'success';
                $output['message']  = 'No data found';
                $output['data']     = NULL;
                $output['error']    = null;
            }
        }
        catch (\Exception $e) {
            // Log the exception
            Log::error('ProductList error: ' . $e->getMessage());

            $output = [
                'response' => 'failed',
                'message'  => 'An error occurred while update quantity',
                'error'    => $e->getMessage(),
            ];
        }
        return response()->json($output);
    }
    public function AddToCartListItemDelete(Request $req){
        try {
            $req->validate([
                'CartId'    => 'required'
            ]);
            $id     = $req->input('CartId');
            $count  = DB::table('add_to_cart')
                    ->where([
                        'id'        => $id,
                        'deleted_by'=> NULL
                    ])
                    ->count();
            if($count == 1){
                $delete = DB::table('add_to_cart')
                        ->where('id',$id)
                        ->delete();
                if($delete){
                    $output['response'] = 'success';
                    $output['message']  = 'Item remove successfull';
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
                else{
                    $output['response'] = 'failed';
                    $output['message']  = 'Item remove failed. Please try again';
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'success';
                $output['message']  = 'No data found';
                $output['data']     = NULL;
                $output['error']    = null;
            }
        }
        catch (\Exception $e) {
            // Log the exception
            Log::error('ProductList error: ' . $e->getMessage());

            $output = [
                'response' => 'failed',
                'message'  => 'An error occurred while delete add to cart data by customer',
                'error'    => $e->getMessage(),
            ];
        }
        return response()->json($output);
    }
}