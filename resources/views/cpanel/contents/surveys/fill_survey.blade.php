@extends('cpanel.layouts.main')

@section('css')

@endsection

@section('content')
<form method="POST" action="{{ route('store.activity.survey') }}">
	@csrf
	<input type="hidden" name="survey_id" value="{{ $data->id }}">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title text-primary"><i class="fas fa-chart-area"></i> &nbsp;{{ $data->title }}</h3>
				</div>

				<div class="card-body">
					<p style="text-align: justify;">{{ $data->description }}</p>
					<hr>
					@foreach($data->question as $key => $question)
					<input type="hidden" name="question_id[{{$key}}]" value="{{ $question->id }}">
						<div class="row">
							<div class="col-md-1">
								{{ $key+1 }}
							</div>

							<div class="col-md-11">
								{{ $question->question }} @if ($question->is_required) <span style="color: red;">*</span> @endif

								@if ($question->image)
				                    <br><br>
				                    <img style="width: auto; height: 150px;" src="{{ asset('files/surveys/'.$data->id.'/'.$question->image) }}">
								@endif
							</div>

							<div class="col-md-1"></div>
							<div class="col-md-11">
								@if ($question->type == 'ESSAY')
									<div class="form-group">
										<input type="text" name="answers[{{ $key }}][]" class="form-control" placeholder="Fill your answer here ..." @if ($question->is_required) required @endif>
									</div>
								@elseif ($question->type == 'MULTIPLE_CHOICE_RADIO')
									@foreach($question->multipleChoice as $keyOption => $questionOption)
                                    <div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" id="customControlValidation{{ $key }}_{{ $keyOption }}" name="answers[{{ $key }}][]" @if ($question->is_required) required @endif value="{{ $questionOption->answer }}">
										<label class="custom-control-label" for="customControlValidation{{ $key }}_{{ $keyOption }}">{{ $questionOption->answer }}</label>
									</div>
									@endforeach
								@elseif ($question->type == 'MULTIPLE_CHOICE_CHECK')
									<div class="checkbox-group-{{ $key }}">
										@foreach($question->multipleChoice as $keyOption => $questionOption)
	                                    <div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" name="answers[{{ $key }}][]" id="customCheck{{ $key }}_{{ $keyOption }}" value="{{ $questionOption->answer }}"> 
											<label class="custom-control-label" for="customCheck{{ $key }}_{{ $keyOption }}">{{ $questionOption->answer }}</label>
										</div>
										@endforeach
									</div>
								@endif
							</div>
						</div>

						<hr>
					@endforeach
				</div>

				<div class="card-footer">
					<div class="form-group" style="float: right;">
						<button class="btn btn-primary" type="submit" id="submit"><i class="fa fa-save"></i> Submit</button>
						<a class="btn btn-secondary" href="{{ route('activity.operator') }}"><i class="fa fa-undo"></i> Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
@endsection

@section('javascript')
	<script>
		var verify = function() {
			<?php foreach ($data->question as $keyJs => $questionJs) { 
				if ($questionJs->type == 'MULTIPLE_CHOICE_CHECK') { ?>
				var checkboxes_{{$keyJs}} = $('.checkbox-group-{{$keyJs}} .custom-checkbox');
				var inputs_{{$keyJs}} = checkboxes_{{$keyJs}}.find('input');
				var first_{{$keyJs}} = inputs_{{$keyJs}}.first()[0];

				inputs_{{$keyJs}}.on('change', function () {
					this.setCustomValidity('');
				});

				first_{{$keyJs}}.setCustomValidity(checkboxes_<?= $keyJs ?>.find('input:checked').length === 0 ? 'Please select one of these required option' : '');
			 
			<?php }
			} ?>
		}

		$('#submit').click(verify);
	</script>
@endsection