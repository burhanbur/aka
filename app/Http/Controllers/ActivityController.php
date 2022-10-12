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
use App\Models\ActivityType;
use App\Models\Activity;
use App\Models\Survey;

use File;
use Auth;
use Alert;
use Exception;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $data = Activity::all();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('type', function($data) {
                    return @$data->activityType->name;
                })
                ->addColumn('countSurvey', function($data) {
                    $count = Survey::where('activity_id', $data->id)->count();

                    return $count;
                })
                ->addColumn('action', function ($data){
                    return '
                        <a href="#" value="'.e(route('edit.activity', $data->id)).'" class="btn btn-warning btn-sm modalEdit" title="Edit User" data-toggle="modal" data-target="#modalEdit"><span class="fas fa-pencil-alt"></span></a>
                    ';
                    
                    // <a href="#" value="'.e(route('show.activity', $data->id)).'" class="btn btn-primary btn-sm modalMd" title="Detail User" data-toggle="modal" data-target="#modalMd"><span class="fas fa-search"></span></a>
                })
                ->toJson();
        }

        return view('cpanel.contents.activities.index', get_defined_vars());
    }

    public function show($id)
    {
        $data = Activity::find($id);

        return view('cpanel.contents.activities.show', get_defined_vars())->renderSections()['content'];
    }

    public function create()
    {
        $types = ActivityType::all();

        return view('cpanel.contents.activities.add', get_defined_vars())->renderSections()['content'];
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'activity_type' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            Activity::create([
                'activity_type' => $request->activity_type,
                'name' => $request->name,
            ]);

            DB::commit();
            Alert::success('Success', 'Activity added successfully');
        } catch (Exception $ex) {
            DB::rollBack();

            Alert::error('Error', $ex->getMessage());
        }

        return redirect()->back();
    }

    public function edit($id)
    {
        $types = ActivityType::all();
        $data = Activity::find($id);

        return view('cpanel.contents.activities.edit', get_defined_vars())->renderSections()['content'];
    }

    public function update(Request $request, $id)
    {

        $data = $request->all();

        $validator = Validator::make($data, [
            'activity_type' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],      
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            $data = Activity::find($id);

            $data->update([
                'activity_type' => $request->activity_type,
                'name' => $request->name,
            ]);

            DB::commit();
            Alert::success('Success', 'Activity updated successfully');
        } catch (Exception $ex) {
            DB::rollBack();

            Alert::error('Error', $ex->getMessage());
        }

        return redirect()->back();
    }
}
