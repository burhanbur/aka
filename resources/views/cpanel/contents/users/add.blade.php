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
			<input type="text" class="form-control" name="institution_name" required>
		</div>

		<label>Kode PT <strong style="color: red;">*</strong></label>
		<div class="form-group">
			<input type="text" class="form-control" name="institution_code" required>
		</div>

		<label>No. Telepon PT</label>
		<div class="form-group">
			<input type="text" class="form-control" name="institution_telephone">
		</div>

		<label>Email PT</label>
		<div class="form-group">
			<input type="text" class="form-control" name="institution_email">
		</div>

		<label>Website PT</label>
		<div class="form-group">
			<input type="text" class="form-control" name="institution_website">
		</div>

		<label>Alamat PT</label>
		<div class="form-group">
			<input type="text" class="form-control" name="institution_address">
		</div>

		<label>Kode Pos PT</label>
		<div class="form-group">
			<input type="text" class="form-control" name="institution_postal_code">
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