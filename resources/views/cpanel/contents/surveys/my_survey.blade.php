@extends('cpanel.layouts.main')

@section('css')

@endsection

@section('content')

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
									@if ($questionAnswer->responden->user_id == Auth::user()->id)
										<input type="text" class="form-control" readonly value="{{ $questionAnswer->answer }}">
									@endif
								@endforeach
							@endif

							@if ($question->type == 'MULTIPLE_CHOICE_RADIO')
                                <canvas id="multiple-choice-chart-{{ $key }}" height="50"></canvas>
							@endif
							
							@if ($question->type == 'MULTIPLE_CHOICE_CHECK')
								@foreach($question->answer as $keyAnswer => $questionAnswer)
	                                <canvas id="multiple-choice-chart-{{ $key }}" height="50"></canvas>
								@endforeach
							@endif
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
    </script>

    @foreach($data->question as $keyJs => $questionJs)
    <script>
    	@if ($questionJs->type == 'MULTIPLE_CHOICE_RADIO' || $questionJs->type = 'MULTIPLE_CHOICE_CHECK')
    		<?php
    			$labels = [];
    			$data = [];

    			foreach ($questionJs->multipleChoice as $label) {
    				$labels[] = $label->answer;

    				$answer = \DB::select('select count(a.answer) as count from answers as a join respondens as r on r.id = a.responden_id where r.user_id = ? and a.question_id = ? and a.answer = ? group by a.answer', array(Auth::user()->id, $questionJs->id, $label->answer));

    				$data[] = (@$answer[0]) ? $answer[0]->count : 0;
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