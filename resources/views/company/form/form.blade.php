<div class="tile-body">
  <form method="POST" action="{{$submitURL}}">
      @csrf
      @if($editPage)
      @method('PUT')
      @endif
      <div class="form-group col-md-12">
          <label class="control-label">Company Name</label>
          <input name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{old('company_name', $company->company_name)}}" type="text" placeholder="Enter your Company Name">
          @error('company_name')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>
      <div class="form-group col-md-12">
          <label class="control-label">Address1</label>
          <input name="address_1" class="form-control @error('address_1') is-invalid @enderror" value="{{old('address_1', $company->address_1)}}" type="text" placeholder="Enter your Address">
          @error('address_1')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>
      <div class="form-group col-md-12">
          <label class="control-label">Address2</label>
          <input name="address_2" class="form-control @error('address_2') is-invalid @enderror" value="{{old('address_2', $company->address_2)}}" type="text" placeholder="Enter alternate Address"/>
          @error('address_2')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>
      <div class="form-group col-md-12">
          <label class="control-label">City</label>
          <input name="city" class="form-control @error('city') is-invalid @enderror" value="{{old('city', $company->city)}}" type="text" placeholder="Enter your City"/>
          @error('city')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>

      <div class="form-group col-md-12">
          <label class="control-label">Postcode</label>
          <input name="post_code" class="form-control @error('post_code') is-invalid @enderror" value="{{old('post_code', $company->post_code)}}" type="text" placeholder="Enter your Postcode"/>
          @error('post_code')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>

      <div class="form-group col-md-4 align-self-end">
          <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Add Vehicle Details</button>
      </div>
  </form>
</div>