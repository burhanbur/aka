@extends('cpanel.layouts.main')

@section('css')
    <link href="{{ asset('admin/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
      	<div class="card">
          	<div class="card-header">
            	<h3 class="card-title text-primary" style="float: right;">
            		<i class="fas fa-user"></i> Daftar Kegiatan
            	</h3>
        	</div>

			<div class="card-body">
				<table id="table" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th style="width: 5%" class="center">No</th>
							<th class="center">Kegiatan</th>
							<th class="center">Jenis</th>
							<th class="center"></th>
						</tr>
					</thead>
					{{--<tbody>
						@php $no = 1; @endphp
						@foreach($data as $value)
							<tr>
								<td class="center">{{ $no++ }}</td>
								<td>{{ $value->name }}</td>
								<td>{{ $value->activityType->name }}</td>
							</tr>
						@endforeach
					</tbody> --}}
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

			$('#table').DataTable({
				processing: true,
				serverSide: true,
				ajax: '{!! route('activity.operator') !!}',
				columns: [
		            { 
		                data: 'DT_RowIndex',
		                name: 'DT_RowIndex',
		                class: 'center',
		                orderable: false,
		                searchable: false
		            },
		            {
		                data: 'name',
		                name: 'name'
		            },
		            {
		                data: 'type',
		                name: 'type'
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
    </script>
@endsection