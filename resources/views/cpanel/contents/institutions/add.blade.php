@section('content')
<form method="POST" action="{{ route('store.institution') }}">
@csrf

	<div class="modal-body">

		<label>Kode <strong style="color: red;">*</strong></label>
		<div class="form-group">
			<input type="text" class="form-control" name="code" required>
		</div>

		<label>Nama <strong style="color: red;">*</strong></label>
		<div class="form-group">
			<input type="text" class="form-control" name="name" required>
		</div>

		<label>No. Telepon</label>
		<div class="form-group">
			<input type="text" class="form-control" name="telephone">
		</div>

		<label>Email</label>
		<div class="form-group">
			<input type="email" class="form-control" name="email">
		</div>

		<label>Website</label>
		<div class="form-group">
			<input type="text" class="form-control" name="website">
		</div>

		<label>Alamat</label>
		<div class="form-group">
			<input type="text" class="form-control" name="address">
		</div>

		<label>Kode Pos</label>
		<div class="form-group">
			<input type="text" class="form-control" name="postal_code">
		</div>

		<label><strong>Program Studi</strong></label>
		<div class="form-group">
			<div class="input-group">
				<input type="text" class="form-control" name="major_names[]" placeholder="Nama Program Studi" required> &nbsp;
				<input type="text" class="form-control" name="major_codes[]" placeholder="Kode Program Studi" required> &nbsp;
				<input type="checkbox" name="is_active[]" checked data-bootstrap-switch data-off-color="danger" data-on-color="success"> &nbsp;
				<div class="input-group-prepend" style="vertical-align: middle;">
					<span>
						<a href="void::javascript(0);" id="addmajor" class="btn btn-success">
							<i class="fas fa-plus"></i>
						</a>
					</span>
				</div>
			</div>
		</div>

		<div id="majors">
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

	$("#addmajor").on("click", function (e) {
		e.preventDefault();
		var newRow = $("<p>");
		var cols = "";

		// html
		// cols = '<div class="form-group"> <div class="input-group"> <input type="file" class="form-control" style="border: none;" name="subject[]"> <div class="input-group-prepend"> <span style=""> <a href="void::javascript(0);" class="btn btn-danger ibtnDel"> <i class="fas fa-trash"></i> </a> </span> </div> </div> </div>';

		cols += '<div class="form-group"> <div class="input-group"> <input type="text" class="form-control" name="major_names[]" placeholder="Nama Program Studi"> &nbsp; <input type="text" class="form-control" name="major_codes[]" placeholder="Kode Program Studi"> &nbsp; <input type="checkbox" name="is_active[]" checked data-bootstrap-switch data-off-color="danger" data-on-color="success"> &nbsp; <div class="input-group-prepend" style="vertical-align: middle;"> <span> <a href="void::javascript(0);" class="btn btn-danger ibtnDel"> <i class="fas fa-trash"></i> </a> </span> </div> </div> </div><script> $(document).ready(function(){ $("input[data-bootstrap-switch]").each(function(){ $(this).bootstrapSwitch("state", $(this).prop("checked")); }); }); <' + '/' + 'script>';

		newRow.append(cols);
		$("#majors").append(newRow);
	});

	$("#majors").on("click", ".ibtnDel", function (event) {
		event.preventDefault();
		$(this).closest("p").remove();
	});
</script>
@endsection