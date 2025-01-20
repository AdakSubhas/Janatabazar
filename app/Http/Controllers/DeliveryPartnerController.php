<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliveryPartner;

class DeliveryPartnerController extends Controller
{
    public function DeliveryPartnerList()
    {
        $data = DeliveryPartner::all();
        return view('delivery_partner.index', compact('data'));
    }
}
