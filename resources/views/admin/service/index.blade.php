@extends('admin.layouts.app')

<x-assets.datatables />

@push('page-css')

@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Service</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Service</li>
	</ul>
</div>
<div class="col-sm-5 col">
	<a href="{{route('service.create')}}" class="btn btn-primary float-right mt-2">Ajout</a>
</div>
@endpush

@section('content')
<div class="row">
	<div class="col-md-12">

		<!-- service -->
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="service-table" class="datatable table table-hover table-center mb-0">
						<thead>
							<tr>
								<th>Libelle</th>
								<th>Date</th>
								<th class="action-btn">Action</th>
							</tr>
						</thead>
						<tbody>
							{{-- @foreach ($service as $service)
							<tr>
								<td>
									{{$service->product}}
								</td>
								<td>{{$service->name}}</td>
								<td>{{$service->phone}}</td>
								<td>{{$service->email}}</td>
								<td>{{$service->address}}</td>
								<td>{{$service->company}}</td>
								<td>
									<div class="actions">
										<a class="btn btn-sm bg-success-light" href="{{route('edit-service',$service)}}">
											<i class="fe fe-pencil"></i> Edit
										</a>
										<a data-id="{{$service->id}}" href="javascript:void(0);" class="btn btn-sm bg-danger-light deletebtn" data-toggle="modal">
											<i class="fe fe-trash"></i> Delete
										</a>
									</div>
								</td>
							</tr>
							@endforeach							 --}}
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- /service-->

	</div>
</div>

@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        var table = $('#service-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('service.index')}}",
            columns: [
                {data: 'libelle', name: 'libelle'},
                {data: 'date_crea', name: 'date_crea'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

    });
</script>
@endpush