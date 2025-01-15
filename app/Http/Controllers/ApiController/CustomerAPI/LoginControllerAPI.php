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
                    'username'      => 'required|unique:customers,username',
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
                    'username.unique'   => 'This username id is already registered with us. Please use a different username.',
                    'email.unique'      => 'This email id is already registered with us. Please use a different email id.',
                    'mobile.unique'     => 'This mobile number is already registered with us. Please use a different mobile number.',
                    'mobile.digits'     => 'The mobile number Must be 10 digits.'
                ]);
            
            $name       = $req->name;
            $username   = $req->username;
            $password   = $req->password;
            $CPassword  = $req->ConfPassword;
            $email      = $req->email;
            $mobile     = $req->mobile;
            $address    = $req->address;
            $city       = $req->city;
            $state      = $req->state;
            $zipcode    = $req->zipcode;
            $created_at = Carbon::now();
            if($password === $CPassword){
                $insert     = DB::table('customers')->insertGetId([
                                'name'      => $name,
                                'username'  => $username,
                                'password'  => Hash::make($password),
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
                                'name as Name',
                                'mobile as Mobile',
                                'email as Email',
                                'address as Address',
                                'city as City',
                                'zipcode as ZipCode',
                                'status'
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
    public function customer_login(Request $req){
        try {
            
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
}