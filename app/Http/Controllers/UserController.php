<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

use Yajra\DataTables\Facades\DataTables;

use App\Models\User;
use App\Models\Institution;
use App\Models\UserInstitution;

use File;
use Auth;
use Alert;
use Exception;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = User::all();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('institution', function($data) {
                    return @$data->institution()->first()->name;
                })
                ->addColumn('action', function ($data){
                    return '
                        <a href="#" value="'.e(route('show.user', $data->id)).'" class="btn btn-primary btn-sm modalMd" title="Detail User" data-toggle="modal" data-target="#modalMd"><span class="fas fa-search"></span></a>
                        <a href="#" value="'.e(route('edit.user', $data->id)).'" class="btn btn-warning btn-sm modalEdit" title="Edit User" data-toggle="modal" data-target="#modalEdit"><span class="fas fa-pencil-alt"></span></a>
                    ';
                })
                ->toJson();
        }

        return view('cpanel.contents.users.index', get_defined_vars());
    }

    public function show($id)
    {
        $data = User::find($id);

        return view('cpanel.contents.users.show', get_defined_vars())->renderSections()['content'];
    }

    public function create()
    {
        return view('cpanel.contents.users.add', get_defined_vars())->renderSections()['content'];
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'institution_code' => ['required', 'string', 'max:255'],
            'institution_name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $institution = Institution::create([
                'code' => $data['institution_code'],
                'name' => $data['institution_name'],
                'telephone' => $request->institution_telephone,
                'email' => $request->institution_email,
                'website' => $request->institution_website,
                'address' => $request->institution_address,
                'postal_code' => $request->institution_postal_code,
            ]);

            UserInstitution::create([
                'user_id' => $user->id,
                'institution_id' => $institution->id,
            ]);

            DB::commit();
            Alert::success('Success', 'User added successfully');
        } catch (Exception $ex) {
            DB::rollBack();

            Alert::error('Error', $ex->getMessage());
        }

        return redirect()->back();
    }

    public function edit($id)
    {
        $data = User::find($id);

        return view('cpanel.contents.users.edit', get_defined_vars())->renderSections()['content'];
    }

    public function update(Request $request, $id)
    {

        $data = $request->all();

        $validator = Validator::make($data, [
            'institution_id' => ['required', 'string', 'max:255'],
            'institution_code' => ['required', 'string', 'max:255'],
            'institution_name' => ['required', 'string', 'max:255'],            
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            $institution = Institution::find($request->institution_id);

            $institution->update([
                'code' => $request->institution_code,
                'name' => $request->institution_name,
                'telephone' => $request->institution_telephone,
                'email' => $request->institution_email,
                'website' => $request->institution_website,
                'address' => $request->institution_address,
                'postal_code' => $request->institution_postal_code,
            ]);

            DB::commit();
            Alert::success('Success', 'User updated successfully');
        } catch (Exception $ex) {
            DB::rollBack();

            Alert::error('Error', $ex->getMessage());
        }

        return redirect()->back();
    }

    public function profile()
    {
    	$userId = Auth::user()->id;
        $data = User::find($userId);

        return view('cpanel.contents.profile', get_defined_vars())->renderSections()['content'];
    }
}
