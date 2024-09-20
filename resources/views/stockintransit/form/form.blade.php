<div class="tile-body">
  <form method="POST" action="{{$submitURL}}">
      @csrf
      @if($editPage)
      @method('PUT')
      @endif
    <!-- <div>{{$stockintransit}}</div> -->
      <div id="route-vehicle-section">

      <div class="form-group col-md-12">
          <label class="control-label">Route Number</label>
          <select name="route_id" id='route_id' class="form-control @error('route_id') is-invalid @enderror" >
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
          <select name="vehicle_id" id='vehicle_id' class="form-control @error('vehicle_id') is-invalid @enderror" >
              <option value=''>Select Vehicle Number</option>
              @foreach($vehicles as $vehicle)
              <option value="{{ $vehicle['id'] }}" {{ old('vehicle_id', $vehicle->vehicle_id) == $vehicle['id'] ? 'selected' : '' }}>
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
      <div class="form-group d-flex col-md-12 justify-content-end align-self-end">
          <button type="button" id="nextButton" class="btn btn-success">Next</button>
      </div>

      </div style="overflow-y:hidden">
  @foreach($products as $product)
  <!-- <div>{{$product}}</div> -->
   
      <div id="product-section1" style="display: none;">
          <div id="sku_code" class="form-group d-block col-md-6">
              <label class="control-label">SKU Code</label>
              <input name="sku_code" class="form-control @error('sku_code') is-invalid @enderror" value="{{old('sku_code', $product->sku_code)}}" readonly>
              @error('sku_code')
              <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
          <div id="barcode" class="form-group d-block col-md-6" >
              <label class="control-label">Bar Code</label>
              <input name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{old('barcode', $product->barcode)}}" readonly>
              @error('barcode')
              <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
      </div>

      <div id="product-section2" style="display: flex;">

        <div class="form-group d-block col-md-6">
            <label class="control-label">Product Name</label>
            <input name="product_id" class="form-control @error('product_id') is-invalid @enderror" value="{{old('product_id', $product->name)}}" readonly>
            @error('product_id')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group d-block col-md-6">
            <label class="control-label">Quanity</label>
            <input name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{old('quantity', $vehicle->quantity)}}" type="number" placeholder="Enter Quantity">
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
          <button style="display: none;" id="add_button" class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Add Stock in Transit Details</button>
      </div>
  </form>
</div>

<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const nextButton = document.getElementById('nextButton');
        const routeSelect = document.getElementById('route_id');
        const vehicleSelect = document.getElementById('vehicle_id');
        const routeVehicleSection = document.getElementById('route-vehicle-section');
        const productSection = document.getElementById('product-section');
        const addButtonSelect = document.getElementById('add_button');


        // Show the product section when the next button is clicked
        nextButton.addEventListener('click', function() {
            if (routeSelect.value && vehicleSelect.value) {
                routeVehicleSection.style.display = 'none';
                productSection.style.display = 'block';
                addButtonSelect.style.display = 'block';
            } else {
                alert('Please select both Route Number and Vehicle Number.');
            }
        });
    });
</script> -->

<script>
        const quantity = document.getElementById('quantity');
        const productSection1 = document.getElementById('product-section1');
        const barCode = document.getElementById('barcode');
        quantity.addEventListener('click', function() {
          productSection1.style.display = 'flex';
                barCode.style.display = 'block';
        });
</script>