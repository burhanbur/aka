@extends('cpanel.layouts.main')

@section('css')
    <link href="{{ asset('admin/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
	<link href="{{ asset('admin/assets/libs/bootstrap-datepicker/datepicker.css') }}" rel="stylesheet" />

    <style>
    	test {
    		display: block;
		    margin-block-start: 1em;
		    margin-block-end: 1em;
		    margin-inline-start: 0px;
		    margin-inline-end: 0px;
    	}
    </style>
@endsection

@section('content')
<form action="{{ route('store.survey') }}" method="POST" enctype="multipart/form-data">
	@csrf
	<div class="card">
		<div class="card-header">
			<h3 class="card-title text-primary">Create New Survey</h3>	
		</div>

		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<select name="activity_id" class="form-control" id="activity" required>
							<option value=""></option>
							@foreach ($activities as $activity)
								<option value="{{ $activity->id }}">{{ $activity->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="title" placeholder="Judul survey ..." required>
					</div>
					<div class="form-group">
						<textarea name="description" class="form-control" placeholder="Form deskripsi ..."></textarea>
					</div>

					<div class="row">
						<div class="col-md-6">
							<strong>Due Date</strong>
								<div class="form-group">
									<div class="form-group">
									    <div class="input-group">
									        <div class="input-group-prepend">
									            <span class="input-group-text"
									                ><i class="far fa-calendar-alt"></i
									            ></span>
									        </div>
									        <input
									            type="text"
									            class="form-control datepicker5"
									            name="due_date"
									            data-date-format="yyyy-mm-dd" 
									            placeholder="2022-12-31"
									            required
									        />
									    </div>
									</div>
								</div>
						</div>
						<div class="col-md-6">
							<strong>Status Active</strong>
							<div class="form-group">
					            <input type="checkbox" name="is_active" checked data-bootstrap-switch data-off-color="danger" data-on-color="success">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<!-- list questions -->
			<div id="questions">
			</div>

			<div class="form-group" style="float: right;">
				<a href="" class="btn btn-sm btn-success" id="addquestion">+ Add Question</a>
			</div>
		</div>

		<div class="card-footer">
			<div class="form-group" style="">
				<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Submit</button>
				<a class="btn btn-secondary" href="{{ route('surveys') }}"><i class="fa fa-undo"></i> Back</a>
			</div>
		</div>
	</div>
</form>
@endsection

@section('javascript')
<script src="{{ asset('admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script>
	$(document).ready(function() {
		$(".datepicker5").datepicker({
	      autoclose: true,
	    });

		$("input[data-bootstrap-switch]").each(function(){
			$(this).bootstrapSwitch('state', $(this).prop('checked'));
		});

        $('#activity').select2({
	        placeholder: 'Pilih Kegiatan ...',
	        width: '100%',
            theme: 'bootstrap4',
        });
	});

    // question
    var counter = 0;

	$("#addquestion").on("click", function (e) {
		e.preventDefault();
		var newRow = $("<p>");
		var cols = "";

		// html
		cols += '<div class="row"> <div class="col-md-12"> <div class="card"> <div class="card-header"> <div style="float: right;"> <a href="" class="btn btn-danger btn-sm delData"> <i class="fa fa-trash"></i> Delete </a> </div> </div> <div class="card-body"> <div class="row"> <div class="col-md-8"> <div class="form-group"> <textarea name="question['+counter+']" class="form-control" placeholder="Question Text ..." required></textarea> </div> <strong>Answer</strong> <div id="essay'+counter+'"></div> <div id="multiple_choice'+counter+'"> <div id="show_option'+counter+'"></div> </div> </div> <div class="col-md-4"> <div class="form-group"> <select class="form-control" id="type'+counter+'" name="type['+counter+']" required> <option value=""></option> <option value="MULTIPLE_CHOICE_RADIO">MULTIPLE CHOICE RADIO</option> <option value="MULTIPLE_CHOICE_CHECK">MULTIPLE CHOICE CHECK</option> <option value="ESSAY">ESSAY</option> </select> </div> <strong>Upload Image</strong> <div class="form-group"> <input type="file" name="image['+counter+']" accept="image/png, image/jpg, image/jpeg"> </div> <strong>Required</strong> <div class="form-group"> <input type="checkbox" name="is_required['+counter+']" checked data-bootstrap-switch data-off-color="danger" data-on-color="success"> </div> </div> </div> </div> </div> </div> </div>';

		// javascript
		cols += '<script>';

		cols += '$(document).ready(function() { $("input[data-bootstrap-switch]").each(function(){ $(this).bootstrapSwitch("state", $(this).prop("checked")); }); $("#type'+counter+'").select2({ placeholder: "Select Question Type ...", width: "100%", theme: "bootstrap4", }); });';

		// function check type
		cols += '$("#type'+ counter + '").on("change", function() { var type = $(this).val();';

		cols += 'if (type == "MULTIPLE_CHOICE_CHECK" || type == "MULTIPLE_CHOICE_RADIO") {';
		cols += '$("#multiple_choice'+counter+'").html('+"'"+'<div id="show_option'+counter+'"><test>  </test> </div> <div class="form-group"> <a href="javascript:void(0)" class="btn btn-sm btn-warning" onClick="return addOption('+counter+');">+ Add Option</a></div>'+"'"+');';

		cols += '$("#essay'+counter+'").empty();';
		cols += '}';

		cols += 'if (type == "ESSAY") {';
		cols += '$("#multiple_choice'+counter+'").empty();';
		cols += '$("#essay'+counter+'").html('+"'"+'<div class="form-group"> <input type="text" class="form-control" value="Short answer text" readonly> </div>'+"'"+');';
		cols += '}';

		cols += '});';

		cols += '<' + '/' + 'script>';

		newRow.append(cols);
		$("#questions").append(newRow);
		counter++;
	});

	$("#questions").on("click", ".delData", function (event) {
		event.preventDefault();
		$(this).closest("p").remove();       
		// counter -= 1
	});

	// option
	var counterOption = 0;

	function addOption(counter) {
		var newRow = $("<test>");
		var cols = "";

		cols += '<div class="form-group"> <div class="input-group"> <input type="text" name="answer['+counter+'][]" class="form-control" placeholder="Option" required> <div class="input-group-prepend"> <span class="input-group-text"><a href="javascript:void(0)" onClick="return deleteOption('+counter+','+counterOption+');" class="ibtnDel' + counter + '_'+counterOption+'"><i class="fa fa-trash"></i></a></span> </div> </div> </div>';

		newRow.append(cols);
		$("#show_option"+counter).append(newRow);
		counterOption++;
	};

	function deleteOption(counter, counterOption) {
		$('.ibtnDel' + counter + '_' + counterOption).closest("test").remove();
		// counterOption -= 1
	}
</script>
@endsection