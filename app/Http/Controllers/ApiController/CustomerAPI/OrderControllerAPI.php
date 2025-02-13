<?php

namespace App\Http\Controllers\ApiController\CustomerAPI;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Http\Request;
use Carbon\Carbon;



class OrderControllerAPI extends Controller
{
    public function ShopAndDeliveryActive(Request $req){
        try{
            $req->validate([
                'zipcode' => 'required',
            ]);

            $zipcode    = $req->input('zipcode');

                $shopCheck  = DB::table('stores')
                            ->whereRaw("FIND_IN_SET(?, zip_code)", [$zipcode])
                            ->first();
            if($shopCheck){
                if($shopCheck->active_status ==  1){
                   $devCheck    = DB::table('delivery_partners')
                                ->where([
                                    'zipcode'       => $zipcode,
                                    'active_status'  => 1
                                ])
                                ->get();
                                // ->inRandomOrder()
                    if($devCheck->isNotEmpty()){
                        $output['response'] = 'success';
                        $output['message']  = 'All are avlilable right now';
                        $output['data']     = NULL;
                        $output['error']    = null;
                    }
                    else{
                        $output['response'] = 'failed';
                        $output['message']  = 'No delivery partners available right now';
                        $output['data']     = NULL;
                        $output['error']    = null;
                    }
                }
                else{
                    $output['response'] = 'failed';
                    $output['message']  = 'Shop is close now';
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'failed';
                $output['message']  = 'No shop avilable in there';
                $output['data']     = NULL;
                $output['error']    = null;
            }
        }
        catch (\Exception $e) {
            // Log the exception
            Log::error('ProductList error: ' . $e->getMessage());

            $output = [
                'response' => 'failed',
                'message'  => 'An error occurred while Check shop and delivery active status',
                'error'    => $e->getMessage(),
            ];
        }
        return response()->json($output);
    }
    private function generateOrderNumber(){
        // Generate 2 random letters
        // $letters = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2));
        $letters = 'JB';
        
        $numbers = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);

        return $letters . $numbers;
    }
    private function generateUniqueOrderNumber(){
        do {
            $orderNumber = $this->generateOrderNumber();
        } while (DB::table('orders')->where('order_id', $orderNumber)->exists());

        return $orderNumber;
    }
    public function AddOrder(Request $req){
        try{
            $req->validate([
                'customer_id'   => 'required',
                'price'         => 'required',
            ]);
            $insert_data    = [];
            $update_data    = [];
            $otp            = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $customer_id    = $req->input('customer_id');
            $price          = $req->input('price');
            
            $check          = DB::table('add_to_cart')
                            ->where([
                                'customer_id'   => $customer_id,
                                'status'        => 0,
                            ])
                            ->count();
            if($check>0){
                $c_address      = DB::table('customer_address')
                                ->where([
                                    'customer_id'   => $customer_id,
                                    'status'        => 1
                                ])->first();
                if($c_address){
                    $shopCheck  = DB::table('stores')
                                ->whereRaw("FIND_IN_SET(?, zip_code)", [$c_address->zipcode])
                                ->where('active_status',1)
                                ->first();
                    $devCheck   = DB::table('delivery_partners')
                                ->where([
                                    'zipcode'       => $c_address->zipcode,
                                    'active_status'  => 1
                                ])
                                ->inRandomOrder()
                                ->first();
                    $orderNumber    = $this->generateUniqueOrderNumber();
                    $insert_data    = [
                                        'order_id'      => $orderNumber,
                                        'customer_id'   => $customer_id,
                                        'address_id'    => $c_address->id,
                                        'store_id'      => $shopCheck->id,
                                        'delivery_id'   => $devCheck->id,
                                        'status'        => 0,
                                        'otp1'          => $otp,
                                        'price'         => $price,
                                        'total'         => $price,
                                        'created_at'    => now()
                                    ];
                    $order_insert   = DB::table('orders')->insertGetId($insert_data);
                    if($order_insert){
                        $update_data      = [
                                            'order_id'  => $orderNumber,
                                            'status'    => 2,
                                            'updated_at'=> now()
                                        ];
                        $update_cart    = DB::table('add_to_cart')
                                        ->where([
                                            'customer_id'   => $customer_id,
                                            'status'        => 0
                                        ])
                                        ->update($update_data);
                        if($update_cart){
                            $output['response'] = 'success';
                            $output['message']  = 'Order placed successfully';
                            $output['data']     = NULL;
                            $output['error']    = null;
                        }
                        else{
                            DB::table('orders')->where('id',$order_insert)->delete();

                            $output['response'] = 'failed';
                            $output['message']  = 'Something went wrong please try again';
                            $output['data']     = NULL;
                            $output['error']    = null;
                        }
                    }
                    else{
                        $output['response'] = 'failed';
                        $output['message']  = 'Something went wrong please try again...';
                        $output['data']     = NULL;
                        $output['error']    = null;
                    }
                }
                else{
                    $output['response'] = 'failed';
                    $output['message']  = 'You do not select any address';
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'failed';
                $output['message']  = 'No item in cart';
                $output['data']     = NULL;
                $output['error']    = null;
            }
        }
        catch (\Exception $e) {
            // Log the exception
            Log::error('Add Order error: ' . $e->getMessage());

            $output = [
                'response' => 'failed',
                'message'  => 'An error occurred while add order data by customer',
                'error'    => $e->getMessage(),
            ];
        }
        return response()->json($output);
    }
    public function OrderList(Request $req){
        try{
            $req->validate([
                'customer_id' => 'required',
            ]);
            $CId    = $req->input('customer_id');

            $check  = DB::table('orders')
                    ->where([
                        ['customer_id', '=', $CId],
                        ['status', '!=', 1],
                        ['deleted_by','=', NULL]
                    ])
                    ->select([
                        // 'id',
                        'order_id',
                        'customer_id',
                        'address_id',
                        'total',
                        'status',
                        'otp2'
                    ])
                    ->get();
            $output['response'] = 'success';
            $output['message']  = 'Order list get successfully';
            $output['data']     = $check;
            $output['error']    = null;
        }
        catch (\Exception $e) {
            // Log the exception
            Log::error('ProductList error: ' . $e->getMessage());

            $output = [
                'response' => 'failed',
                'message'  => 'An error occurred while fetch order data by customer',
                'error'    => $e->getMessage(),
            ];
        }
        return response()->json($output);

    }
    public function OrderItemList(Request $req){
        try{
            $req->validate([
                'customer_id'   => 'required',
                'order_id'      => 'required',
                'address_id'    => 'required',
            ]);
            $CId    = $req->input('customer_id');
            $OId    = $req->input('order_id');
            $AId    = $req->input('address_id');

            $check  = DB::table('orders')
                    ->where([
                        ['customer_id', '=', $CId],
                        ['status', '!=', 1],
                        ['order_id','=',$OId],
                        ['address_id','=',$AId],
                    ])
                    ->first();
            $address    = DB::table('customer_address')
                        ->where('id',$AId)
                        ->where('deleted_at',NULL)
                        ->select(
                            'customer_id',
                            'contact_name as customer_name',
                            'address',
                            'contact_numbar as ContactNumber',
                            'city',
                            'zipcode'
                        )
                        ->get();
            $delivery_partner   = DB::table('delivery_partners')
                                ->where('id',$check->delivery_id)
                                ->first();
            if($check){
                $cart   = DB::table('add_to_cart as ac')
                        ->join('orders as od','od.order_id','ac.order_id')
                        ->join('daily_price_list as dpl', 'dpl.id', '=', 'ac.daily_price_id')
                        ->join('products as pd','pd.id','ac.product_id')
                        ->select(
                            'ac.id',
                            'pd.item',
                            'pd.photo',
                            'dpl.price',
                            'od.discount',
                            'od.total',
                            'ac.quantity',
                            'ac.product_id',
                            'ac.customer_id'
                        )
                        ->where([
                            'ac.order_id'       => $check->order_id,
                            'ac.customer_id'    => $CId,
                            'ac.status'         => 2,
                        ])
                        ->get();
                foreach($cart as $val){
                    $photo  = $val->photo
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
                $data['OrderStatus']            = $check->status;
                $data['DeliveryPartnerName']    = $delivery_partner->name;
                $data['DeliveryPartnerMobile']  = $delivery_partner->mobile;
                $data['ProductPrice']           = $check->price;
                $data['Discount']               = $check->discount;
                $data['TotalPrice']             = ($check->price - $check->discount);
                $data['Customer Address']       = $address;
                $output['response']             = 'success';
                $output['message']              = 'Order Items List Data';
                $output['data']                 = $data;
                $output['error']                = null;
            }
        }
        catch (\Exception $e) {
            // Log the exception
            Log::error('ProductList error: ' . $e->getMessage());

            $output = [
                'response' => 'failed',
                'message'  => 'An error occurred while fetch order data by customer',
                'error'    => $e->getMessage(),
            ];
        }
        return response()->json($output);
    }
    public function OrderHistory(Request $req){
        try{
            $req->validate([
                'CustomerId'   => 'required',
            ]);
            $CId = $req->input('CustomerId');

            $OrderData  = DB::table('orders')
                        ->where([
                            'customer_id'    => $CId,
                            'status'          => 1,
                        ])
                        ->select(
                            'order_id as OrderId',
                            'customer_id as CustomerId',
                            'address_id as CustomerAddressId',
                            'price as Price',
                            'total as TotalPrice',
                            'created_at as OrderDate'
                        )
                        ->get();
            if($OrderData){
                $output['response'] = 'success';
                $output['message']  = 'Order history list get successfully';
                $output['data']     = $OrderData;
                $output['error']    = null;
            }
            else{
                $output['response'] = 'failed';
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
                'message'  => 'An error occurred while fetch order data by customer',
                'error'    => $e->getMessage(),
            ];
        }
        return response()->json($output);
    }
    public function OrderHistoryItems(Request $req){
        try{
            $req->validate([
                'OrderId'           => 'required',
                'CustomerAddressId' => 'required',
            ]);
            $OId    = $req->input('OrderId');
            $CAId   = $req->input('CustomerAddressId');
            $data   = [];
            $TotalPrice = 0;
            $pincode    = DB::table('customer_address as ca')
                        ->join('pincodes as pd','pd.pincode','ca.zipcode')
                        ->select(
                            'pd.state_id as StateId',
                            'pd.district_id as DistrictId',
                            'pd.id as PincodeId',
                            'ca.customer_id as CustomerId',
                            'ca.address as CAddress',
                            'ca.city as CCity',
                            'ca.zipcode as Zipcode'
                        )
                        ->where('ca.id',$CAId)
                        ->first();
            $OrderData  = DB::table('orders')
                        ->where('order_id',$OId)
                        ->value('created_at');
            $OrderItems = DB::table('add_to_cart as atc')
                        ->join('products as pro','pro.id','atc.product_id')
                        ->select(
                            'pro.id as ProductId',
                            'pro.item as ProductName',
                            'pro.photo as ProductPhoto',
                            'atc.quantity as ProductQuantity',
                            'pro.units as ProductUnits',
                            'pro.serial_number  as ProductSerialNumber'
                        )
                        ->where([
                            ['atc.order_id', '=', $OId],
                            ['atc.deleted_at', '=', NULL]
                        ])
                        ->get();
            $formattedDate = Carbon::parse($OrderData)->format('Y-m-d');
            if($formattedDate === Carbon::now()->format('Y-m-d')){
                foreach($OrderItems as $val){
                    $DalyPrice  = DB::table('daily_price_list')
                                ->where(
                                    ['pin_id','=',$pincode->PincodeId],
                                    ['product_id','=',$val->product_id]
                                )
                                ->value('price');
                    $photo  = $val->ProductPhoto
                            ? env('APP_URL') . 'storage/Product/' . $val->ProductPhoto
                            : env('APP_URL') . 'storage/Product/default.png';
                    $ProductQun     = (float) $val->ProductQuantity;
                    $DalyPrice      = (float) $DalyPrice;
                    $data[] = [
                                'ProductId'         => $val->ProductId,
                                'ProductName'       => $val->ProductName,
                                'ProductPhoto'      => $photo,
                                'ProductQuantity'   => $ProductQun,
                                'ProductUnits'      => $val->ProductUnits,
                                'ProductUnitPrice'  => $DalyPrice,
                                'ProductTotalPrice' => ($ProductQun * $DalyPrice)
                            ];
                    $TotalPrice += ($ProductQun * $DalyPrice);
                }
            }
            else{
                foreach($OrderItems as $val){
                    $DalyPrice  = DB::table('product_history')
                                ->where([
                                    ['serial_number','=',$val->ProductSerialNumber],
                                    ['zipcode','=',$pincode->Zipcode],
                                    ['price_date','=',$formattedDate]
                                ])
                                ->value('price');
                    $photo  = $val->ProductPhoto
                            ? env('APP_URL') . 'storage/Product/' . $val->ProductPhoto
                            : env('APP_URL') . 'storage/Product/default.png';
                    $ProductQun     = (float) $val->ProductQuantity;
                    $DalyPrice      = (float) $DalyPrice;
                    $data[] = [
                                'ProductId'         => $val->ProductId,
                                'ProductName'       => $val->ProductName,
                                'ProductPhoto'      => $photo,
                                'ProductQuantity'   => $ProductQun,
                                'ProductUnits'      => $val->ProductUnits,
                                'ProductUnitPrice'  => $DalyPrice,
                                'ProductTotalPrice' => ($ProductQun * $DalyPrice)
                            ];
                    $TotalPrice += ($ProductQun * $DalyPrice);
                }
            }
            $data['TotalPrice']         = $TotalPrice;
            $data['OrderId']            = $OId;
            $data['CustomerId']         = $pincode->CustomerId;
            $data['CustomerStateId']    = $pincode->StateId;
            $data['CustomerDistrictId'] = $pincode->DistrictId;
            $data['CustomerAddress']    = $pincode->CAddress;
            $data['CustomerZipcode']    = $pincode->Zipcode;
        
            $output['response'] = 'success';
            $output['message']  = 'Order history list item data get successfully';
            $output['data']     = $data;
            $output['error']    = null;
        
        }
        catch (\Exception $e) {
            // Log the exception
            Log::error('ProductList error: ' . $e->getMessage());

            $output = [
                'response' => 'failed',
                'message'  => 'An error occurred while fetch order data by customer',
                'error'    => $e->getMessage(),
            ];
        }
        return response()->json($output);
    }
    public function ReorderItems(Request $req){
        try{
            $req->validate([
                'OrderId'       => 'required',
                'CustomerId'    => 'required',
                'Zipcode'       => 'required',
            ]);
            $OId    = $req->input('OrderId');
            $CId    = $req->input('CustomerId');
            $Zipcode= $req->input('Zipcode');
            $count  = 0;
            $tprice = 0;
            $data   = [];
            $FetchData  = DB::table('add_to_cart as atc')
                        ->join('customer_address as ca','ca.customer_id','atc.customer_id')
                        ->join('pincodes as pin','pin.pincode','ca.zipcode')
                        ->join('daily_price_list as dpl', function ($join) {
                            $join->on('dpl.pin_id', '=', 'pin.id')
                                 ->on('dpl.product_id', '=', 'atc.product_id');
                        })
                        ->join('products as pro','pro.id','dpl.product_id')
                        ->select(
                            'ca.customer_id as CustomerId',
                            'pin.id as PincodeId',
                            'dpl.id as DPLId',
                            'dpl.product_id as ProdactId',
                            'dpl.price',
                            'atc.quantity as Quntity',
                            'pro.photo',
                            'pro.item'
                        )
                        ->where([
                            ['ca.zipcode','=',$Zipcode],
                            ['atc.customer_id','=',$CId],
                            ['atc.order_id','=',$OId],
                            ['atc.deleted_at','=',NULL]
                        ])
                        ->get();
            if ($FetchData->isNotEmpty()) {
                foreach($FetchData as $val){
                    $ExitData   = DB::table('add_to_cart')
                                ->where([
                                    ['order_id','=',NULL],
                                    ['daily_price_id','=',$val->DPLId],
                                    ['product_id','=',$val->ProdactId],
                                    ['customer_id','=',$val->CustomerId],
                                    ['quantity','=',$val->Quntity],
                                    ['status','=',0],
                                ])
                                ->count();
                    if($ExitData>0){
                        $count++;
                        continue;
                    }
                    else{
                        $insert =DB::table('add_to_cart')
                                ->insertGetId([
                                    'daily_price_id'    => $val->DPLId,
                                    'product_id'        => $val->ProdactId,
                                    'customer_id'       => $val->CustomerId,
                                    'quantity'          => $val->Quntity,
                                    'status'            => 0,
                                    'created_at'        => now()
                                ]);
                        $photo = $val->photo
                                ? env('APP_URL') . 'storage/Product/' . $val->photo
                                : env('APP_URL') . 'storage/Product/default.jpg';
                        $data[] = [
                                    'ID'            => $insert,
                                    'UserId'        => $val->CustomerId,
                                    'ProductID'     => $val->ProdactId,
                                    'ProductName'   => $val->item,
                                    'ProductImage'  => $photo,
                                    'ProductPrice'  => $val->price,
                                    'Quantity'      => $val->Quntity,
                                ];
                        $tprice += ($val->price*$val->Quntity);
                    }
                }
                if($count >0){
                    $output['response'] = 'failed';
                    $output['message']  = 'Items allready have in cart list';
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
                else{
                    $output['response']     = 'success';
                    $output['message']      = 'Items added in cart list successfully';
                    $output['data']         = $data;
                    $output['TotalPrice']   = $tprice;
                    $output['error']        = null;
                }
            }
            else{
                $output['response'] = 'failed';
                $output['message']  = 'No items found for reorder';
                $output['data']     = NULL;
                $output['error']    = null;
            }
        }
        catch (\Exception $e) {
            // Log the exception
            Log::error('Reorder error: ' . $e->getMessage());

            $output = [
                'response' => 'failed',
                'message'  => 'An error occurred while reorder items by customer',
                'error'    => $e->getMessage(),
            ];
        }
        return response()->json($output);
    }
    public function OrderStatusUpdate(Request $req){
        $req->validate([
            'OrderID'       => 'required',
            'CustomerID'    => 'required',
        ]);
        $OId    = $req->input('OrderId');
        $CId    = $req->input('CustomerId');

        $check  = DB::table('orders')
                ->where([
                    ['order_id', '=', $OId],
                    ['customer_id', '=', $CId],
                    ['status','!=',1],
                    ['status','!=',2]
                ])
                ->count();
        if($check>0){
            $cancel = DB::table('orders')
                    ->where('order_id',$OId)
                    ->update([
                        'delete_by_table'   => 'customer',
                        'deleted_by'        => $CId,
                        'deleted_at'        => now()
                    ]);
            if($cancel){
                $cancel2    = DB::table('add_to_cart')
                            ->where('order_id',$OId)
                            ->update([
                                'delete_by_table'   => 'customer',
                                'deleted_by'        => $CId,
                                'deleted_at'        => now()
                            ]);
                if($cancel2){
                    $output['response'] = 'success';
                    $output['message']  = "Order cancel successfully";
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
                else{
                    $output['response'] = 'failed';
                    $output['message']  = "Order items can not cancel";
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'failed';
                $output['message']  = "Order can not cancel";
                $output['data']     = NULL;
                $output['error']    = null;
            }
        }
        else{
            $output['response'] = 'success';
            $output['message']  = "Order can not cancel at that stage";
            $output['data']     = NULL;
            $output['error']    = null;
        }
    }
}