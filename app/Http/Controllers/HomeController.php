<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('cpanel.contents.dashboard', get_defined_vars());
    }
}
