<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;

class UserController extends Controller
{
    public function index()
    {
        $data = Employee::all();
        return view('users.index', compact('data'));
    }

}
