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
                    // 'email'         => 'required|unique:customers,email',
                    'mobile'        => 'required|unique:customers,mobile|digits:10',
                ],
                [
                    // 'email.unique'      => 'This email id is already registered with us. Please use a different email id.',
                    'mobile.unique'     => 'This mobile number is already registered with us. Please use a different mobile number.',
                    'mobile.digits'     => 'The mobile number Must be 10 digits.'
                ]);
            
            $name       = $req->name;
            $username   = $req->email;
            $password   = $req->password;
            $CPassword  = $req->ConfPassword;
            $email      = $req->email;
            $mobile     = $req->mobile;
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
                                'status'    => 1,
                                'created_at'=> $created_at
                            ]);
                if($insert){
                    $check  = DB::table('customer_address')
                            ->where([
                                'customer_id'   => $insert,
                                'status'        => 1,
                            ])
                            ->count();
                    if($check > 0){
                        $data   = DB::table('customers as cu')
                                ->join('customer_address as ca','ca.customer_id','cu.id')
                                ->where('cu.id',$insert)
                                ->where('ca.status',1)
                                ->select(
                                    'cu.id as user_id',
                                    DB::raw("CONCAT('" . env('APP_URL') . "storage/Customer/', cu.photo) as ProfilePhoto"),
                                    'cu.name as Name',
                                    'cu.mobile as Mobile',
                                    'cu.email as Email',
                                    'ca.address as Address',
                                    'ca.city as City',
                                    'ca.zipcode as ZipCode',
                                    'ca.status as Status'
                                )
                                ->get();
                    }
                    else{
                        $data   = DB::table('customers')
                                ->where('id',$insert)
                                ->select(
                                    'id as user_id',
                                    DB::raw("CONCAT('" . env('APP_URL') . "storage/Customer/', photo) as ProfilePhoto"),
                                    'name as Name',
                                    'mobile as Mobile',
                                    'email as Email',
                                    'status as Status'
                                )
                                ->get();
                    }
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
                    ->where('mobile',$username)
                    ->orWhere('email',$username)
                    ->first();
            if($find){
                if($find->status == 1){
                    if(Hash::check($pass,$find->password)){
                        $find1  = DB::table('customer_address')
                                ->where([
                                    'customer_id'   => $find->id,
                                    'status'        => 1,
                                ])
                                ->first();
                        $data   = [
                                    'Id'        => $find->id,
                                    'Name'      => $find->name,
                                    'UserName'  => $find->username,
                                    'Photo'     => env('APP_URL').'storage/Customer/'.$find->photo ?? NULL,
                                    'Email'     => $find->email ?? NULL,
                                    'Mobile'    => $find->mobile,
                                    'Address'   => $find1->address ?? NULL,
                                    'City'      => $find1->city ?? NULL,
                                    'State'     => $find1->state ?? NULL,
                                    'zipcode'   => $find1->zipcode ?? NULL,
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
                        $output['message']  = 'Password not match';
                        $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'failed';
                $output['message']  = 'User account is inactive';
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
    public function CustomerAddressAdd(Request $req){
        try{
            $req->validate([
                'CustomerId'    => 'required',
                'Address'       => 'required',
                'State'         => 'required',
                'City'          => 'required',
                'ZipCode'       => 'required',
            ]);
            $id         = $req->input('CustomerId');
            $address    = $req->input('Address');
            $state      = $req->input('State');
            $city       = $req->input('City');
            $zipcode    = $req->input('ZipCode');
            $status     = 0;

            $check      = DB::table('customer_address')
                        ->where([
                            'customer_id'   => $id,
                            'deleted_at'    => NULL,
                        ])
                        ->count();
            if($check<5){
                $insert_data    = [
                                'customer_id'   => $id,
                                'address'       => $address,
                                'city'          => $state,
                                'state'         => $city,
                                'zipcode'       => $zipcode,
                                'status'        => $status,
                                'created_at'    => now()
                            ];

                $insert = DB::table('customer_address')
                        ->insertGetId($insert_data);
                if($insert){
                    $output['response'] = 'success';
                    $output['message']  = 'Address add successfull';
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
                else{
                    $output['response'] = 'failed';
                    $output['message']  = 'Address add failed. Please try again';
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'failed';
                $output['message']  = 'Can not add more address';
                $output['data']     = NULL;
                $output['error']    = null;
            }
        }
        catch(\Exception $e){
            // Log the exception
            Log::error('Address ad request processing error: ' . $e->getMessage());
            
            $output = [
                'response' => 'failed',
                'message1' => 'An error occurred while processing address add request',
                'message'  => $e->getMessage(),
                'error'    => $e->getMessage()
            ];
        }
        return response()->json($output);
    }
    public function CustomerAddressEdit(Request $req){
        try{
            $req->validate([
                'AddressId'     => 'required',
                'CustomerId'    => 'required',
                'Address'       => 'required',
                'State'         => 'required',
                'City'          => 'required',
                'ZipCode'       => 'required',
            ]);

            $id         = $req->input('AddressId');
            $address    = $req->input('Address');
            $state      = $req->input('State');
            $city       = $req->input('City');
            $zipcode    = $req->input('ZipCode');
            $status     = 0;
            $update_data    = [
                'address'       => $address,
                'city'          => $state,
                'state'         => $city,
                'zipcode'       => $zipcode,
                'status'        => $status,
                'updated_at'    => now()
            ];

            $update = DB::table('customer_address')
                    ->where('id',$id)
                    ->Update($update_data);
            if($update){
                $output['response'] = 'success';
                $output['message']  = 'Address update successfull';
                $output['data']     = NULL;
                $output['error']    = null;
            }
            else{
                $output['response'] = 'failed';
                $output['message']  = 'Address update failed. Please try again';
                $output['data']     = NULL;
                $output['error']    = null;
            }
        }
        catch(\Exception $e){
            // Log the exception
            Log::error('Address update request processing error: ' . $e->getMessage());
            
            $output = [
                'response' => 'failed',
                'message1' => 'An error occurred while processing address update request',
                'message'  => $e->getMessage(),
                'error'    => $e->getMessage()
            ];
        }
        return response()->json($output);
    }
    public function CustomerAddressDelete(Request $req){
        try{
            $req->validate([
                'AddressId' => 'required|integer',
            ]);
            $id = $req->input('AddressId');
            $check  = DB::table('customer_address')
                    ->where([
                        'id'        => $id,
                        'deleted_at'=> NULL,
                    ])
                    ->count();
            if($check>0){
                $delete = DB::table('customer_address')
                        ->where('id',$id)
                        ->update([
                            'status'        => 0,
                            'deleted_at'    => now(),
                        ]);
                if($delete){
                    $output['response'] = 'success';
                    $output['message']  = 'Address delete Successful';
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
                else{
                    $output['response'] = 'failed';
                    $output['message']  = 'Address delete failed';
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'success';
                $output['message']  = 'No data found to delete';
                $output['data']     = NULL;
                $output['error']    = null;
            }
        }
        catch(\Exception $e){
            // Log the exception
            Log::error('Address delete request processing error: ' . $e->getMessage());
            
            $output = [
                'response' => 'failed',
                'message1' => 'An error occurred while processing Address delete request',
                'message'  => $e->getMessage(),
                'error'    => $e->getMessage()
            ];
        }
        return response()->json($output);
    }
    public function CustomerAddressList(Request $req){
        try{
            $req->validate([
                'CustomerId' => 'required',
            ]);
            $id     = $req->input('CustomerId');
            $check  = DB::table('customers')
                    ->where('status',1)
                    ->count();
            if($check > 0){
                $fetch  = DB::table('customer_address')
                        ->where([
                            'customer_id'   => $id,
                            'deleted_at'    => NULL,
                        ])
                        ->select(
                            'id',
                            'customer_id',
                            'address',
                            'city',
                            'state',
                            'zipcode',
                            'status'
                        )
                        ->get();
                if($fetch->isNotEmpty()){
                    $data   = [];

                    foreach($fetch as $val){
                        $data[] = [
                                    'AddressId' => $val->id,
                                    'CustomerId'=> $val->customer_id,
                                    'Address'   => $val->address,
                                    'City'      => $val->city,
                                    'State'     => $val->state,
                                    'Zipcode'   => $val->zipcode,
                                    'Status'    => $val->status,
                                ];
                    }

                    $output['response'] = 'success';
                    $output['message']  = 'Data retrieved successfull';
                    $output['data']     = $data;
                    $output['error']    = null;
                }
                else{
                    $output['response'] = 'success';
                    $output['message']  = 'No data found';
                    $output['data']     = [];
                    $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'failed';
                $output['message']  = 'User Not Active';
                $output['data']     = NULL;
                $output['error']    = null;
            }
        }
        catch(\Exception $e){
            // Log the exception
            Log::error('Address list request processing error: ' . $e->getMessage());
            
            $output = [
                'response' => 'failed',
                'message1' => 'An error occurred while processing address list request',
                'message'  => $e->getMessage(),
                'error'    => $e->getMessage()
            ];
        }
        return response()->json($output);
    }

}