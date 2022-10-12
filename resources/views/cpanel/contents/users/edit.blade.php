@section('content')
<form method="POST" action="{{ route('update.user', $data->id) }}">
@csrf
@method('PUT')
	<input type="hidden" name="institution_id" value="{{ @$data->institution()->first()->id }}">
	<div class="modal-body">
		<label>Username</label>
		<div class="form-group">
			<input type="text" class="form-control" value="{{ $data->username }}" readonly>
		</div>

		<label>Email</label>
		<div class="form-group">
			<input type="email" class="form-control" value="{{ $data->email }}" readonly>
		</div>

		<label>Perguruan Tinggi <strong style="color: red;">*</strong></label>
		<div class="form-group">
			<input type="text" class="form-control" name="institution_name" value="{{ @$data->institution()->first()->name }}" required>
		</div>

		<label>Kode PT <strong style="color: red;">*</strong></label>
		<div class="form-group">
			<input type="text" class="form-control" name="institution_code" value="{{ @$data->institution()->first()->code }}" required>
		</div>

		<label>No. Telepon PT</label>
		<div class="form-group">
			<input type="text" class="form-control" name="institution_telephone" value="{{ @$data->institution()->first()->telephone }}">
		</div>

		<label>Email PT</label>
		<div class="form-group">
			<input type="text" class="form-control" name="institution_email" value="{{ @$data->institution()->first()->email }}">
		</div>

		<label>Website PT</label>
		<div class="form-group">
			<input type="text" class="form-control" name="institution_website" value="{{ @$data->institution()->first()->website }}">
		</div>

		<label>Alamat PT</label>
		<div class="form-group">
			<input type="text" class="form-control" name="institution_address"  value="{{ @$data->institution()->first()->address }}">
		</div>

		<label>Kode Pos PT</label>
		<div class="form-group">
			<input type="text" class="form-control" name="institution_postal_code" value="{{ @$data->institution()->first()->postal_code }}">
		</div>
	</div>

	<div class="modal-footer">
		<div class="form-group">
			<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Simpan</button>
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><i class="fa fa-undo"></i> Tutup</button>
		</div>
	</div>
</form>
@endsection