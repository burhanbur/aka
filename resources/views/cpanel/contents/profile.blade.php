@section('content')
<div class="row">
	<div class="col-md-3">
		<div class="card card-primary card-outline">
			<div class="card-body box-profile">
				<div class="center">
					<img class="profile-user-img img-fluid" alt="User profile picture" src="{{ asset('admin/assets/images/users/default.png') }}">
				</div>

				<br>

				<h3 class="profile-username text-center" style="font-size: 16px;"><strong>{{ $data->name }}</strong></h3>

	        	<p class="text-muted center">{{ $data->email }}</p>
			</div>
		</div>
	</div>

	<div class="col-md-9">
		<div class="card">
			<div class="card-header p-2">
				<ul class="nav nav-pills">
		            <li class="nav-item"><a class="nav-link active" href="#biodata" data-toggle="tab">Biodata</a></li>
		            <li class="nav-item"><a class="nav-link" href="#event_member" data-toggle="tab">Event</a></li>
		        </ul>
			</div>

			<div class="card-body">
				<div class="tab-content">
					<div class="active tab-pane" id="biodata">
						<div class="form-group row">
							<label style="font-weight: 400" for="inputName" class="col-sm-3 col-form-label">Phone Number</label>
							<div class="col-sm-9">
								<label style="border: 0; color: black;" class="form-control">
									{{ $data->phone }}
								</label>
							</div>
						</div>
						<div class="form-group row">
							<label style="font-weight: 400" for="inputName" class="col-sm-3 col-form-label">Institution</label>
							<div class="col-sm-9">
								<label style="border: 0; color: black;" class="form-control">
									{{ $data->institution }}
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="card-footer">
				<div style="float: right;">
					<a href="" value="{{ route('edit.profile') }}" class="btn btn-primary modalEditProfile" title="Edit Profile" data-toggle="modal" data-target="#modalEditProfile"><span class="fa fa-edit"></span> Edit</a>
					<button class="btn btn-secondary" type="button" data-dismiss="modal"><i class="fa fa-undo"></i> Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection