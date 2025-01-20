<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    public function storeList()
    {
        $data = Store::all();
        return view('store.index', compact('data'));
    }
}
