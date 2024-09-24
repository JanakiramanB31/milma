<div class="tile-body">
  <form method="POST" action="{{$submitURL}}">
    @csrf
    @if($editPage)
    @method('PUT')
    @endif

    <div id="route-vehicle-section" style="display: {{$routeDisplay}};">
      <div class="form-group col-md-12">
        <label class="control-label">Route Number</label>
        <select name="route_id" id='route_id' class="form-control @error('route_id') is-invalid @enderror">
          <option value=''>Select Route Number</option>
          @foreach($routes as $route)
          <option value="{{ $route['id'] }}" {{ old('route_id', $stockintransit->route_id) == $route['id'] ? 'selected' : '' }}>
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
          <option value="{{ $vehicle['id'] }}" {{ old('vehicle_id', $stockintransit->vehicle_id) == $vehicle['id'] ? 'selected' : '' }}>
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

        <div class="form-group d-flex col-md-12 justify-content-st ">
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
        $prdQuantity = array_key_exists($product->id, $productIDsAndQuantities)?$productIDsAndQuantities[$product->id]:'';
        $stockTransitId = array_key_exists($product->id, $stockinTransitIds)?$stockinTransitIds[$product->id]:'';
        @endphp
        <div id="product-section2" style="display: flex;">
          <div class="form-group col-md-6">
            <label class="control-label">Product Name</label>
            <input name="product_name[]" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name', $product->name) }}" readonly>
            <input type="hidden" name="product_id[]" value="{{ $product->id }}">
            <input type="hidden" name="stock_transit_id[]" value="{{ $product->id }}">
            @error('product_name')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label class="control-label">Quantity</label>
            <input name="quantity[]" id="quantity-{{ $product->id }}" class="form-control quantity-input @error('quantity') is-invalid @enderror" value="{{ old('quantity.' . $product->id, $prdQuantity) }}"  type="number" placeholder="Enter Quantity" data-sku="{{ $product->sku_code }}"  data-barcode="{{ $product->barcode }}">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  
  $(document).ready(function() {
    $('#nextButton').on('click', function() {
      var routeSelect = $('#route_id');
      var vehicleSelect = $('#vehicle_id');

      if (routeSelect.val() && vehicleSelect.val()) {
        console.log(routeSelect.val(), vehicleSelect.val());
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
            }, 3000);
            e.preventDefault(); 
            window.location.href = '{{ route("stockintransit.index") }}'; 
          }
        });
      } 
      else {
        $('#error-message').html('Please select both Route Number and Vehicle Number.').show();
        setTimeout(function() {
          $('#error-message').show(); 
        }, 3000);
      }
    });


    $('.quantity-input').on('input', function() {
      var sku = $(this).data('sku');
      var barcode = $(this).data('barcode');
      var quantityValue = $(this).val();

      if (quantityValue) {
        $('#product-section1').show();
        $('#sku_code').text(sku);
        $('#barcode').text(barcode);
      } else {
        $('#product-section1').hide();
      }
    });
  });
</script>
