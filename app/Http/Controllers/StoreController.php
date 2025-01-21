<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    public function storeList()
    {
        $data = Store::all();
        return view('store.index', compact('data'));
    }

    public function addStore(){
        return view('store.add-store');
    }

    public function createStore(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'sname' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'storeimg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'password' => 'required|string|min:5',
                'status' => 'required',
                'activity' => 'required',
                'zip' => 'required',
                'email' => 'required|email',
                'mobile' => 'required|string|max:15',
            ]);
    
            $profileImagePath = null;
            if ($request->hasFile('storeimg')) {
                $profileImagePath = $request->file('storeimg')->store('store_images', 'public');
            }
    
            $store = new Store();
            $store->name = $validatedData['name'];
            $store->store_name = $validatedData['sname'];
            $store->zip_code = $validatedData['zip'];
            $store->username = $validatedData['username'];
            $store->address = $validatedData['address'];
            $store->active_stats = $validatedData['activity'];
            $store->image = $profileImagePath;
            $store->password = Hash::make($validatedData['password']);
            $store->status = $validatedData['status'];
            $store->email = $validatedData['email'];
            $store->mobile = $validatedData['mobile'];
            $store->save();
    
            return redirect()->route('store-list')->with('success', 'Store added successfully!');
          
        } catch (\Exception $e) {
        
            return redirect()->back()->with('Warning', $e->getMessage());
        }
        
    }

    public function edit_store($id){
        $data = Store::findOrFail($id);
        return view('store.edit-store', compact('data'));
    }

    public function updatestore(Request $request, $id)
    {
        try {
            // Validate form data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'sname' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'storeimg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'status' => 'required',
                'activity' => 'required',
                'zip' => 'required',
                'email' => 'required|email',
                'mobile' => 'required|string|max:15',
            ]);

            // Find the user
            $store = Store::findOrFail($id);

            // Handle file upload for profile image
            if ($request->hasFile('storeimg')) {
                // Delete the old profile image if it exists
                if ($store->image && \Storage::exists('public/' . $store->image)) {
                    \Storage::delete('public/' . $store->image);
                }
                // Save the new profile image
                $store->image = $request->file('storeimg')->store('store_images', 'public');
            }

            // Update user data
            $store->name = $validatedData['name'];
            $store->store_name = $validatedData['sname'];
            $store->zip_code = $validatedData['zip'];
            $store->username = $validatedData['username'];
            $store->address = $validatedData['address'];
            $store->active_stats = $validatedData['activity'];
            $store->status = $validatedData['status'];
            $store->email = $validatedData['email'];
            $store->mobile = $validatedData['mobile'];
            $store->save();

            // Redirect back with success message
            return redirect()->route('store-list')->with('success', 'Store updated successfully!');
          
        } catch (\Exception $e) {
        
            return redirect()->back()->with('Warning', $e->getMessage());
        }
        
    }

    public function delete_store(Request $req, $id){
        $user = Store::findOrFail($id)->delete();
        // $delete = DB::table('employees')->where('id',$id)->delete();
        if($user){
            return redirect('/store-list')->with('message', 'Store Delete Successfully');
        }else{
            return back()->with('Warning', 'Store Delete Failed');
        }
    }
}
