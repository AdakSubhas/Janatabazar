<?php

namespace App\Http\Controllers\ApiController\CustomerAPI;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Http\Request;
use Carbon\Carbon;



class LoginControllerAPI extends Controller
{
    public function CustomerRegistration(Request $req){
        try {
            $req->validate([
                    'name'          => 'required',
                    'password'      => 'required',
                    'ConfPassword'  => 'required',
                    'email'         => 'required|unique:customers,email',
                    'mobile'        => 'required|unique:customers,mobile|digits:10',
                    'address'       => 'required',
                    'city'          => 'required',
                    'state'         => 'required',
                    'zipcode'       => 'required',
                ],
                [
                    'email.unique'      => 'This email id is already registered with us. Please use a different email id.',
                    'mobile.unique'     => 'This mobile number is already registered with us. Please use a different mobile number.',
                    'mobile.digits'     => 'The mobile number Must be 10 digits.'
                ]);
            
            $name       = $req->name;
            $username   = $req->email;
            $password   = $req->password;
            $CPassword  = $req->ConfPassword;
            $email      = $req->email;
            $mobile     = $req->mobile;
            $address    = $req->address;
            $city       = $req->city;
            $state      = $req->state;
            $zipcode    = $req->zipcode;
            $created_at = Carbon::now();

            if ($photo = $req->file('photo')){
                $photo = date('Y-m-d-H-i-s').rand(1000,9999).$req->file('photo')->getClientOriginalName();
                $ext = $req->file('photo')->getClientOriginalExtension();
                $req->file('photo')->storeAs('public/Customer', $photo);
            }
            else{
                $photo='';
            }

            if($password === $CPassword){
                $insert     = DB::table('customers')->insertGetId([
                                'name'      => $name,
                                'username'  => $username,
                                'password'  => Hash::make($password),
                                'photo'     => $photo,
                                'email'     => $email,
                                'mobile'    => $mobile,
                                'address'   => $address,
                                'city'      => $city,
                                'state'     => $state,
                                'zipcode'   => $zipcode,
                                'status'    => 1,
                                'created_at'=> $created_at
                            ]);
                if($insert){
                    $data   = DB::table('customers')
                            ->where('id',$insert)
                            ->select(
                                'id as user_id',
                                'username as UserName',
                                DB::raw("CONCAT('" . env('APP_URL') . "storage/Customer/', photo) as ProfilePhoto"),
                                'name as Name',
                                'mobile as Mobile',
                                'email as Email',
                                'address as Address',
                                'city as City',
                                'zipcode as ZipCode',
                                'status as Status'
                            )
                            ->get();
                    
                    $output['response'] = 'success';
                    $output['message']  = 'User registered successfully';
                    $output['data']     = $data;
                    $output['error']    = null;
                }
                else{
                    $output['response'] = 'failed';
                    $output['message']  = 'User registered failed';
                    $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'failed';
                $output['message']  = 'Password not match';
                $output['error']    = null;
            }
        }
        catch(\Exception $e){
            // Log the exception
            Log::error('Registration request processing error: ' . $e->getMessage());
            
            $output = [
                'response' => 'failed',
                'message1' => 'An error occurred while processing the registration request',
                'message'  => $e->getMessage(),
                'error'    => $e->getMessage()
            ];
        }
        return response()->json($output);
    }
    public function CustomerLogin(Request $req){
        try {
            $req->validate([
                'UserName'  => 'required',
                'Password'  => 'required',
            ]);
            $username   = $req->UserName;
            $pass       = $req->Password;

            $find   = DB::table('customers')
                    ->where('username',$username)
                    ->first();
            if($find){
                if(Hash::check($pass,$find->password)){
                    $data   = [
                                'Id'        => $find->id,
                                'Name'      => $find->name,
                                'UserName'  => $find->username,
                                'Photo'     => env('APP_URL').'storage/Customer/'.$find->photo,
                                'Email'     => $find->email,
                                'Mobile'    => $find->mobile,
                                'Address'   => $find->address,
                                'City'      => $find->city,
                                'State'     => $find->state,
                                'zipcode'   => $find->zipcode,
                                'Status'    => $find->status,
                            ];
                    $output['response'] = 'success';
                    $output['message'] = 'User Login Successful';
                    $output['data'] = $data;
                    $output['error'] = null;
                }
                else{
                    $output['response'] = 'failed';
                    $output['message']  = 'Password not match';
                    $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'failed';
                $output['message']  = 'Invlide Username';
                $output['error']    = null;
            }
        }
        catch(\Exception $e){
            // Log the exception
            Log::error('Registration request processing error: ' . $e->getMessage());
            
            $output = [
                'response' => 'failed',
                'message1' => 'An error occurred while processing the registration request',
                'message'  => $e->getMessage(),
                'error'    => $e->getMessage()
            ];
        }
        return response()->json($output);
    }
    public function ProductList()
    {
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
        } catch (\Exception $e) {
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