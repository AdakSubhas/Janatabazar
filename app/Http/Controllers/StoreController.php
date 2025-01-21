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
    }
}
