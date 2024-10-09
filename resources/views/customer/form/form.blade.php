<div class="tile-body">
  <form method="POST" action="{{$submitURL}}">
      @csrf
      @if($createPage)
      @else
      @method('PUT')
      @endif
      <div class="form-group col-md-12">
          <label class="control-label">Customer Name</label>
          <input name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name', $customer->name)}}" type="text" placeholder="Enter Customer's Name">
          @error('name')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>
      <div class="form-group col-md-12">
          <label class="control-label">Contact</label>
          <input name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{old('mobile', $customer->mobile)}}" type="text" placeholder="Enter Contact Number">
          @error('mobile')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>
      <div class="form-group col-md-12">
          <label class="control-label">Address</label>
          <textarea name="address" class="form-control @error('address') is-invalid @enderror"  placeholder="Enter Your Address">{{old('address', $customer->address)}}</textarea>
          @error('address')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>
      @if($createPage)
      <div class="form-group col-md-12">
          <label class="control-label">Email</label>
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email', $customer->email)}}" placeholder="Enter Your Email">
          @error('email')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>
      @endif
      <div class="form-group col-md-12">
          <label class="control-label">Details</label>
          <textarea name="details" class="form-control @error('details') is-invalid @enderror" >{{old('details', $customer->details)}}</textarea>
          @error('details')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>

      <div class="form-group col-md-12">
          <label class="control-label">Previous Credit Balance</label>
          <input name="previous_balance" class="form-control @error('previous_balance') is-invalid @enderror" value="{{old('previous_balance', $customer->previous_balance ? $customer->previous_balance : '0')}}" type="text" placeholder="Example: 111">
          @error('previous_balance')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>
      <!-- Temp Start -->
      <div class="form-group col-md-12">
          <label class="control-label">Company Name</label>
          <input name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{old('company_name', $customer->company_name)}}" type="text" placeholder="Enter Your Company Name">
          @error('company_name')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>

      <div class="form-group col-md-12">
          <label class="control-label">Contact Person</label>
          <input name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{old('contact_person', $customer->contact_person)}}" type="text" placeholder="Enter Other Contact Person">
          @error('contact_person')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>

      <div class="form-group col-md-12">
          <label class="control-label">Postcode</label>
          <input name="post_code" class="form-control @error('post_code') is-invalid @enderror" value="{{old('post_code', $customer->post_code)}}" type="text" placeholder="Enter Your Post Code">
          @error('post_code')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>

      <div class="form-group col-md-12">
          <label class="control-label">Customer Type</label>
          <select name="customer_type_parent_id" id = 'customer_type_parent_id' class="form-control @error('customer_type_parent_id') is-invalid @enderror" >
              <option value = ''>Select Customer Type</option>
              @foreach($customerTypes as $customerType)
              <option value="{{ $customerType['id'] }}" {{ old('customer_type_parent_id', $customer->customer_type_parent_id) == $customerType['id'] ? 'selected' : '' }}>
                {{ $customerType['name'] }}
              </option>
              @endforeach
          </select>
          @error('customer_type_parent_id')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>

      <div class="form-group col-md-12">
          <label class="control-label">Sale Type</label>
          <select name="sale_type_parent_id" id='sale_type_parent_id' class="form-control @error('sale_type_parent_id') is-invalid @enderror" >
              <option value=''>Select Sale Type</option>
              @foreach($saleTypes as $saleType)
              <option value="{{ $saleType['id'] }}" {{ old('sale_type_parent_id', $customer->sale_type_parent_id) == $saleType['id'] ? 'selected' : '' }}>
                {{ $saleType['name'] }}
              </option>
              @endforeach
          </select>
          @error('sale_type_parent_id')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>

      <div class="form-group col-md-12">
              <label class="control-label">Rate</label>
              <select name="rate_id" class="form-control @error('rate_id') is-invalid @enderror">
                  <option value =''>---Select rate---</option>
                  @foreach($rates as $rate)
                      <option value="{{$rate->id}}" {{ old('rate_id', $customer->rate_id) == $rate->id ? 'selected' : '' }}>{{$rate->type}}</option>
                  @endforeach
              </select>
              @error('rate_id')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
              @enderror
          </div>

      <div class="form-group col-md-12">
          <label class="radio control-label">Status</label>
              <div class="controls">
                  <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="status1" name="status" class="custom-control-input" value="1" {{ (old('status', $customer->status)=="1")? "checked" : "" }}>
                      <label class="custom-control-label" for="status1" >Active</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="status2" name="status" class="custom-control-input" value="0" {{ (old('status', $customer->status)=="0")? "checked" : "" }}>
                      <label class="custom-control-label" for="status2">Inactive</label>
                  </div>
              </div>
          </div>
      <!-- Temp End -->


      <div class="form-group col-md-4 align-self-end">
          <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>{{$createPage ? "Add" : "Update"}} Customer</button>
      </div>
  </form>
</div>