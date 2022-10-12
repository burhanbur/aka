@extends('cpanel.layouts.main')

@section('css')
    <link href="{{ asset('admin/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="modal fade" id="modalMd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title" id="modalMdTitle"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
              <div class="modalError"></div>
              <div id="modalMdContent"></div>
          </div>
      </div>
  </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title" id="modalEditTitle"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
              <div class="modalError"></div>
              <div id="modalEditContent"></div>
          </div>
      </div>
  </div>
</div>

<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title" id="modalCreateTitle"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
              <div class="modalError"></div>
              <div id="modalCreateContent"></div>
          </div>
      </div>
  </div>
</div>

<div class="row">
	<div class="col-md-12">
      	<div class="card">
          	<div class="card-header">
          		<div style="float: left;">
          			<a href="#" value="{{ route('create.user') }}" class="btn btn-success modalCreate" title="Create User" data-toggle="modal" data-target="#modalCreate"><i class="fas fa-user-plus"></i> &nbsp;Tambah Operator</a>
          			
          		</div>
            	<h3 class="card-title text-primary" style="float: right;">
            		<i class="fas fa-user"></i> Daftar Operator
            	</h3>
        	</div>

			<div class="card-body">
				<table id="table" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th style="width: 5%" class="center">No</th>
							<th class="center">Username</th>
							<th class="center">Email</th>
							<th class="center">Perguruan Tinggi</th>
							<th class="center"></th>
						</tr>
					</thead>
				</table>
			</div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script src="{{ asset('admin/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
    <script>
		$(document).ready(function(){

			$('#event_member').DataTable();

			$('#table').DataTable({
				processing: true,
				serverSide: true,
				ajax: '{!! route('users') !!}',
				columns: [
		            { 
		                data: 'DT_RowIndex',
		                name: 'DT_RowIndex',
		                class: 'center',
		                orderable: false,
		                searchable: false
		            },
		            {
		                data: 'username',
		                name: 'username'
		            },
		            {
		                data: 'email',
		                name: 'email'
		            },
		            {
		                data: 'institution',
		                name: 'institution'
		            },
		            {
		                data: 'action',
		                name: 'action',
		                class: 'center',
		                orderable: false,
		                searchable: false
		            }
		        ]
			});
		});

		setInterval(function(){ 
		    $('.modalMd').off('click').on('click', function () {
		      $('#modalMd').modal({backdrop: 'static', keyboard: false}) 
		      
		        $('#modalMdContent').load($(this).attr('value'));
		        $('#modalMdTitle').html($(this).attr('title'));
		    });

		    $('.modalEdit').off('click').on('click', function () {
		      $('#modalEdit').modal({backdrop: 'static', keyboard: false}) 
		      
		        $('#modalEditContent').load($(this).attr('value'));
		        $('#modalEditTitle').html($(this).attr('title'));
		    });	    

		    $('.modalCreate').off('click').on('click', function () {
		      $('#modalCreate').modal({backdrop: 'static', keyboard: false}) 
		      
		        $('#modalCreateContent').load($(this).attr('value'));
		        $('#modalCreateTitle').html($(this).attr('title'));
		    });
		  }, 500);
    </script>
@endsection