<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use Yajra\DataTables\Facades\DataTables;

use App\Models\User;

use File;
use Auth;
use Alert;
use Exception;

class UserController extends Controller
{

    public function profile()
    {
    	$userId = Auth::user()->id;
        $data = User::find($userId);

        return view('cpanel.contents.profile', get_defined_vars())->renderSections()['content'];
    }
}
