

@extends('layouts.master')

@section('title', 'Stock in Transit  | ')
@section('content')
@include('partials.header')
@include('partials.sidebar')

  <main class="app-content">
      <!-- <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Manage Stock in Transits </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Stock in Transit </li>
          <li class="breadcrumb-item active"><a href="#">Manage Stock in Transits </a></li>
        </ul>
      </div> -->
      @if(Auth::check())
        @if(Auth::user()->role == 'admin')
          <div class="">
            <a class="btn btn-primary" href="{{ route('stockintransit.create') }}">
              <i class="fa fa-plus"></i>Stock in Transit
            </a>
          </div>
        @else
          @if(!$recordExists)
            <div class="">
              <a class="btn btn-primary" href="{{ route('stockintransit.create') }}">
                <i class="fa fa-plus"></i>Stock in Transit
              </a>
            </div>
          @endif
        @endif
      @endif

      <div class="row mt-2">
        <div class="col-md-12">
          <div class="tile " >
            <div class="d-flex flex-column" style="gap: 20px;" >
              @if($groupedStockInTransits->isEmpty())
                <div class="mx-auto">
                  <p>No stock-in-transit data available.</p>
                </div>
              @else
                @foreach ($groupedStockInTransits as $date => $routeGroups)
                  @foreach ($routeGroups as $group => $items)
                    @php
                      $firstItem = $items->first();
                    @endphp

                    <div class="d-flex justify-content-between align-items-center">
                      <div class="d-flex h-100 flex-row justify-content-center">
                        <p class="mb-0 ">Vehicle : </p>
                        <b>{{ $firstItem->vehicle->vehicle_type_parent_id == 1 ? 'Van' : 'Car' }}<p class="d-inline mx-2">-</p>{{$firstItem->vehicle->vehicle_number}}</b>
                      </div>
                      <div class="d-flex h-100 flex-row justify-content-center">
                        <p class="mb-0 ">Route : </p>
                        <b>{{ $firstItem->route->route_number }}</b>
                      </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-center">
                      <div class="d-flex align-self-end" style="gap: 10px;">
                        <a class="btn btn-primary btn-sm" href="{{ route('stockintransit.edit', $firstItem->id) }}">
                          <i class="fa fa-edit"></i>
                        </a>
                        <button class="btn btn-danger btn-sm waves-effect" type="submit" onclick="deleteTag({{ $firstItem->id }})">
                          <i class="fa fa-trash"></i>
                        </button>
                        <form id="delete-form-{{ $firstItem->id }}" action="{{ route('stockintransit.destroy', $firstItem->vehicle->id) }}" method="POST" style="display: none;">
                          @csrf
                          @method('DELETE')
                        </form>
                      </div>
                    </div>

                    <div id="product-data" class="overflow-auto row" style="max-height: 330px; overflow-y: auto;">
                        <div class="col-4">
                          <label class="control-label"><b>Product Name</b></label>
                        </div>
                        <div class="col-4">
                          <label class="control-label"><b>Sold Qty</b></label>
                        </div>
                        <div class="col-4">
                          <label class="control-label"><b>Bal Qty</b></label>
                        </div>
                      @foreach ($items as $item)
                        <div class="form-group col-4">
                          <input name="product_name[]" class="form-control @error('product_name') is-invalid @enderror" value="{{ $item->product->name }}" readonly>
                          <input type="hidden" name="product_id[]" value="{{ $item->product->id }}">
                          <input type="hidden" name="stock_in_transit_id[]" value="{{ $firstItem->id }}">
                          @error('product_name')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                          @enderror
                        </div>
                        <div class="form-group col-4">
                          <input name="sold-quantity[]" id="sold-quantity-{{ $item->product->id }}" class="form-control sold-quantity-input @error('sold-quantity') is-invalid @enderror" value="{{ $item->sold_qty ?? 0 }}"   type="number" readonly>
                          @error('sold-quantity')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                          @enderror
                        </div>
                        <div class="form-group col-4">
                          <input name="quantity[]" id="quantity-{{ $item->product->id }}" class="form-control quantity-input @error('quantity') is-invalid @enderror" value="{{ $item->quantity}}"   type="number" readonly>
                          @error('quantity')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                          @enderror
                        </div>
                      @endforeach
                    </div>
                
                  @endforeach
                @endforeach
              @endif
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript">
    function deleteTag(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success mx-2",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });
      swalWithBootstrapButtons.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545',
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          event.preventDefault();
          document.getElementById('delete-form-'+id).submit();
          swalWithBootstrapButtons.fire({
            title: "Deleted!",
            text: "Your file has been deleted.",
            icon: "success"
          });
        } else if (result.dismiss === swal.DismissReason.cancel) {
          swalWithBootstrapButtons.fire({
            title: 'Cancelled',
            text: 'Your data is safe :)',
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Ok',
            buttonsStyling: true,
          });
        }
      });
    }

    $(document).ready(function(){
      $('#product-data').find('input').on('focus', function () {
        $(this).css('border-color','#ced4da');
      });

    });

  </script>
@endpush
