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


      <div class="form-group d-flex col-md-12 justify-content-end">
        <button type="button" id="nextButton" class="btn btn-success">Next</button>
      </div>
    </div>

    <div id="product-section" style="display: {{$productDisplay}};">
      <div id="product-section1" style="display: none;">
        <div class="form-group col-md-6">
          <label class="control-label">SKU Code</label>
          <input id="sku_code" name="sku_code" class="form-control @error('sku_code') is-invalid @enderror" value="{{ old('sku_code') }}" readonly>
          @error('sku_code')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        <div class="form-group col-md-6">
          <label class="control-label">Bar Code</label>
          <input id="barcode" name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode') }}" readonly>
          @error('barcode')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
      </div>
     

      <div class="overflow-auto" style="max-height: 250px; overflow-y: auto;">
        @foreach($products as $product)
        @php
        echo $prdQuantity = array_key_exists($product->id, $productIDsAndQuantities)?$productIDsAndQuantities[$product->id]:'';
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
            <input name="quantity[]" id="quantity-{{ $product->id }}" class="form-control quantity-input @error('quantity') is-invalid @enderror" value="{{ $prdQuantity }}" type="number" placeholder="Enter Quantity" data-sku="{{ $product->sku_code }}"  data-barcode="{{ $product->barcode }}">
            @error('quantity')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
        @endforeach
      </div>

      <div class="form-group col-md-4 align-self-end">
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
        $('#product-section1').css('display', 'flex');
        $('#product-section1').show();
        $('#sku_code').val(sku);
        $('#barcode').val(barcode);
      } else {
        $('#product-section1').hide();
      }
    });
  });
</script>
