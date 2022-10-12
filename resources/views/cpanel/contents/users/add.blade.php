@section('content')
<form method="POST" action="{{ route('store.user') }}">
@csrf

	<div class="modal-body">
		<label>Username <strong style="color: red;">*</strong></label>
		<div class="form-group">
			<input type="text" class="form-control" name="username" required>
		</div>

		<label>Email <strong style="color: red;">*</strong></label>
		<div class="form-group">
			<input type="email" class="form-control" name="email" required>
		</div>

		<label>Password <strong style="color: red;">*</strong></label>
		<div class="form-group">
			<input type="password" class="form-control" name="password" required>
		</div>

		<label>Perguruan Tinggi <strong style="color: red;">*</strong></label>
		<div class="form-group">
			<select class="form-control select2" id="institution_id" name="institution_id" required>
				<option value="">Pilih Perguruan Tinggi</option>
				@foreach($institutions as $value)
					<option value="{{ $value->id }}">{{ $value->code }} - {{ $value->name }}</option>
				@endforeach
			</select>
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
	$(document).ready(function() {
        $('#institution_id').select2({
	        placeholder: 'Pilih Perguruan Tinggi',
	        width: '100%',
            theme: 'bootstrap4',
        });
	});
</script>
@endsection