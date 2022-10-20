@extends('cpanel.layouts.main')

@section('css')

@endsection

@section('content')
<form method="GET" action="">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<strong>Perguruan Tinggi</strong>

							<select name="institution" class="form-control" id="institution" data-placeholder="Pilih Perguruan Tinggi ..."> 
								<option @if ($request->get('institution') == 'semua') selected @endif value="semua">Semua</option>
								@foreach($dropdownInstitution as $value)
									<option @if ($request->get('institution') == $value->id) selected @endif value="{{ $value->id }}">{{ $value->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
			</div>

			<div class="card-footer">
				<div style="float: right;">
					<button type="submit" class="btn btn-primary"><span class="fa fa-search"></span>&nbsp; Cari</button>
				</div>
			</div>
		</div>
	</div>
</form>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title text-primary"><span class="fas fa-tasks"></span>&nbsp; Response {{ $data->title }}</h3>
			</div>

			<div class="card-body">
				@foreach($data->question as $key => $question)
					<div class="row">
						<div class="col-md-1">
							{{ $key+1 }}
						</div>
						
						<div class="col-md-11">
							{{ $question->question }} @if ($question->is_required) <span style="color: red;">*</span> @endif
						</div>

						<div class="col-md-1"></div>
						<div class="col-md-11">
							@if ($question->type == 'ESSAY')
								@foreach($question->answer as $keyAnswer => $questionAnswer)
									@if ($request->get('institution') == 'semua' || !$request->get('institution'))
										<input type="text" class="form-control" readonly value="{{ $questionAnswer->answer }} [{{ @$questionAnswer->responden->user->institution()->first()->name }}]">
									@else
										@if (@$questionAnswer->responden->user->institution()->first()->id == $request->get('institution'))
											<input type="text" class="form-control" readonly value="{{ $questionAnswer->answer }} [{{ @$questionAnswer->responden->user->institution()->first()->name }}]">
										@endif
									@endif
								@endforeach
							@endif

							@if ($question->type == 'MULTIPLE_CHOICE_RADIO' || $question->type == 'MULTIPLE_CHOICE_CHECK')
                                <canvas id="multiple-choice-chart-{{ $key }}" height="50"></canvas>
							@endif
							
							{{-- 
							@if ($question->type == 'MULTIPLE_CHOICE_CHECK')
								@foreach($question->answer as $keyAnswer => $questionAnswer)
                                <canvas id="multiple-choice-chart-{{ $key }}" height="50"></canvas>
								@endforeach
							@endif
							 --}}
						</div>
					</div>

					<hr>
				@endforeach
			</div>
		</div>
	</div>
</div>
@endsection

@section('javascript')
	<script src="{{ asset('admin/assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/chart.js/dist/Chart.min.js') }}"></script>
    <script>
		function random_rgba() {
			var o = Math.round, r = Math.random, s = 255;
			return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',' + r().toFixed(1) + ')';
		}

		$(document).ready(function() {
	        $('#institution').select2({
		        placeholder: 'Pilih Perguruan Tinggi ...',
		        width: '100%',
	            theme: 'bootstrap4',
	        });
		});
    </script>

    @foreach($data->question as $keyJs => $questionJs)
    <script>
    	@if ($questionJs->type == 'MULTIPLE_CHOICE_RADIO' || $questionJs->type = 'MULTIPLE_CHOICE_CHECK')
    		<?php
    			$labels = [];
    			$data = [];

    			if ($request->get('institution') == 'semua' || !$request->get('institution')) {
	    			foreach ($questionJs->multipleChoice as $label) {
	    				$labels[] = $label->answer;

	    				$answer = \DB::select('select count(answer) as count from answers where question_id = ? and answer = ? group by answer', array($questionJs->id, $label->answer));

	    				$data[] = (@$answer[0]) ? $answer[0]->count : 0;
	    			}
    			} else {
	    			foreach ($questionJs->multipleChoice as $label) {
	    				$labels[] = $label->answer;

	    				$answer = \DB::select('select count(ans.answer) as count from answers as ans join respondens as res on res.id = ans.responden_id join users as u on u.id = res.user_id join user_institution as ui on u.id = ui.user_id where ui.institution_id = ? and ans.question_id = ? and ans.answer = ? group by ans.answer', array($request->get('institution'), $questionJs->id, $label->answer));

	    				$data[] = (@$answer[0]) ? $answer[0]->count : 0;
	    			}
    			}
    		?>

    		var labels = <?= json_encode($labels) ?>;
    		var colors = [];
    		for (var i = 0; i < labels.length; i++) {
    			colors.push(random_rgba());
    		}

    		var data = <?= json_encode($data) ?>;

	    	new Chart(document.getElementById("multiple-choice-chart-{{ $keyJs }}"), {
				type: 'pie',
				data: {
				  labels: labels,
				  datasets: [{
					// label: "Population (millions)",
					backgroundColor: colors,
					data: data
				  }]
				},
				// options: {
				//   title: {
				// 	display: true,
				// 	text: 'Predicted world population (millions) in 2050'
				//   }
				// }
			});
    	@endif
    </script>
    @endforeach
@endsection