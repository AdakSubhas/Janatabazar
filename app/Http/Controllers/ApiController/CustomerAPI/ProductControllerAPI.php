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
                $photo = $val->icon
                        ? env('APP_URL') . 'storage/CategoriesIcon/' . $product->photo
                        : env('APP_URL') . 'storage/CategoriesIcon/default.png';
                $data[] = [
                            'Id'    => $val->id,
                            'Name'  => $val->category_name,
                            'Icon'  => $photo,
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
    public function ProductList(Request $req){
        try {
            $req->validate([
                // 'pincode'   => 'required',
            ]);
            if($req->input('pincode') ==  NULL){
                $pin    = '721507';
            }
            else{
                $pin    = $req->input('pincode');
            }
            $output = [];
            $data   = [];
            $PinCheck   = DB::table('pincodes')
                        ->where([
                            'pincode'   => $pin,
                            'status'    => 1,
                        ])
                        ->value('id');

            $products   = DB::table('daily_price_list as dpl')
                        ->join('product_categories as pc','pc.id','dpl.category_id')
                        ->join('products as prod','prod.id','dpl.product_id')
                        ->where('dpl.pin_id',$PinCheck)
                        ->where('dpl.status',1)
                        ->where('pc.status',1)
                        ->where('prod.status',1)
                        ->select(
                            'pc.id as pc_id',
                            'pc.category_name',
                            'prod.id as prod_id',
                            'prod.item',
                            'prod.photo',
                            'dpl.id as dpl_id',
                            'dpl.price',
                            'prod.units',
                            'prod.min_order',
                            'prod.max_order',
                            'prod.description',
                            )
                            ->get();

            foreach ($products as $product) {
                $photo  = $product->photo
                        ? env('APP_URL') . 'storage/Product/' . $product->photo
                        : env('APP_URL') . 'storage/Product/default.png';
                        
                $data[] = [
                    'ProductCategoryId'   => $product->pc_id,
                    'ProductCategoryName' => $product->category_name,
                    'ProductId'           => $product->prod_id,
                    'ProductName'         => $product->item,
                    'ProductImage'        => $photo,
                    'DailyPriceId'        => $product->dpl_id,
                    'ProductPrice'        => $product->price,
                    'ProductUnits'        => $product->units,
                    'ProductMinOrder'     => $product->min_order,
                    'ProductMaxOrder'     => $product->max_order,
                    'ProductDescription'  => $product->description,
                ];
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