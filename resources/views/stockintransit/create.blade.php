@extends('layouts.master')

@section('title', 'Stock in Transit | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')
    <main class="app-content" style="min-width: 100vw;">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i>Add Stock in Transit Entry</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Stock in Transit</li>
                <li class="breadcrumb-item"><a href="#">Add Stock in Transit Entry</a></li>
            </ul>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="">
            <a class="btn btn-primary" href="{{route('stockintransit.index')}}"><i class="fa fa-edit"> </i>Manage Stock in Transit</a>
        </div>
        <div class="row mt-2" style="margin:-30px">

            <div class="clearix"></div>
            <div class="col-md-12">
                <div class="tile">
                <div class="alert alert-danger" style="display: none;" id ="quantity-error"></div>
                    <h3 class="tile-title">Stock in Transit Entry</h3>
                    <div class="tile-body">
                      <form method="POST" action="{{$submitURL}}">
                        @csrf
                        <div id="route-vehicle-section" style="display: {{$routeDisplay}};">
                          <div class="form-group col-md-12">
                            <label class="control-label">Route Number</label>
                            <select name="route_id" id='route_id' class="form-control @error('route_id') is-invalid @enderror">
                              <option value=''>Select Route Number</option>
                              @foreach($routes as $route)
                              <option value="{{ $route['id'] }}" {{ old('route_id') == $route['id'] ? 'selected' : '' }}>
                                {{ $route['route_number'] }}
                              </option>
                              @endforeach
                            </select>
                            @error('route_id')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>

                          <div class="form-group col-md-12">
                            <label class="control-label">Vehicle Number</label>
                            <select name="vehicle_id" id='vehicle_id' class="form-control @error('vehicle_id') is-invalid @enderror">
                              <option value=''>Select Vehicle Number</option>
                              @foreach($vehicles as $vehicle)
                              <option value="{{ $vehicle['id'] }}" {{ old('vehicle_id') == $vehicle['id'] ? 'selected' : '' }}>
                                {{ $vehicle['vehicle_number'] }}
                              </option>
                              @endforeach
                            </select>
                            @error('vehicle_id')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                          <span id="error-message" class="invalid-feedback col-md-12" role="alert"></span>
                            <div class="form-group d-flex col-md-12 justify-content-start ">
                              <button type="button" id="nextButton" class="btn btn-success">Next</button>
                            </div>
                          </div>

                        <div id="product-section" style="display: {{$productDisplay}};">
                          <div id="product-section1" style="display: flex;">
                            <div class="form-group col-md-6 ">
                              <pre class="control-label">SKU Code : </pre>
                              <b id="sku_code" name="sku_code" >{{ old('sku_code') }}</b>
                            </div>
                            <div class="form-group col-md-6">
                              <pre class="control-label">Bar Code : </pre>
                              <b id="barcode" name="barcode" >{{ old('barcode') }}</b>
                            </div>
                          </div>
                        

                          <div class="overflow-auto" style="max-height: 330px; overflow-y: auto;">
                            @foreach($products as $product)
                            @php
                            $prodMaxQuantity = array_key_exists($product->id, $supplierProdQuantities)?$supplierProdQuantities[$product->id]:0;
        
                            @endphp
                            <div id="product-section2" style="display: flex;">
                              <div class="form-group col-md-6">
                                <label class="control-label">Product Name</label>
                                <input name="product_name[]" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name', $product->name) }}" readonly>
                                <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                @error('product_name')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                              </div>
                              <div class="form-group col-md-6">
                                <label class="control-label">Quantity</label>
                                <input name="quantity[]" id="quantity-{{ $product->id }}" class="form-control quantity-input @error('quantity') is-invalid @enderror" value="{{ old('quantity.' . $product->id) }}"  type="number" placeholder="Enter Quantity" data-available = "{{$prodMaxQuantity}}" data-sku="{{ $product->sku_code }}"  data-barcode="{{ $product->barcode }}">
                                @error('quantity')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                              </div>
                            </div>
                            @endforeach
                          </div>

                          <div class="form-group mt-3 col-md-4 align-self-end">
                            <button style="display: {{$productDisplay}};" id="add_button" class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Add Stock in Transit Details</button>
                          </div>
                        </div>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $('#nextButton').on('click', function() {
      var routeSelect = $('#route_id');
      var vehicleSelect = $('#vehicle_id');
      var userIDSelect = $('#user_id');

      if (routeSelect.val() && vehicleSelect.val()) {
        var route_id = routeSelect.val();
        var vehicle_id = vehicleSelect.val();
        console.log(route_id, vehicle_id);
        $.ajax({
          url: '{{ route("stockintransit.check") }}',
          method: 'POST',
          data: {
            route_id: routeSelect.val(),
            vehicle_id: vehicleSelect.val(),
            _token: '{{ csrf_token() }}' 
          },
          success: function(response) {
              $('#route-vehicle-section').hide();
              $('#product-section').show();
              $('#add_button').show();
          },
          error: function(xhr) {
            var errorMessage =  xhr.responseJSON.error && xhr.responseJSON.error ? xhr.responseJSON.error : 'An error occurred. Please try again.';
            var ID = xhr.responseJSON && xhr.responseJSON.ID ;
            console.log(ID);
            $('#error-message').html(errorMessage).show();
            setTimeout(function() {
              $('#error-message').show();
              window.location.href =  '{{ route("stockintransit.edit", ":id") }}'.replace(':id', ID); 
            }, 3000);
            
          }
        });
      } 
      else {
        $('#error-message').html('Please select both Route Number and Vehicle Number.').show();
        setTimeout(function() {
          $('#error-message').hide(); 
        }, 3000);
      }
    });
    
    $('.quantity-input').on('input', function() {
      var sku = $(this).data('sku');
      var barcode = $(this).data('barcode');
      var quantityValue = $(this).val();
      var availableQuantity = $(this).data('available');
      if (quantityValue < 0) {
        $('#quantity-error').css("display", "block");
        $('#quantity-error').text('Please enter non-negative quantities.').show();
        $('#add_button').prop('disabled', true);
      } else if (quantityValue >= 0 && quantityValue <= availableQuantity) {
        $('#quantity-error').css("display", "none");
        $('#add_button').prop('disabled', false);
        $('#product-section1').show();
        $('#sku_code').text(sku);
        $('#barcode').text(barcode);
      } else if (quantityValue > availableQuantity) {
        $('#add_button').prop('disabled', true);
        $('#quantity-error').text('Quantity exceeds available stock of Available Quantity').show();
      } else {
        $('#add_button').prop('disabled', false);
        $('#product-section1').hide();
      }
    });
  });
  
</script>



