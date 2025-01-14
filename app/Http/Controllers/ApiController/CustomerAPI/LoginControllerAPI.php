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
    //hello
    //test
    public function CustomerRegistration(Request $req){
        try {
            $req->validate([
                    'email'         => 'required|unique:user,email',
                    'mobile'        => 'required|unique:user,mobile|digits:10',
                    'mobile'        => 'required|digits:10',
                    'name'          => 'required',
                    'gst'           => 'required|unique:user,gst',
                    'password'      => 'required',
                ],
                [
                    'email.unique'  => 'This email id is already registered with us. Please use a different email id.',
                    'gst.unique'    => 'This GST number is already registered with us. Please use a different GST number.',
                    'mobile.unique' => 'This mobile number is already registered with us. Please use a different mobile number.',
                    'mobile.digits' => 'The mobile number Must be 10 digits.'
                ]);
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