@extends('layouts.master')
@section('title', 'Stock in Transit | ')
@section('content')
@include('partials.header')
@include('partials.sidebar')
  <main class="app-content" style="min-width: 100vw;">
    <!-- <div class="app-title">
      <div>
        <h1><i class="fa fa-edit"></i>Add Stock in Transit</h1>
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item">Stock in Transit</li>
        <li class="breadcrumb-item"><a href="#">Add Stock in Transit</a></li>
      </ul>
    </div> -->

    @if(session()->has('message'))
      <div class="alert alert-success">
        {{ session()->get('message') }}
      </div>
    @endif

    <div class="">
      <a class="btn btn-primary" href="{{route('stockintransit.index')}}"><i class="fa fa-edit"> </i>Manage Stock in Transits</a>
    </div>
    <div class="row mt-2" >
      <div class="clearix"></div>
      <div class="col-md-12">
        <div class="tile">
          <div class="alert alert-danger" style="display: none;" id ="quantity-error"></div>
          <h3 class="tile-title">Stock in Transit</h3>
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
                    
                @if($userRole == 'admin')
                <div class="form-group col-md-12">
                  <label class="control-label">Select User</label>
                  <select name="user_id" id='user_id' class="form-control @error('user_id') is-invalid @enderror">
                    <option value=''>Select User ID</option>
                    @foreach($users as $user)
                    <option value="{{ $user['id'] }}" {{ old('user_id') == $user['id'] ? 'selected' : '' }}>
                      {{ $user['f_name'] }}
                    </option>
                    @endforeach
                  </select>
                </div>
                @endif
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
                  <div  style="display: flex;">
                    <div class="col-md-6">
                      <label class="control-label">Product Name</label>
                    </div>
                    <div class="col-md-6">
                      <label class="control-label">Quantity</label>
                    </div>
                  </div>

                  @foreach($products as $product)
                    @php
                      $prodMaxQuantity = array_key_exists($product->id, $supplierProdQuantities)?$supplierProdQuantities[$product->id]:0;
                    @endphp
                    <div id="product-section2" style="display: flex;">
                      <div class="form-group col-md-6">
                        <input name="product_name[]" class="form-control prod-name @error('product_name') is-invalid @enderror" value="{{ old('product_name', $product->name) }}" readonly>
                        <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                        @error('product_name')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                      </div>
                      <div class="form-group col-md-6">
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
                  <button id="check-button" type="button" class="btn btn-success" ><i class="fa fa-fw fa-lg fa-check-circle"></i> Add Stock in Transit Details</button>
                </div>
              </div>

              <!-- Showing All Form Data -->
              <div class="modal fade" id="sit-data" tabindex="-1" role="dialog" aria-labelledby="sitDataLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="sitDataLabel">Stock In Transit Form Data</h5>
                      <button type="button" class="btn-close btn btn-danger" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-remove"></i>
                      </button>
                    </div>
                    <div class="modal-body table-responsive">
                      <table class="table table-hover" >
                        <thead>
                          <tr>
                            <td><strong>Route Number</strong><p id="route-number"></p></td>
                            <td><strong>Vehicle Number</strong><p id="vehicle-number"></p></td>
                          </tr>
                        </thead>
                        <tbody id="products-list">
                          <tr>
                            <td><strong>Product Name</strong></td>
                            <td><strong>Quantity</strong></td>
                          </tr>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td align="end">Total</td>
                            <td align="start" class="tot-qty"></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <button id="submit-sit-data" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
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
      var currentUserRole = @if(Auth::check()) {!! json_encode(Auth::user()->role) !!} @else 'guest' @endif;
      $('#check-button').attr('disabled', true);

      $('#nextButton').on('click', function() {
        var routeSelect = $('#route_id');
        var vehicleSelect = $('#vehicle_id');
        var userIDSelect = $('#user_id');

        if (routeSelect.val() && vehicleSelect.val() && (currentUserRole === "admin" ? userIDSelect.val() :true)) {
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
          $('#error-message').html('Please select all fields.').show();
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
        console.log(availableQuantity);
        $('#product-section1').show();
        $('#sku_code').text(sku);
        $('#barcode').text(barcode);
        if (quantityValue < 0) {
          $('#quantity-error').css("display", "block");
          $('#quantity-error').text('Please enter non-negative quantities.').show();
          $('#add_button').prop('disabled', true);
          $('#check-button').attr('disabled', true);
        }
        else if (quantityValue >= 0 && quantityValue <= availableQuantity) {
          $('#quantity-error').hide();
          $('#add_button').prop('disabled', false);
          $('#check-button').attr('disabled', false);
          $('#product-section1').show();
          $('#sku_code').text(sku);
          $('#barcode').text(barcode);
        } else if (quantityValue > availableQuantity) {
          $('#add_button').prop('disabled', true);
          $('#check-button').attr('disabled', true);
          $('#quantity-error').text('Quantity exceeds available stock of Available Quantity').show();
        } else {
          $('#add_button').prop('disabled', false);
          $('#check-button').attr('disabled', false);
          $('#product-section1').hide();
        }
        updateProductQuantities();
      }).on('blur', function() {
        var quantityValue = $(this).val();
        if (!quantityValue) {
          $('#add_button').prop('disabled', true);
          $('#product-section1').hide();
        }
      });

      $('#check-button').on('click', function () {
        var routeNumber = $('#route_id').find('option:selected').text();
        var vehicleNumber = $('#vehicle_id').find('option:selected').text();
        var products = [];

        $('.prod-name').each(function () {
          var productName = $(this).val();
          var quantity = $(this).closest('div').next().find('.quantity-input').val();
          if(quantity > 0) {
            products.push({
              prodName:productName,
              qty:quantity
            });
          }
        });

        //console.log(routeNumber,vehicleNumber,products);
        $('#route-number').text(routeNumber);
        $('#vehicle-number').text(vehicleNumber);
        var existingProducts = {};

        $('#products-list').find('tr').each(function () {
          var existingProductName = $(this).find('.product-name').text().trim();
          existingProducts[existingProductName] = true;
        });
        
        var productListHtml = '';
        products.forEach(function(product) {
          if (!existingProducts[product.prodName]) {
            productListHtml += `
            <tr class="product-row">
              <td><p class = "product-name">${product.prodName}</p></td>
              <td><p class="product-quantity">${product.qty}</p></td>
            </tr>`
          }
        });

        if(productListHtml) {
          $('#products-list').append(productListHtml);
          updateTotalQuantity();
        }
        $('#sit-data').modal('show');
      });

      function updateProductQuantities () {
        $('#products-list .product-row').each(function() {
          var productName = $(this).find('.product-name').text().trim();
          var quantity = $('.prod-name').filter(function () {
            return $(this).val() == productName;
          }).closest('div').next().find('.quantity-input').val();
          $(this).find('.product-quantity').text(quantity);
        });
        updateTotalQuantity();
      }

      function updateTotalQuantity() {
        var totalQuantity = 0;

        $('#product-list .product-row').each(function () {
          var quantity = parseInt($(this).find('.product-quantity').text()) || 0;
          totalQuantity += quantity;
        });

        $('.quantity-input').each(function () {
          var inputQty = parseInt($(this).val()) || 0;
          totalQuantity += inputQty;
        });

        $('.tot-qty').text(totalQuantity)
      }

      $('#product-section').on('focus', '.prod-name, .quantity-input', function () {
        //console.log("Working");
        $(this).css('border-color','#ced4da');
      });

  });
  
</script>



