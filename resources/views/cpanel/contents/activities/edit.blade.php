@section('content')
<form method="POST" action="{{ route('update.activity', $data->id) }}">
@csrf
@method('PUT')

	<div class="modal-body">
		<label>Tipe Kegiatan <strong style="color: red;">*</strong></label>
		<div class="form-group">
			<select name="activity_type" class="form-control select2" required>
				<option value="">Pilih Tipe Kegiatan</option>
				@foreach($types as $value)
					<option @if($value->id == $data->activity_type) selected @endif value="{{ $value->id }}">{{ $value->name }}</option>
				@endforeach
			</select>
		</div>

		<label>Nama Kegiatan <strong style="color: red;">*</strong></label>
		<div class="form-group">
			<input type="text" class="form-control" name="name" value="{{ $data->name }}" required>
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