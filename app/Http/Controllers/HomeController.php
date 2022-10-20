<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Institution;

use DB;

class HomeController extends Controller
{
    public function index()
    {
        $arrCountInst = [];
        $arrMonthInst = [];

        $data = DB::select("select count(id) as count, month(created_at) as month from institutions group by month(created_at)");

        foreach ($data as $value) {
            $arrCountInst[] = $value->count;
            $arrMonthInst[] = bulan($value->month);
        }

        return view('cpanel.contents.dashboard', get_defined_vars());
    }
}
