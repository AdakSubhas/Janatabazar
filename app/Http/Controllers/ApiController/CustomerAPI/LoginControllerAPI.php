<?php

namespace App\Http\Controllers\ApiController\CustomerAPI;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;



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

            // if ($photo = $req->file('photo')){
            //     $photo = date('Y-m-d-H-i-s').rand(1000,9999).$req->file('photo')->getClientOriginalName();
            //     $ext = $req->file('photo')->getClientOriginalExtension();
            //     $req->file('photo')->storeAs('public/Customer', $photo);
            // }
            // else{
            //     $photo='';
            // }

            if($password === $CPassword){
                $insert     = DB::table('customers')->insertGetId([
                                'name'      => $name,
                                'username'  => $username,
                                'password'  => Hash::make($password),
                                // 'photo'     => $photo,
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
                                    DB::raw("CONCAT('" . env('APP_URL') . "storage/Customer/default.jpg', cu.photo) as ProfilePhoto"),
                                    // DB::raw("IF(cu.photo IS NOT NULL AND cu.photo != '', CONCAT('" . env('APP_URL') . "storage/Customer/', cu.photo), CONCAT('" . env('APP_URL') . "storage/Customer/default.jpg')) as ProfilePhoto"),
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
    public function CustomerOTP(Request $req){
        try{
            $req->validate([
                    'Mobile'    => 'required',
                    'DeviceId'  => 'required',
                    'DeviceName'=> 'required'
                ]);
            $mobile     = $req->input('Mobile');
            $device_id  = $req->input('DeviceId');
            $device_name= $req->input('DeviceName');
            $otp        = sprintf('%04d', random_int(0, 9999));
            
            $find   = DB::table('customers')
                    ->where('mobile',$mobile)
                    ->first();
            if($find){
                if($find->status == 1){
                    $device_check   = DB::table('device_access')
                                    ->where([
                                        'user_table'    => 'customer',
                                        'user_id'       => $find->id,
                                    ])
                                    ->count();
                    if($device_check > 0){
                        $device_check1  = DB::table('device_access')
                                        ->where([
                                            'user_table'=> 'customer',
                                            'user_id'   => $find->id,
                                            'status'    => 1
                                        ])
                                        ->count();
                        if($device_check1 > 0){
                            $device_check2  = DB::table('device_access')
                                            ->where([
                                                'user_table'    => 'customer',
                                                'user_id'       => $find->id,
                                                'device_id'     => $device_id,
                                                'status'        => 1
                                            ])
                                            ->count();
                            if($device_check2 != 0){

                            }
                        }
                        else{
                            $device_check3  = DB::table('device_access')
                                            ->where([
                                                'user_table'    => 'customer',
                                                'user_id'       => $find->id,
                                                'device_id'     => $device_id
                                            ])
                                            ->count();
                            if($device_check3 != 0){
                                $update = DB::table('device_access')
                                        ->where([
                                            'user_table'    => 'customer',
                                            'user_id'       => $find->id,
                                            'device_id'     => $device_id
                                        ])
                                        ->update([
                                            'status'        => 1,
                                            'updated_at'    => now()
                                        ]);
                                
                                if($update){
                                    $update = DB::table('customers')
                                            ->where('mobile',$mobile)
                                            ->update([
                                                'otp'       => $otp,
                                                'updated_at'=> now()
                                            ]);
                                }
                                else{
                                    $output['response'] = 'failed';
                                    $output['message']  = 'Otp send failed';
                                    $output['error']    = null;
                                }
                            }
                            else{
                                $insert = DB::table('device_access')
                                        ->insert([
                                            'user_table'    => 'customer',
                                            'user_id'       => $find->id,
                                            'device_id'     => $device_id,
                                            'device_name'   => $device_name,
                                            'status'        => 1,
                                            'created_at'    => date('Y-m-d H:i:s')
                                        ]);

                                if($insert){
                                    $update = DB::table('customers')
                                            ->where('mobile',$mobile)
                                            ->update([
                                                'otp'       => $otp,
                                                'updated_at'=> now()
                                            ]);
                                }
                                else{
                                    $output['response'] = 'failed';
                                    $output['message']  = 'Otp send failed';
                                    $output['error']    = null;
                                }
                            }
                        }
                    }
                    else{
                        $insert = DB::table('device_access')
                                ->insert([
                                    'user_table'    => 'customer',
                                    'user_id'       => $find->id,
                                    'device_id'     => $device_id,
                                    'device_name'   => $device_name,
                                    'status'        => 1,
                                    'created_at'    => date('Y-m-d H:i:s')
                                ]);
                        if($insert){
                            $update = DB::table('customers')
                                    ->where('mobile',$mobile)
                                    ->update([
                                        'otp'       => $otp,
                                        'updated_at'=> now()
                                    ]);
                        }
                        else{
                            $output['response'] = 'failed';
                            $output['message']  = 'Otp send failed';
                            $output['error']    = null;
                        }
                    }
                    if($update){
                        $apiUrl = env('SMS_API_URL');
                        $apiKey = env('SMS_API_KEY');
                        $senderId = env('SMS_SENDER_ID');
                        $receiverNumber = $mobile;
                
                        // Prepare the message
                        $message = "Your OTP is $otp. Do not share it with anyone. -CHAALAKYA CATERERS & EVENTS LLP";
                        $encode_message = urlencode($message);
                        
                        // Build the API URL for sending SMS
                        $url = "$apiUrl?apikey=$apiKey&senderid=$senderId&number=$receiverNumber&message=$encode_message&format=json";
                
                        // Send SMS request
                        try {
                            $response = Http::get($url); // Use GET based on most SMS API formats
                
                            if ($response->successful()) {
                                // Retrieve customer data again after inserting
                                $customer_data  = DB::table('customers')->where(['mobile' => $mobile])->get();
                
                                // Success response for OTP sent
                                $output['response'] = 'Success';
                                $output['message']  = 'OTP sent successfully';
                                // $output['data']     = (int)$otp;
                                $output['data']     = $customer_data;
                                $output['error']    = 'null';
                            }
                            else {
                                // Error in sending OTP
                                $output['response'] = 'Error';
                                $output['message'] = 'Failed to send OTP';
                                $output['data'] = '';
                                $output['error'] = 'SMS API failure';
                                // Log response for debugging
                                Log::error('SMS API Failure: ' . $response->body());
                            }
                        } catch (Exception $e) {
                            // Handle any exceptions during the API call
                            $output['response'] = 'Error';
                            $output['message'] = 'Error sending OTP';
                            $output['data'] = '';
                            $output['error'] = $e->getMessage();
                            // Log the exception
                            Log::error('SMS API Exception: ' . $e->getMessage());
                        }
                    }
                    else{
                        $output['response'] = 'Error';
                        $output['message'] = 'Failed to faield to get otp';
                        $output['data'] = '';
                        $output['error'] = 'Insert failed';
                    }
                }
                else{
                    $output['response'] = 'failed';
                    $output['message']  = 'User is inactive';
                    $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'failed';
                $output['message']  = 'Mobile Number Not Registered';
                $output['error']    = null;
            }
        }
        catch(\Exception $e){
            // Log the exception
            Log::error('OTP send request processing error: ' . $e->getMessage());
            
            $output = [
                'response' => 'failed',
                'message1' => 'An error occurred while processing OTP Send request',
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
                'OTP'       => 'required',
            ]);
            $username   = $req->input('UserName');
            $otp        = $req->input('OTP');

            $find   = DB::table('customers')
                    ->where('mobile',$username)
                    // ->orWhere('email',$username)
                    ->first();
            if($find){
                if($find->status == 1){
                    if($otp === $find->otp){
                        $find1  = DB::table('customer_address')
                                ->where([
                                    'customer_id'   => $find->id,
                                    'status'        => 1,
                                ])
                                ->first();
                        $data   = [
                                    'Id'        => $find->id,
                                    'Name'      => $find->name,
                                    'Photo'     => !empty($find->photo) ? env('APP_URL').'storage/Customer/'.$find->id.'/'.$find->photo : env('APP_URL').'storage/Customer/default.jpg',
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
                        $output['message']  = 'OTP not match';
                        $output['error']    = null;
                    }
                }
                else{
                    $output['response'] = 'failed';
                    $output['message']  = 'User account is inactive';
                    $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'failed';
                $output['message']  = 'Mobile Number Not Registered';
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
    // public function CustomerLogin(Request $req){
    //     try {
    //         $req->validate([
    //             'UserName'  => 'required',
    //             'Password'  => 'required',
    //         ]);
    //         $username   = $req->UserName;
    //         $pass       = $req->Password;

    //         $find   = DB::table('customers')
    //                 ->where('mobile',$username)
    //                 ->orWhere('email',$username)
    //                 ->first();
    //         if($find){
    //             if($find->status == 1){
    //                 if(Hash::check($pass,$find->password)){
    //                     $find1  = DB::table('customer_address')
    //                             ->where([
    //                                 'customer_id'   => $find->id,
    //                                 'status'        => 1,
    //                             ])
    //                             ->first();
    //                     $data   = [
    //                                 'Id'        => $find->id,
    //                                 'Name'      => $find->name,
    //                                 'Photo'     => !empty($find->photo) ? env('APP_URL').'storage/Customer/'.$find->id.'/'.$find->photo : env('APP_URL').'storage/Customer/default.jpg',
    //                                 'Email'     => $find->email ?? NULL,
    //                                 'Mobile'    => $find->mobile,
    //                                 'Address'   => $find1->address ?? NULL,
    //                                 'City'      => $find1->city ?? NULL,
    //                                 'State'     => $find1->state ?? NULL,
    //                                 'zipcode'   => $find1->zipcode ?? NULL,
    //                                 'Status'    => $find->status,
    //                             ];
    //                     $output['response'] = 'success';
    //                     $output['message'] = 'User Login Successful';
    //                     $output['data'] = $data;
    //                     $output['error'] = null;
    //                 }
    //                 else{
    //                     $output['response'] = 'failed';
    //                     $output['message']  = 'Password not match';
    //                     $output['error']    = null;
    //                 }
    //             }
    //             else{
    //                 $output['response'] = 'failed';
    //                     $output['message']  = 'Password not match';
    //                     $output['error']    = null;
    //             }
    //         }
    //         else{
    //             $output['response'] = 'failed';
    //             $output['message']  = 'User account is inactive';
    //             $output['error']    = null;
    //         }
    //     }
    //     catch(\Exception $e){
    //         // Log the exception
    //         Log::error('Registration request processing error: ' . $e->getMessage());
            
    //         $output = [
    //             'response' => 'failed',
    //             'message1' => 'An error occurred while processing the registration request',
    //             'message'  => $e->getMessage(),
    //             'error'    => $e->getMessage()
    //         ];
    //     }
    //     return response()->json($output);
    // }
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
    public function CustomerProfileEdit(Request $req){
        try{
            $req->validate([
                'id'            => 'required',
                'name'          => 'required',
                'Profile_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            ]);
            $id     = $req->input('id');
            $name   = $req->input('name');
            $email  = $req->input('email');
            $photo  = $req->input('Profile_image');
            $data   = [];
            $output = [];
            $update_data1   = [];

            $check  = DB::table('customers')
                    ->where([
                        'id' => $id,
                        'status' => 1
                    ])
                    ->first();
            if($check){
                if (!empty($photo)) {
                    // Define the folder path relative to storage/app/public
                    $folderPath = 'Customer/' . $id;
                    
                    // Create the directory if it doesn't exist
                    if (!Storage::disk('public')->exists($folderPath)) {
                        Storage::disk('public')->makeDirectory($folderPath);
                    }
                
                    // Check and delete the existing image
                    $existingImagePath = 'public/Customer/'.$id.'/'. $check->photo; // Assuming `$check->photo` holds the current image name
                    if (Storage::exists($existingImagePath)) {
                        if (Storage::delete($existingImagePath)) {
                            $message = "Existing image deleted successfully.";
                        } else {
                            $message = "Failed to delete the existing image.";
                        }
                    } else {
                        $message = "Existing image not found.";
                    }
                
                    // Generate a new image name and save the new image
                    $imageName = date('Y-m-d-H-i-s') . rand(1000, 9999) . '.' . $photo->getClientOriginalExtension();
                    $photo->storeAs('public/Customer/' . $id, $imageName); // Save in the specific folder
                } else {
                    // Keep the existing photo if no new photo is provided
                    $imageName = $check->photo; // Assuming `$check->photo` has the current image name
                }
                
                // Prepare data for updating the customer
                $update_data1 = [
                    'name'       => $name,
                    'email'      => $email,
                    'photo'      => $imageName,
                    'updated_at' => now(),
                ];
                
                // Update the customer details in the database
                $update_details = DB::table('customers')
                                ->where('id', $id)
                                ->update($update_data1);
                if($update_details){
                    $find1  = DB::table('customer_address')
                                ->where([
                                    'customer_id'   => $id,
                                    'status'        => 1,
                                ])
                                ->first();
                    $data   = [
                                'Id'        => $id,
                                'Name'      => $name,
                                'Photo'     => !empty($imageName) ? env('APP_URL').'storage/Customer/'.$id.'/'.$imageName : env('APP_URL').'storage/Customer/default.jpg',
                                'Email'     => $email ?? NULL,
                                'Mobile'    => $check->mobile,
                                'Address'   => $find1->address ?? NULL,
                                'City'      => $find1->city ?? NULL,
                                'State'     => $find1->state ?? NULL,
                                'zipcode'   => $find1->zipcode ?? NULL,
                                'Status'    => $check->status,
                            ];
                    $output['response'] = 'sucess';
                    $output['message']  = 'Profile data update successfully';
                    $output['data']     = $data;
                    $output['error']    = null;
                }
                else{
                    $output['response'] = 'failed';
                    $output['message']  = 'profile data update failed';
                    $output['data']     = NULL;
                    $output['error']    = null;
                }
            }
            else{
                $output['response'] = 'failed';
                $output['message']  = 'User Not found';
                $output['data']     = NULL;
                $output['error']    = null;
            }
        }
        catch(\Exception $e){
            // Log the exception
            Log::error('Customer profile update request processing error: ' . $e->getMessage());
            
            $output = [
                'response' => 'failed',
                'message1' => 'An error occurred while processing customer profile edit request',
                'message'  => $e->getMessage(),
                'error'    => $e->getMessage()
            ];
        }
        return response()->json($output);
    }
    public function CustomerAddressStatusChange(Request $req){
        try{
            $req->validate([
                'CustomerId'    => 'required',
                'AddressId'     => 'required',
            ]);
            $id     = $req->input('CustomerId');
            $address= $req->input('AddressId');
            $check  = DB::table('customers')
                    ->where('status',1)
                    ->count();
            if($check > 0){
                $fetch  = DB::table('customer_address')
                        ->where([
                            'customer_id'   => $id,
                            'deleted_at'    => NULL,
                            'id'            => $address,
                        ])
                        ->update([
                            'status'    => 1,
                            'updated_at'=> now()
                        ]);
                if($fetch){
                    $update = DB::table('customer_address')
                            ->where('id', '!=', $address)
                            ->where('customer_id', '=', $id)
                            ->whereNull('deleted_at')
                            ->update([
                                'status'    => 0,
                                'updated_at'=> now()
                            ]);
                    if($update){
                        $output['response'] = 'success';
                        $output['message']  = 'Address Change successfull';
                        $output['data']     = NULL;
                        $output['error']    = null;
                    }
                    else{
                        $output['response'] = 'failed';
                        $output['message']  = 'Address not change';
                        $output['data']     = NULL;
                        $output['error']    = null;
                    }
                }
                else{
                    $output['response'] = 'failed';
                    $output['message']  = 'Something went wrong. please try again...';
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
            Log::error('Address status change request processing error: ' . $e->getMessage());
            
            $output = [
                'response' => 'failed',
                'message1' => 'An error occurred while processing address status change request',
                'message'  => $e->getMessage(),
                'error'    => $e->getMessage()
            ];
        }
        return response()->json($output);
    }
    // public function CustomerPasswordChange(Request $req){
    //     try{
    //         $req->validate([
    //             'id'                    => 'required',
    //             'password'              => 'required|string|min:8|confirmed',
    //             'password_confirmation' => 'required|string|min:8'
    //         ],
    //         [
    //             'password.confirmed' => 'The password and confirm password do not match.',
    //             'password.min'       => 'The password must be at least 8 characters long.',
    //         ]);
    //         $id = $req->input('id');
    //         $pas= $req->input('password');
    //         $check  = DB::table('customers')
    //                 ->where([
    //                     'id'    => $id,
    //                     'status'=> 1,
    //                 ])
    //                 ->count();
    //         if($check){
    //             $update = DB::table('customers')
    //                     ->where('id',$id)
    //                     ->update([
    //                         'password'  => bcrypt($pas),
    //                         'updated_at'=> now(),
    //                     ]);
    //             if($update){
    //                 $output['response'] = 'sucess';
    //                 $output['message']  = 'Password change successfully';
    //                 $output['data']     = NULL;
    //                 $output['error']    = null;
    //             }
    //             else{
    //                 $output['response'] = 'failed';
    //                 $output['message']  = 'Failed to change password. Please try again...';
    //                 $output['data']     = NULL;
    //                 $output['error']    = null;
    //             }
    //         }
    //         else{
    //             $output['response'] = 'failed';
    //             $output['message']  = 'User Not found';
    //             $output['data']     = NULL;
    //             $output['error']    = null;
    //         }
    //     }
    //     catch(\Exception $e){
    //         // Log the exception
    //         Log::error('Customer profile update request processing error: ' . $e->getMessage());
            
    //         $output = [
    //             'response' => 'failed',
    //             'message1' => 'An error occurred while processing customer profile edit request',
    //             'message'  => $e->getMessage(),
    //             'error'    => $e->getMessage()
    //         ];
    //     }
    //     return response()->json($output);
    // }
}