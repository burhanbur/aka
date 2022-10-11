<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use Yajra\DataTables\Facades\DataTables;

use App\Models\Activity;
use App\Models\Survey;

use File;
use Auth;
use Alert;
use Exception;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
    	$data = Survey::all();

    	if ($request->ajax()) {
    		return Datatables::of($data)
		        ->addIndexColumn()
		        ->addColumn('activity', function($data) {
		        	return @$data->activity->name;
		        })
                ->addColumn('due_date', function($data) {
                    return @tanggal($data->due_date);
                })
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
                    	<a href="'.e(route('show.survey', $data->id)).'" class="btn btn-primary btn-sm" title="Show Responden"><span class="fas fa-search"></span></a>
                        <a href="'.e(route('edit.survey', $data->id)).'" class="btn btn-warning btn-sm" title="Edit Survey"><span class="fas fa-pencil-alt"></span></a>
                    	<form style="display: inline;" method="POST" action="'.e(route('delete.survey', $data->id)).'" onsubmit="return confirm('."'Are you sure want to delete this data?'".')"> <input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.csrf_token().'"> <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button> </form>
		            ';
		        })
		        ->toJson();
        }

        return view('cpanel.contents.surveys.index', get_defined_vars());
    }

    public function show($id)
    {
        $data = Survey::find($id);

        return view('cpanel.contents.surveys.show', get_defined_vars());
    }

    public function create()
    {
    	$activities = Activity::all();
    	
    	return view('cpanel.contents.surveys.add', get_defined_vars());
    }

    public function edit($id)
    {
        $survey = $this->surveyService->getById($id);
        $events = $this->eventService->getAll();

        $totalQuestion = $survey->question->count();
        
        return view('cpanel.contents.surveys.edit', get_defined_vars());
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'event_id' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['required'],
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
        } else {
            $err = $this->surveyService->saveData($request);

            if ($err) {
                Alert::error('Error', $err);
            } else {
                Alert::success('Success', 'Survey added successfully');
            }
        }

        return redirect()->route('surveys');
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'event_id' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['required'],
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
        } else {
            $err = $this->surveyService->updateData($request, $id);

            if ($err) {
                Alert::error('Error', $err);
            } else {
                Alert::success('Success', 'Survey updated successfully');
            }
        }

        return redirect()->back();
        // return redirect()->route('surveys');
    }

    public function destroy($id)
    {
        $err = $this->surveyService->deleteById($id);

        if ($err) {
            Alert::error('Error', $err);
        } else {
            Alert::success('Success', 'Survey deleted successfully');
        }

        return redirect()->back();
    }

    public function listEventSurvey(Request $request)
    {
        $data = $this->surveyService->getAllMySurvey(Auth::user()->id);

        // echo "<pre>";
        // var_dump($data);
        // die();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('due_date', function($data) {
                    return @tanggal($data->due_date);
                })
                ->addColumn('action', function ($data){
                    return '
                        <a href="'.e(route('fill.event.survey', $data->id)).'" class="btn btn-primary btn-sm" title="Fill  Survey"><span class="fas fa-pencil-alt"></span></a>
                    ';
                })
                ->toJson();
        }

        return view('cpanel.contents.surveys.my_survey', get_defined_vars());
    }

    public function fillEventSurvey($id)
    {
        $check = $this->surveyService->isMemberEvent($id, Auth::user()->id);

        if (!$check) {
            Alert::error('Error', 'Anda tidak dapat mengisi kuesioner ini');

            return redirect()->back();
        }

        $data = $this->surveyService->getById($id);

        return view('cpanel.contents.surveys.fill_survey', get_defined_vars());
    }

    public function storeEventSurvey(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'survey_id' => ['required'],
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
        } else {
            $err = $this->surveyService->fillSurvey($request, Auth::user()->id);

            if ($err) {
                Alert::error('Error', $err);
            } else {
                Alert::success('Success', 'Fill survey data successfully');
            }
        }

        return redirect()->route('event.surveys');
    }
}
