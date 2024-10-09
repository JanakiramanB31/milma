<div class="tile-body">
  <form method="POST" action="{{$submitURL}}">
      @csrf
      @if($editPage)
      @method('PUT')
      @endif
      <div class="form-group col-md-12">
          <label class="control-label">Price Type</label>
          <select name="price_type_parent_id" id = 'price_type_parent_id' class="form-control @error('price_type_parent_id') is-invalid @enderror" >
              <option value = ''>Select Price Type</option>
              @foreach($priceTypes as $priceType)
              <option value="{{ $priceType['id'] }}" {{ old('price_type_parent_id', $price->price_type_parent_id) == $priceType['id'] ? 'selected' : '' }}>
                {{ $priceType['name'] }}
              </option>
              @endforeach
          </select>
          @error('price_type_parent_id')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>

      <div class="form-group col-md-12">
          <label class="control-label">Price Code</label>
          <select name="price_code_parent_id" id = 'price_code_parent_id' class="form-control @error('price_code_parent_id') is-invalid @enderror" >
              <option value = ''>Select Price Code</option>
              @foreach($priceCodes as $priceCode)
              <option value="{{ $priceCode['id'] }}" {{ old('price_code_parent_id', $price->price_code_parent_id) == $priceCode['id'] ? 'selected' : '' }}>
                {{ $priceCode['name'] }}
              </option>
              @endforeach
          </select>
          @error('price_code_parent_id')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>



      <div class="form-group col-md-4 align-self-end">
          <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> {{$editPage ? "Update" : "Add"}} Price</button>
      </div>
  </form>
</div>