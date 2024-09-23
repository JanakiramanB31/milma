

@extends('layouts.master')

@section('title', 'Stock in Transit Entry | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Manage Stock in Transit Entry</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Stock in Transit Entry</li>
                <li class="breadcrumb-item active"><a href="#">Manage Stock in Transit Entry</a></li>
            </ul>
        </div>
        <div class="">
            <a class="btn btn-primary" href="{{route('stockintransit.create')}}"><i class="fa fa-plus"></i> Add New Stock in Transit Entry</a>
        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                      <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                              <th>Serial No</th>
                              <th>Route Number</th>
                              <th>Vehicle Number</th>
                              <th>Total Products</th>
                              <th>Total Quantity</th>
                              <th>Date</th>
                              <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          @php 
                          $serialNo = 1; 
                          @endphp
                          @foreach ($groupedStockInTransits as $date => $routeGroups)
                            @foreach ($routeGroups as $group => $items)
                              @php
                                $firstItem = $items->first();
                                $totalProducts = $items->count();
                                $totalQuantity = $items->sum('quantity');
                              @endphp
                              <tr>
                                <td>{{ $serialNo++ }}</td>
                                <td>{{ $firstItem->route->route_number }}</td>
                                <td>{{ $firstItem->vehicle->vehicle_number }}</td>
                                <td>{{ $totalProducts }}</td>
                                <td>{{ $totalQuantity }}</td>
                                <td>{{ $date }}</td>
                                <td>
                                  <a class="btn btn-primary btn-sm" href="{{ route('stockintransit.edit', $firstItem->id) }}">
                                    <i class="fa fa-edit"></i>
                                  </a>
                                  <button class="btn btn-danger btn-sm waves-effect" type="submit" onclick="deleteTag({{ $firstItem->id }})">
                                    <i class="fa fa-trash"></i>
                                  </button>
                                  <form id="delete-form-{{ $firstItem->id }}" action="{{ route('stockintransit.destroy', $firstItem->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                  </form>
                                </td>
                              </tr>
                            @endforeach
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
