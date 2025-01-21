<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $data = Employee::all();
        return view('users.index', compact('data'));
    }

    public function add_user(){
        return view('users.add-user');
    }

    
    
    public function create_user(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'profilei' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'password' => 'required|string|min:5',
                'status' => 'required',
                'email' => 'required|email',
                'mobile' => 'required|string|max:15',
            ]);
    
            $profileImagePath = null;
            if ($request->hasFile('profilei')) {
                $profileImagePath = $request->file('profilei')->store('profile_images', 'public');
            }
    
            $user = new Employee();
            $user->name = $validatedData['name'];
            $user->username = $validatedData['username'];
            $user->address = $validatedData['address'];
            $user->profile = $profileImagePath;
            $user->password = Hash::make($validatedData['password']);
            $user->status = $validatedData['status'];
            $user->email = $validatedData['email'];
            $user->mobile = $validatedData['mobile'];
            $user->save();
    
            return redirect()->route('users')->with('success', 'User added successfully!');
        }catch(\Exception $e){
            return redirect()->back()->with('Warning', $e->getMessage());
        }
        
    }

    public function edit_user($id){
        $user = Employee::findOrFail($id);
        return view('users.edit-user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        try{
            // Validate form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'profilei' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required',
            'email' => 'required|email',
            'mobile' => 'nullable|string|max:15',
        ]);

        // Find the user
        $user = Employee::findOrFail($id);

        // Handle file upload for profile image
        if ($request->hasFile('profilei')) {
            // Delete the old profile image if it exists
            if ($user->profile && \Storage::exists('public/' . $user->profile)) {
                \Storage::delete('public/' . $user->profile);
            }
            // Save the new profile image
            $user->profile = $request->file('profilei')->store('profile_images', 'public');
        }

        // Update user data
        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];
        $user->address = $validatedData['address'];
        $user->status = $validatedData['status'];
        $user->email = $validatedData['email'];
        $user->mobile = $validatedData['mobile'];
        $user->save();

        // Redirect back with success message
        return redirect()->route('users')->with('success', 'User updated successfully!');
        }catch(\Exception $e){
            return redirect()->back()->with('Warning', $e->getMessage());
        }
        
    }

    public function delete_user(Request $req, $id){
        $user = Employee::findOrFail($id)->delete();
        // $delete = DB::table('employees')->where('id',$id)->delete();
        if($user){
            return redirect('/users')->with('message', 'User Delete Successfully');
        }else{
            return back()->with('Warning', 'User Delete Failed');
        }
    }

}
