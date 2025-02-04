<?php

namespace App\Http\Controllers;

use App\Models\kaffah;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $data = kaffah::all();
        return view('admin', compact('data'));
    }
}
