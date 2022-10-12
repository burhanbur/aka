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
use App\Models\Major;

use File;
use Auth;
use Alert;
use Exception;

class InstitutionController extends Controller
{
    public function index(Request $request)
    {
        $data = Institution::all();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data){
                    return '
                        <a href="#" value="'.e(route('edit.institution', $data->id)).'" class="btn btn-warning btn-sm modalEdit" title="Edit User" data-toggle="modal" data-target="#modalEdit"><span class="fas fa-pencil-alt"></span></a>
                    ';
                    
                        // <a href="#" value="'.e(route('show.institution', $data->id)).'" class="btn btn-primary btn-sm modalMd" title="Detail User" data-toggle="modal" data-target="#modalMd"><span class="fas fa-search"></span></a>
                })
                ->toJson();
        }

        return view('cpanel.contents.institutions.index', get_defined_vars());
    }

    public function show($id)
    {
        $data = Institution::find($id);

        return view('cpanel.contents.institutions.show', get_defined_vars())->renderSections()['content'];
    }

    public function create()
    {
        return view('cpanel.contents.institutions.add', get_defined_vars())->renderSections()['content'];
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            $institution = Institution::create([
                'code' => $request->code,
                'name' => $request->name,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'website' => $request->website,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
            ]);

            $major_names = $request->major_names;
            $major_codes = $request->major_codes;
            $is_active = $request->is_active;

            for ($i=0; $i < count((array) $major_codes); $i++) { 
                if ($is_active[$i] == 'on') {
                    $status = 1;
                } else {
                    $status = 0;
                }

                $major = new Major;
                $major->institution_id = $institution->id;
                $major->code = $major_codes[$i];
                $major->name = $major_names[$i];
                $major->is_active = $status;
                $major->save();
            }

            DB::commit();
            Alert::success('Success', 'Institution added successfully');
        } catch (Exception $ex) {
            DB::rollBack();

            Alert::error('Error', $ex->getMessage());
        }

        return redirect()->back();
    }

    public function edit($id)
    {
        $data = Institution::find($id);
        $majors = Major::where('institution_id', $id)->get();

        return view('cpanel.contents.institutions.edit', get_defined_vars())->renderSections()['content'];
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],            
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            $institution = Institution::find($id);

            $institution->update([
                'code' => $request->code,
                'name' => $request->name,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'website' => $request->website,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
            ]);

            $u_major_id = $request->u_major_id;
            $u_major_names = $request->u_major_names;
            $u_major_codes = $request->u_major_codes;
            $u_is_active = $request->u_is_active;

            $major_names = $request->major_names;
            $major_codes = $request->major_codes;
            $is_active = $request->is_active;

            // delete
            $check = Major::whereNotIn('id', (array) $u_major_id)->where('institution_id', $id)->get();

            foreach ($check as $value) {
                $value->delete();
            }

            // update
            for ($i=0; $i < count((array) $u_major_id); $i++) { 
                if (@$u_is_active[$i] == 'on') {
                    $status = 1;
                } else {
                    $status = 0;
                }

                $major = Major::find($u_major_id[$i]);
                $major->code = $u_major_codes[$i];
                $major->name = $u_major_names[$i];
                $major->is_active = $status;
                $major->save();
            }

            // add
            for ($i=0; $i < count((array) $major_codes); $i++) { 
                if (@$is_active[$i] == 'on') {
                    $status = 1;
                } else {
                    $status = 0;
                }

                $major = new Major;
                $major->institution_id = $institution->id;
                $major->code = $major_codes[$i];
                $major->name = $major_names[$i];
                $major->is_active = $status;
                $major->save();
            }

            DB::commit();
            Alert::success('Success', 'Institution updated successfully');
        } catch (Exception $ex) {
            DB::rollBack();

            Alert::error('Error', $ex->getMessage());
        }

        return redirect()->back();
    }

    public function majors(Request $request)
    {
        $userId = auth()->user()->id;
        $institutionId = @auth()->user()->institution()->first()->id;
        $data = Major::where('institution_id', $institutionId)->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($data) {
                    if ($data->is_active) {
                        $stat = 'Aktif';
                    } else {
                        $stat = 'Tidak aktif';
                    }

                    return $stat;
                })
                ->addColumn('action', function ($data){
                    return '
                        <a href="#" value="'.e(route('edit.institution.major', $data->id)).'" class="btn btn-warning btn-sm modalEdit" title="Edit Progoram Studi" data-toggle="modal" data-target="#modalEdit"><span class="fas fa-pencil-alt"></span></a>
                    ';
                    
                        // <a href="#" value="'.e(route('show.institution', $data->id)).'" class="btn btn-primary btn-sm modalMd" title="Detail User" data-toggle="modal" data-target="#modalMd"><span class="fas fa-search"></span></a>
                })
                ->toJson();
        }

        return view('cpanel.contents.institutions.major', get_defined_vars());
    }

    public function createMajor()
    {
        return view('cpanel.contents.institutions.add_major', get_defined_vars())->renderSections()['content'];
    }

    public function storeMajor(Request $request)
    {
        $data = $request->all();
        $institutionId = @auth()->user()->institution()->first()->id;

        $validator = Validator::make($data, [
            // 'code' => ['required', 'string', 'max:255'],
            // 'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            $major_names = $request->major_names;
            $major_codes = $request->major_codes;
            $is_active = $request->is_active;

            for ($i=0; $i < count((array) $major_codes); $i++) { 
                if ($is_active[$i] == 'on') {
                    $status = 1;
                } else {
                    $status = 0;
                }

                $major = new Major;
                $major->institution_id = $institutionId;
                $major->code = $major_codes[$i];
                $major->name = $major_names[$i];
                $major->is_active = $status;
                $major->save();
            }

            DB::commit();
            Alert::success('Success', 'Major added successfully');
        } catch (Exception $ex) {
            DB::rollBack();

            Alert::error('Error', $ex->getMessage());
        }

        return redirect()->back();
    }

    public function editMajor($id)
    {
        $data = Major::find($id);

        return view('cpanel.contents.institutions.edit_major', get_defined_vars())->renderSections()['content'];
    }

    public function updateMajor(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],            
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            $data = Major::find($id);
            if ($request->is_active == 'on') {
                $status = 1;
            } else {
                $status = 0;
            }

            $data->update([
                'code' => $request->code,
                'name' => $request->name,
                'is_active' => $status,
            ]);

            DB::commit();
            Alert::success('Success', 'Major updated successfully');
        } catch (Exception $ex) {
            DB::rollBack();

            Alert::error('Error', $ex->getMessage());
        }

        return redirect()->back();
    }
}
