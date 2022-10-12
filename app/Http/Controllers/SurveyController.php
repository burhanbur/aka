<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use Yajra\DataTables\Facades\DataTables;

use App\Models\Activity;
use App\Models\Answer;
use App\Models\Survey;
use App\Models\Responden;
use App\Models\Question;
use App\Models\MultipleChoice;

use File;
use Session;
use Auth;
use Alert;
use Exception;
use InvalidArgumentException;
use Carbon\Carbon;

class SurveyController extends Controller
{
    public function __construct()
    {
        $this->path = 'files/surveys';
    }

    public function index(Request $request)
    {
    	$data = Survey::all();

    	if ($request->ajax()) {
    		return Datatables::of($data)
		        ->addIndexColumn()
		        ->addColumn('activity', function($data) {
		        	return @$data->activity->name;
		        })
                ->addColumn('due_date2', function($data) {
                    return @tanggal($data->due_date);
                })
                ->addColumn('question', function($data) {
                    return $data->question->count();
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
        $survey = Survey::find($id);
        $activities = Activity::all();

        $totalQuestion = $survey->question->count();
        
        return view('cpanel.contents.surveys.edit', get_defined_vars());
    }

    public function store(Request $data)
    {
        // $data = $request->all();

        $validator = Validator::make($data->all(), [
            'activity_id' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['required'],
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());

            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            if ($data->is_active == 'on') {
                $data->is_active = 1;
            } else {
                $data->is_active = 0;
            }

            $current = strtotime(Carbon::now());

            $survey = new Survey;
            $survey->activity_id = $data->activity_id;
            $survey->title = $data->title;
            $survey->description = $data->description;
            $survey->is_active = $data->is_active;
            $survey->due_date = $data->due_date;
            $survey->save();

            $questions = @$data->question;
            $images = @$data->image;
            $types = @$data->type;
            $requires = @$data->is_required;

            if (is_array($questions)) {
                foreach ($questions as $key => $value) {
                    if (@$requires[$key] == 'on') {
                        $required = 1;
                    } else {
                        $required = 0;
                    }

                    $question = new Question;
                    $question->survey_id = $survey->id;
                    $question->question = $questions[$key];
                    $question->type = $types[$key];
                    $question->is_required = $required;

                    if (@$images[$key]) {
                        $folder = $this->path.'/'.$survey->id;

                        if (!File::isDirectory($folder)) {
                            File::makeDirectory($folder, 0777, TRUE);
                        }

                        $image = $images[$key];
                        $file_image = $current.'_'.$key.'_'.$image->getClientOriginalName();
                        $image->move($folder, $file_image);
                        $question->image = $file_image;
                    }

                    $question->save();

                    if (in_array($types[$key], ['MULTIPLE_CHOICE_CHECK', 'MULTIPLE_CHOICE_RADIO'])) {
                        $answers = $data->answer[$key];

                        if (is_array($answers)) {
                            foreach ($answers as $keyOption => $valueOption) {
                                $choice = new MultipleChoice;
                                $choice->question_id = $question->id;
                                $choice->answer = $answers[$keyOption];
                                $choice->save();
                            }
                        }
                    }
                }
            }

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            Alert::error('Error', $ex->getMessage());            
        }

        return redirect()->route('surveys');
    }

    public function update(Request $data, $id)
    {
        // $data = $request->all();

        $validator = Validator::make($data->all(), [
            'activity_id' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['required'],
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());

            return redirect()->back();
        }

        if ($data->is_active == 'on') {
            $data->is_active = 1;
        } else {
            $data->is_active = 0;
        }

        DB::beginTransaction();

        try {
            $current = strtotime(Carbon::now());
            $folder = $this->path.'/'.$id;

            $survey = Survey::find($id);
            $survey->activity_id = $data->activity_id;
            $survey->title = $data->title;
            $survey->description = $data->description;
            $survey->is_active = $data->is_active;
            $survey->due_date = $data->due_date;
            $survey->save();

            // update question
            $u_question_ids = @$data->u_question_id;
            $u_questions = @$data->u_question;
            $u_images = @$data->u_image;
            $u_types = @$data->u_type;
            $u_requires = @$data->u_is_required;

            if (is_array($u_question_ids)) {
                // update
                foreach ($u_question_ids as $keyUpdate => $valueUpdate) {
                    if (@$u_requires[$keyUpdate] == 'on') {
                        $u_required = 1;
                    } else {
                        $u_required = 0;
                    }

                    $u_question = Question::find($u_question_ids[$keyUpdate]);
                    $u_question->survey_id = $id;
                    $u_question->question = $u_questions[$keyUpdate];
                    $u_question->type = $u_types[$keyUpdate];
                    $u_question->is_required = $u_required;

                    if (@$images[$key]) {
                        if (!File::isDirectory($folder)) {
                            File::makeDirectory($folder, 0777, TRUE);
                        }

                        $u_image = $u_images[$keyUpdate];
                        $file_u_image = $current.'_'.$keyUpdate.'_'.$u_image->getClientOriginalName();
                        $u_image->move($folder, $file_u_image);
                        $u_question->image = $file_u_image;
                    }

                    $u_question->save();

                    if (in_array($u_types[$keyUpdate], ['MULTIPLE_CHOICE_CHECK', 'MULTIPLE_CHOICE_RADIO'])) {
                        $u_choice_ids = @$data->u_choice_id[$keyUpdate];
                        $u_answers = @$data->u_answer[$keyUpdate];
                        $multiple = [];

                        if (is_array($u_choice_ids)) {
                            foreach ($u_choice_ids as $keyOptionUpdate => $valueOptionUpdate) {
                                $u_choice = MultipleChoice::find($valueOptionUpdate);
                                $u_choice->question_id = $u_question->id;
                                $u_choice->answer = $u_answers[$keyOptionUpdate];
                                $u_choice->save();
                                $multiple[] = $valueOptionUpdate;
                            }
                        }

                        // delete multiple choice
                        MultipleChoice::where('question_id', $u_question->id)->whereNotIn('id', $multiple)->delete();

                        $a_answers = @$data->answer[$keyUpdate];

                        if (is_array($a_answers)) {
                            foreach ($a_answers as $keyOptionAdd => $valueOptionAdd) {
                                $a_choice = new MultipleChoice;
                                $a_choice->question_id = $u_question->id;
                                $a_choice->answer = $valueOptionAdd;
                                $a_choice->save();
                            }
                        }
                    } else {
                        // delete multiple choice
                        MultipleChoice::where('question_id', $u_question->id)->delete();
                    }
                }

                // delete question
                Question::whereNotIn('id', $u_question_ids)->where('survey_id', $survey->id)->delete();
            }
            
            // update add option
            $u_a_question_ids = @$data->u_a_question_id;

            if (is_array($u_a_question_ids)) {
                foreach ($u_a_question_ids as $keyAddUpdate => $valueAddUpdate) {
                    $u_a_answers = @$data->u_a_answer[$keyAddUpdate];

                    foreach ($u_a_answers as $keyAddUpdateAnswer => $valueAddUpdateAnswer) {
                        $u_a_choice = new MultipleChoice;
                        $u_a_choice->question_id = $valueAddUpdate;
                        $u_a_choice->answer = $valueAddUpdateAnswer;
                        $u_a_choice->save();
                    }
                }
            }

            // delete multiple choice
            DB::table('DELETE FROM multiple_choices WHERE question_id in (SELECT id FROM questions WHERE type = "ESSAY")');

            // add new question
            $questions = @$data->question;
            $images = @$data->image;
            $types = @$data->type;
            $requires = @$data->is_required;

            if (is_array($questions)) {
                foreach ($questions as $key => $value) {
                    if ($requires[$key] == 'on') {
                        $required = 1;
                    } else {
                        $required = 0;
                    }

                    $question = new Question;
                    $question->survey_id = $id;
                    $question->question = $questions[$key];
                    $question->type = $types[$key];
                    $question->is_required = $required;

                    if (@$images[$key]) {
                        if (!File::isDirectory($folder)) {
                            File::makeDirectory($folder, 0777, TRUE);
                        }

                        $image = $images[$key];
                        $file_image = $current.'_'.$key.'_'.$image->getClientOriginalName();
                        $image->move($folder, $file_image);
                        $question->image = $file_image;
                    }

                    $question->save();

                    if (in_array($types[$key], ['MULTIPLE_CHOICE_CHECK', 'MULTIPLE_CHOICE_RADIO'])) {
                        $answers = @$data->answer[$key];

                        if (is_array($answers)) {
                            foreach ($answers as $keyOption => $valueOption) {
                                $choice = new MultipleChoice;
                                $choice->question_id = $question->id;
                                $choice->answer = $answers[$keyOption];
                                $choice->save();
                            }
                        }
                    }
                }
            }

            DB::commit();
            Alert::success('Success', 'Survey updated successfully');
        } catch (Exception $ex) {
            DB::rollBack();

            Alert::error('Error', $ex->getMessage());
        }

        return redirect()->back();
        // return redirect()->route('surveys');
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            Survey::find($id)->delete();

            DB::commit();
            Alert::success('Success', 'Survey deleted successfully');
        } catch (Exception $ex) {
            DB::rollBack();

            $err = $ex->getMessage();
            Alert::error('Error', $err);
        }

        return redirect()->back();
    }

    // public function listActivitySurvey(Request $request)
    // {
    //     $userId = Auth::user()->id;
    //     $data = $this->surveyService->getAllMySurvey($userId);
    //     $today = date('Y-m-d');

    //     $data = DB::select('
    //         SELECT DISTINCT s.*, e.title as activity FROM surveys as s 
    //         JOIN activities as e ON e.id = s.activity_id 
    //         WHERE s.is_active = TRUE 
    //         AND s.due_date >= ? 
    //         AND NOT EXISTS (SELECT * FROM respondens WHERE survey_id = s.id AND user_id = ?)
    //     ', array($today, $userId));

    //     if ($request->ajax()) {
    //         return Datatables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('due_date', function($data) {
    //                 return @tanggal($data->due_date);
    //             })
    //             ->addColumn('action', function ($data){
    //                 return '
    //                     <a href="'.e(route('fill.event.survey', $data->id)).'" class="btn btn-primary btn-sm" title="Fill  Survey"><span class="fas fa-pencil-alt"></span></a>
    //                 ';
    //             })
    //             ->toJson();
    //     }

    //     return view('cpanel.contents.surveys.my_survey', get_defined_vars());
    // }

    public function fillActivitySurvey($id)
    {
        $data = Survey::where('activity_id', $id)->first();

        if (!$data) {
            Alert::error('Error', 'Survey pada kegiatan ini belum dibuat');

            return redirect()->back();
        }

        return view('cpanel.contents.surveys.fill_survey', get_defined_vars());
    }

    public function storeActivitySurvey(Request $data)
    {
        // $data = $request->all();

        $validator = Validator::make($data->all(), [
            'survey_id' => ['required'],
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());

            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            $responden = new Responden;
            $responden->survey_id = $data->survey_id;
            $responden->user_id = Auth::user()->id;
            $responden->save();

            $params = [];
            $questions = @$data->question_id;
            $answers = @$data->answers;

            foreach ($answers as $key => $value) {
                foreach ($value as $val) {
                    $params[] = [
                        'responden_id' => $responden->id,
                        'question_id' => $questions[$key],
                        'answer' => $val
                    ];
                }
            }

            Answer::insert($params);

            DB::commit();
            Alert::success('Success', 'Berhasil mengisi survey');
        } catch (Exception $ex) {
            DB::rollBack();

            Alert::error('Error', $ex->getMessage());
        }

        return redirect()->route('activity.operator');
    }

    public function showActivitySurvey($id)
    {
        $data = Survey::where('activity_id', $id)->first();

        if (!$data) {
            Alert::error('Error', 'Survey pada kegiatan ini belum dibuat');

            return redirect()->back();
        }

        return view('cpanel.contents.surveys.my_survey', get_defined_vars());
    }
}
