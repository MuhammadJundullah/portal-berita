<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kaffah;
use App\Models\News;

class KaffahController extends Controller
{
    public function index()
    {
        $data = News::all();
        return view('home', compact('data'));
    }
}
