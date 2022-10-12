@section('content')

    <style>
    	test {
    		display: block;
		    margin-block-start: 1em;
		    margin-block-end: 1em;
		    margin-inline-start: 0px;
		    margin-inline-end: 0px;
    	}
    </style>

<form method="POST" action="{{ route('update.institution.major', $data->id) }}">
@csrf
@method('PUT')

	<div class="modal-body">

		<label>Kode <strong style="color: red;">*</strong></label>
		<div class="form-group">
			<input type="text" class="form-control" name="code" value="{{ $data->code }}" required>
		</div>

		<label>Nama <strong style="color: red;">*</strong></label>
		<div class="form-group">
			<input type="text" class="form-control" name="name" value="{{ $data->name }}" required>
		</div>

		<label>Status <strong style="color: red;">*</strong></label>
		<div class="form-group">
	        <input type="checkbox" name="is_active" @if ($data->is_active) checked @endif data-bootstrap-switch data-off-color="danger" data-on-color="success">
		</div>

		</div>
	</div>

	<div class="modal-footer">
		<div class="form-group">
			<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Simpan</button>
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><i class="fa fa-undo"></i> Tutup</button>
		</div>
	</div>
</form>


<script>
	$(document).ready(function(){
		$("input[data-bootstrap-switch]").each(function(){
			$(this).bootstrapSwitch('state', $(this).prop('checked'));
		});
	});
</script>
@endsection