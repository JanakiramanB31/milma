

@extends('layouts.master')

@section('title', 'Vehicle | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Manage Vehicle</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">vehicle</li>
                <li class="breadcrumb-item active"><a href="#">Manage Vehicle</a></li>
            </ul>
        </div>
        <div class="">
            <a class="btn btn-primary" href="{{route('vehicle.create')}}"><i class="fa fa-plus"></i> Add New vehicle</a>
        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>Vehicle Number </th>
                                <th>Make </th>
                                <th>Model</th>
                                <th>Vehicle type</th>
                                <th>Fuel</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $vehicles as $vehicle)
                            <tr>
                                <td>{{ $vehicle->vehicle_number }} </td>
                                <td>{{ $vehicle->make }} </td>
                                <td>{{ $vehicle->model }} </td>
                                <td>{{ collect($vehicleTypes)->firstWhere('id', $vehicle->vehicle_type_parent_id)['name'] ?? '' }}</td>
                                <td>{{ collect($fuelTypes)->firstWhere('id', $vehicle->fuel_type_parent_id)['name'] ?? '' }}</td>
                                <td>{{ collect($types)->firstWhere('id', $vehicle->type_parent_id)['name'] ?? '' }}</td>
                                
                                 <td>
                                    <a class="btn btn-primary btn-sm" href="{{route('vehicle.edit', $vehicle->id)}}"><i class="fa fa-edit" ></i></a>
                                    <button class="btn btn-danger btn-sm waves-effect" type="submit" onclick="deleteTag({{ $vehicle->id }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $vehicle->id }}" action="{{ route('vehicle.destroy',$vehicle->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>



@endsection

@push('js')
    <script type="text/javascript" src="{{asset('/')}}js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{asset('/')}}js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
    <script src="https://unpkg.com/sweetalert2@7.19.1/dist/sweetalert2.all.js"></script>
    <script type="text/javascript">
        function deleteTag(id) {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('delete-form-'+id).submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swal(
                        'Cancelled',
                        'Your data is safe :)',
                        'error'
                    )
                }
            })
        }
    </script>
@endpush
