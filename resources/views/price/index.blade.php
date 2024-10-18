

@extends('layouts.master')

@section('title', 'Price | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Manage Prices</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Price</li>
                <li class="breadcrumb-item active"><a href="#">Manage Prices</a></li>
            </ul>
        </div>
        <div class="">
            <a class="btn btn-primary" href="{{route('price.create')}}"><i class="fa fa-plus"></i>New Price</a>
        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>Price Type </th>
                                <th>Price Code </th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $prices as $price)
                            <tr>
                                <td>{{ collect($priceTypes)->firstWhere('id', $price->price_type_parent_id)['name'] ?? '' }} </td>
                                <td>{{ collect($priceCodes)->firstWhere('id', $price->price_code_parent_id)['name'] ?? '' }} </td>
                                 <td>
                                    <a class="btn btn-primary btn-sm" href="{{route('price.edit', $price->id)}}"><i class="fa fa-edit" ></i></a>
                                    <button class="btn btn-danger btn-sm waves-effect" type="submit" onclick="deleteTag({{ $price->id }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $price->id }}" action="{{ route('price.destroy',$price->id) }}" method="POST" style="display: none;">
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
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: true,
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          event.preventDefault();
          document.getElementById('delete-form-'+id).submit();
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
          swal({
            title: 'Cancelled',
            text: 'Your data is safe :)',
            type: 'error',
            showCancelButton: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Ok',
            confirmButtonClass: 'btn btn-success',
            buttonsStyling: true,
            
          });
        }
      });
    }
    </script>
@endpush
