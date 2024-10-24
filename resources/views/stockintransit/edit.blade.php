@extends('layouts.master')

@section('title', 'Stock in Transit  | ')
@section('content')
@include('partials.header')
@include('partials.sidebar')
  <main class="app-content">
    <!-- <div class="app-title">
      <div>
        <h1><i class="fa fa-edit"></i> Edit Stock in Transit </h1>
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item">Stock in Transit </li>
        <li class="breadcrumb-item"><a href="#">Edit Stock in Transit </a></li>
      </ul>
    </div> -->

    @if(session()->has('message'))
      <div class="alert alert-success">
        {{ session()->get('message') }}
      </div>
    @endif

    <div class="row">
      <div class="clearix"></div>
      <div class="col-md-12">
        <div class="tile">
        <div class="alert alert-danger" style="display: none;" id ="quantity-error"></div>
          <h3 class="tile-title">Edit Stock in Transit </h3>
          <div class="tile-body">
            <form method="POST" action="{{$submitURL}}">
              @csrf
              @method('PUT')
              <div id="route-vehicle-section" style="display: {{$routeDisplay}};">
                <div class="form-group col-md-12">
                  <label class="control-label">Route Number</label>
                  <select name="route_id" id='route_id' class="form-control @error('route_id') is-invalid @enderror">
                    <option value=''>Select Route Number</option>
                    @foreach($routes as $route)
                    <option value="{{ $route['id'] }}" {{ old('route_id', $stockInTransit->route_id) == $route['id'] ? 'selected' : '' }}>
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
                    <option data-type="{{$vehicle['vehicle_type_parent_id'] == 1 ? 'Van' : 'Car'}}" value="{{ $vehicle['id'] }}" {{ old('vehicle_id', $stockInTransit->vehicle_id) == $vehicle['id'] ? 'selected' : '' }}>
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

                <div class="form-group col-md-12">
                  <label class="control-label">Select User</label>
                  <select name="user_id" id='user_id' class="form-control @error('user_id') is-invalid @enderror">
                    <option value=''>Select User ID</option>
                    @foreach($users as $user)
                    <option value="{{ $user['id'] }}" {{ old('user_id', $stockInTransit->user_id) == $user['id'] ? 'selected' : '' }}>
                      {{ $user['f_name'] }}
                    </option>
                    @endforeach
                  </select>
                  <span id="user-error-message" class="invalid-feedback mt-3" role="alert"></span>
                </div>

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
                    <div class="col-md-5">
                      <label class="control-label">Product Name</label>
                    </div>
                    <div class="col-md-2">
                      <label class="control-label">Existing Qty</label>
                    </div>
                    <div class="col-md-5">
                      <label class="control-label">Add Qty</label>
                    </div>
                  </div>

                @foreach($products as $product)
                  @php
                  $prdQuantity = array_key_exists($product->id, $productIDsAndQuantities)?$productIDsAndQuantities[$product->id]:0;
                  $stockInTransitID = array_key_exists($product->id, $stockInTransitIDs)?$stockInTransitIDs[$product->id]:'';
                  $prodMaxQuantity = array_key_exists($product->id, $supplierProdQuantities)?$supplierProdQuantities[$product->id]:0;
                  @endphp
                  @if($prdQuantity != 0)
                  <div id="product-section2" style="display: flex;">
                    <div class="form-group col-md-5">
                      <input name="product_name[]" class="form-control product-name @error('product_name') is-invalid @enderror" value="{{ old('product_name', $product->name) }}" readonly>
                      <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                      <input type="hidden" name="stock_in_transit_id[]" value="{{$stockInTransitID }}">
                      @error('product_name')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                    <div class="form-group col-md-2">
                      <input name="quantity[]" id="quantity-{{ $product->id }}" class="form-control quantity-input @error('quantity') is-invalid @enderror" value="{{ $prdQuantity }}"   type="number" readonly>
                      @error('quantity')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>

                    <div class="form-group col-md-5">
                      <input name="new_quantity[]" id="new_quantity-{{ $product->id }}" data-prodname="{{$product->name}}" class="form-control new-quantity-input @error('new_quantity') is-invalid @enderror" value="{{ old('new_quantity.' . $product->id) }}"    type="number" placeholder="Enter Quantity" data-existing="{{$prdQuantity}}" data-available = "{{$prodMaxQuantity}}" data-sku="{{ $product->sku_code }}"  data-barcode="{{ $product->barcode }}">
                      @error('new_quantity')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                  </div>
                  @endif
                  @endforeach
                </div>

                <div class="form-group d-flex mt-3 col-md-12 justify-content-center align-self-end">
                  <button id="update_button" class="btn btn-success" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                </div>
              </div>

              <!-- Showing All Form Data -->
              <div class="modal fade" id="sit-data" tabindex="-1" role="dialog" aria-labelledby="sitDataLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="sitDataLabel">Stock In Transit</h5>
                      <button type="button" class="btn-close btn btn-danger" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-remove"></i>
                      </button>
                    </div>
                    <div class="modal-body table-responsive">
                      <div class="d-flex" style="font-size: 16px;">
                        <strong>Route:</strong>
                        <p id="route-number" class="mx-2"></p>-
                        <p id="vehicle-type" class="mx-2"></p>-
                        <p id="vehicle-number" class="mx-2"></p>
                      </div>
                      <table class="table table-hover" >
                        <thead>
                        </thead>
                        <tbody id="products-list">
                          <tr>
                            <td><strong>Product Name</strong></td>
                            <td><strong>Existing Qty</strong></td>
                            <td><strong>New Qty</strong></td>
                          </tr>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td></td>
                            <td align="end">Total</td>
                            <td align="start" class="tot-qty"></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
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

  var currentUserRole = @if(Auth::check()) {!! json_encode(Auth::user()->role) !!} @else 'guest' @endif;

  $(document).ready(function() {
    $('#update_button').attr('disabled', true);

    $('#nextButton').on('click', function() {
      var routeSelect = $('#route_id');
      var vehicleSelect = $('#vehicle_id');

      if(currentUserRole == 'admin') {
        $('#route-vehicle-section').hide();
        $('#product-section').show();
        $('#add_button').show();
      } else {
        if (routeSelect.val() && vehicleSelect.val()) {
          //console.log(routeSelect.val(), vehicleSelect.val());
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
              var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'An error occurred. Please try again.';
              $('#error-message').html(errorMessage).show();
              setTimeout(function() {
                $('#error-message').show();
                window.location.href = '{{ route("stockintransit.index") }}'; 
              }, 3000);
            }
          });
        } else {
          $('#error-message').html('Please select both Route Number and Vehicle Number.').show();
          setTimeout(function() {
            $('#error-message').show(); 
          }, 3000);
        }
      }
    });

    function checkQty() {
      var routeNumber = $('#route_id').find('option:selected').text();
      var vehicleType = $('#vehicle_id').find('option:selected').data('type');
      var vehicleNumber = $('#vehicle_id').find('option:selected').text();
      var products = [];
      let allValidated = true;

      $('.new-quantity-input').each(function() {
        var quantityValue = $(this).val();
        var productName = $(this).data('prodname');
        var existingQuantity = $(this).data('existing');
        var availableQuantity = $(this).data('available');
        var newQty = parseInt(existingQuantity) + parseInt(quantityValue);

        if (quantityValue < 0) {
          $('#quantity-error').text(`Please enter non-negative quantities for ${productName}`).show();
          allValidated = false;
          return false;
        } else if (quantityValue && availableQuantity == 0) {
          $('#quantity-error').text(`${productName} is Out of Stock`).show();
          allValidated = false;
          return false;
        } else if (quantityValue > availableQuantity) {
          $('#quantity-error').text(`${productName} quantity exceeds available stock.`).show();
          allValidated = false;
          return false;
        } else {
          $('#quantity-error').hide();
          allValidated = true;
          return true;
        }

        $('#update_button').prop('disabled', !allValidated);
      });

      if (allValidated) {
        updateProductQuantities();
        $('.product-name').each(function () {
          var productName = $(this).val();
          var existingQty = parseInt($(this).closest('div').next().find('.quantity-input').val()) || 0;
          var newQty = parseInt($(this).closest('div').next().next().find('.new-quantity-input').val()) || 0;
          if(newQty > 0) {
            products.push({
              prodName:productName,
              existingQty:existingQty,
              newQty:newQty
            });
          }
        });
        $('#route-number').text(routeNumber);
        $('#vehicle-type').text(vehicleType);  
        $('#vehicle-number').text(vehicleNumber);  

        var existingProducts = {};

        $('#products-list').find('tr').each(function () {
          var existingProductName = $(this).find('.prod-name').text().trim();
          existingProducts[existingProductName] = true;
        });

        var productListHtml = '';
        products.forEach(function(product) {
          if (!existingProducts[product.prodName]) {
            const existingQuantity = parseInt(product.existingQty);
            const newQuantity = parseInt(product.newQty);
            productListHtml += `
            <tr class="product-row">
              <td><p class = "prod-name">${product.prodName}</p></td>
              <td><p class="product-existing-quantity">${existingQuantity}</p></td>
              <td><p class="product-new-quantity">${newQuantity}</p></td>
            </tr>`
          }
        });

        if(productListHtml) {
          $('#products-list').append(productListHtml);
          updateTotalQuantity();
        }
        $('#sit-data').modal('show');
      }
    }

    $('.new-quantity-input').on('input', function() {
      var sku = $(this).data('sku');
      var barcode = $(this).data('barcode');
      var quantityValue = $(this).val();
      var existingQuantity = $(this).data('existing');
      var availableQuantity = $(this).data('available');
      var new_quantity = parseInt(existingQuantity) + parseInt(quantityValue);
      //console.log(quantityValue,existingQuantity,availableQuantity,new_quantity)

      if ( quantityValue && availableQuantity == 0) {
        $('#quantity-error').text('Out of Stock').show();
        $('#update_button').prop('disabled', true);
      } else if (quantityValue < 0) {
        $('#quantity-error').css("display", "block");
        $('#quantity-error').text('Please enter non-negative quantities.').show();
        $('#update_button').prop('disabled', true);
      } else if (quantityValue >= 0 && quantityValue <= availableQuantity) {
        $('#update_button').prop('disabled', false);
        $('#quantity-error').css("display", "none");
        $('#product-section1').show();
        $('#sku_code').text(sku);
        $('#barcode').text(barcode);
      } else if (quantityValue > availableQuantity) {
        $('#update_button').prop('disabled', true);
        $('#quantity-error').text('Quantity exceeds the available stock.').show();
      } else {
        $('#update_button').prop('disabled', true);
        $('#product-section1').hide();
      }
    });

    $('#product-section').on('focus', '.product-name, .quantity-input', function () {
      $(this).css('border-color', '#ced4da');
    });

    function updateProductQuantities () {
        $('#products-list .product-row').each(function() {
          var productName = $(this).find('.prod-name').text().trim();
          var quantity = $('.product-name').filter(function () {
            return $(this).val() == productName;
          }).closest('div').next().next().find('.new-quantity-input').val();
          $(this).find('.product-new-quantity').text(quantity);
        });
        updateTotalQuantity();
      }

      function updateTotalQuantity() {
        var totalQuantity = 0;

        $('#product-list .product-row').each(function () {
          var quantity = parseInt($(this).find('.product-quantity').text()) || 0;
          totalQuantity += quantity;
        });

        $('.new-quantity-input').each(function () {
          var inputQty = parseInt($(this).val()) || 0;
          totalQuantity += inputQty;
        });

        $('.tot-qty').text(parseInt(totalQuantity))
      }

    $(document).on('click','#update_button', function () {
      checkQty();
    });
    
  });
</script>



