<div class="tile-body">
  <form method="POST" action="{{$submitURL}}">
      @csrf
      @if($editPage)
      @method('PUT')
      @endif
      <div class="form-group col-md-12">
          <label class="control-label">Vehicle Number</label>
          <input name="vehicle_number" class="form-control @error('vehicle_number') is-invalid @enderror" value="{{old('vehicle_number', $vehicle->vehicle_number)}}" type="text" placeholder="Enter Vehicle Number">
          @error('vehicle_number')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>
      <div class="form-group col-md-12">
          <label class="control-label">Manufacture's Name</label>
          <input name="make" class="form-control @error('make') is-invalid @enderror" value="{{old('make', $vehicle->make)}}" type="text" placeholder="Enter Manufacture's Name">
          @error('make')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>
      <div class="form-group col-md-12">
          <label class="control-label">Model</label>
          <input name="model" class="form-control @error('model') is-invalid @enderror" value="{{old('model', $vehicle->model)}}" type="text" placeholder="Enter Your Vehicle Model"/>
          @error('model')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>

      <div class="form-group col-md-12">
          <label class="control-label">Vehicle Type</label>
          <select name="vehicle_type_parent_id" id = 'vehicle_type_parent_id' class="form-control @error('vehicle_type_parent_id') is-invalid @enderror" >
              <option value = ''>Select Vehicle Type</option>
              @foreach($vehicleTypes as $vehicleType)
              <option value="{{ $vehicleType['id'] }}" {{ old('vehicle_type_parent_id', $vehicle->vehicle_type_parent_id) == $vehicleType['id'] ? 'selected' : '' }}>
                {{ $vehicleType['name'] }}
              </option>
              @endforeach
          </select>
          @error('vehicle_type_parent_id')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>

      <div class="form-group col-md-12">
          <label class="control-label">Fuel Type</label>
          <select name="fuel_type_parent_id" id='fuel_type_parent_id' class="form-control @error('fuel_type_parent_id') is-invalid @enderror" >
              <option value=''>Select Fuel Type</option>
              @foreach($FuelTypes as $FuelType)
              <option value="{{ $FuelType['id'] }}" {{ old('fuel_type_parent_id', $vehicle->fuel_type_parent_id) == $FuelType['id'] ? 'selected' : '' }}>
                {{ $FuelType['name'] }}
              </option>
              @endforeach
          </select>
          @error('fuel_type_parent_id')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>

      <div class="form-group col-md-12">
          <label class="control-label">Type</label>
          <select name="type_parent_id" id='type_parent_id' class="form-control @error('type_parent_id') is-invalid @enderror" >
              <option value=''>Select Fuel Type</option>
              @foreach($Types as $Type)
              <option value="{{ $Type['id'] }}" {{ old('type_parent_id', $vehicle->type_parent_id) == $Type['id'] ? 'selected' : '' }}>
                {{ $Type['name'] }}
              </option>
              @endforeach
          </select>
          @error('fuel_type_parent_id')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>


      <div class="form-group col-md-4 align-self-end">
          <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> {{$editPage ? "Update" : "Add"}} Vehicle</button>
      </div>
  </form>
</div>