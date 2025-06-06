<form method="POST" action="{{$submitURL}}" enctype="multipart/form-data">
@csrf
@if($editPage)
@method('PUT')
@endif
  <div class="row">
    <div class="form-group col-md-6">
      <label class="control-label">Product</label>
      <input name="name" value="{{old('name', $product->name)}}" class="form-control @error('name') is-invalid @enderror" type="text" placeholder="Product Name">
      @error('name')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>
    <div class="form-group col-md-6">
      <label class="control-label">SKU code</label>
      <input name="sku_code" value="{{old('sku_code', $product->sku_code)}}" class="form-control @error('sku_code') is-invalid @enderror" type="text" placeholder="Enter SKU code">
      @error('sku_code')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>
    <div class="form-group col-md-6">
      <label class="control-label">Brand</label>
      <input name="brand_name" value="{{old('brand_name', $product->brand_name)}}" class="form-control @error('brand_name') is-invalid @enderror" type="text" placeholder="Brand Name">
      @error('brand_name')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>
    <div class="form-group col-md-6">
      <label class="control-label">Barcode</label>
      <input name="barcode" value="{{old('barcode', $product->barcode)}}" class="form-control @error('barcode') is-invalid @enderror" type="text" placeholder="Enter Barcode">
      @error('barcode')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    <div class="form-group col-md-6">
      <label class="control-label">Model</label>
      <input name="model" value="{{old('model', $product->model)}}" class="form-control @error('model') is-invalid @enderror" type="text" placeholder="Enter Model">
      @error('model')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>
    <div class="form-group col-md-6">
      <label class="control-label">Category</label>

      <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
        <option value = ''>---Select Category---</option>
        @foreach($categories as $category)
        <option value="{{$category->id}}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
        @endforeach
      </select>

      @error('category_id')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    
    <div class="form-group col-md-6">
      <label class="control-label">Unit</label>
      <select name="unit_id" class="form-control @error('unit_id') is-invalid @enderror">
        <option value = ''>---Select Unit---</option>
        @foreach($units as $unit)
        <option value="{{$unit->id}}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>{{$unit->name}}</option>
        @endforeach
      </select>
      @error('unit_id')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    <div class="form-group col-md-6">
      <label class="control-label">Stock Type</label>
      <select name="stock_type" class="form-control @error('stock_type') is-invalid @enderror">
        <option value = ''>---Select Stock Type---</option>
        @foreach($stockTypes as $stockType)
        <option value="{{$stockType['short_name']}}" {{ old('stock_type', $product->stock_type ?? array_values($stockTypes)[0]['short_name'] ) == $stockType['short_name'] ? 'selected' : '' }}>{{$stockType['name']}}</option>
        @endforeach
      </select>
      @error('stock_type')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    <div class="form-group col-md-6">
      <label class="control-label">Image</label>
      <input name="image"  class="form-control @error('image') is-invalid @enderror" type="file">
      @if($product->image)
        <div class="mt-2">
          <img src="{{ asset('images/product/' . $product->image) }}" alt="Current Image" style="height: 50px;">
        </div>
      @endif
      @error('image')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>
    <div class="form-group col-md-6">
      <label class="control-label">Tax </label>
      <select name="tax_id" class="form-control @error('tax_id') is-invalid @enderror">
        <option value = ''>---Select Tax---</option>
        <option value="0" selected>0 %</option>
        @foreach($taxes as $tax)
        <option value="{{$tax->id}}" {{ old('tax_id', $product->tax_id) == $tax->id ? 'selected' : '' }}>{{$tax->name}} %</option>
        @endforeach
      </select>
      @error('tax_id')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    <div class="form-group col-md-6">
      <label class="control-label">Base Rate</label>
      <input name="base_rate" value="{{old('base_rate', $product->base_rate)}}"  class="form-control @error('base_rate') is-invalid @enderror" type="text" placeholder="Enter Base Rate">
      @error('base_rate')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    <div class="form-group col-md-6">
      <label class="control-label">MOQ Number</label>
      <input name="moq_number" value="{{old('moq_number', $product->moq_number)}}"  class="form-control @error('moq_number') is-invalid @enderror" type="number" placeholder="Enter MOQ">
      @error('moq_number')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    <div class="form-group col-md-6">
      <label class="radio control-label">Stock in Transit Display</label>
      <div class="controls">
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" id="sit_status1" name="sit_status" class="custom-control-input" value="1" {{ (old('sit_status', $product->sit_status ?? "1")=="1")? "checked" : "" }}>
          <label class="custom-control-label" for="sit_status1">Visible</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" id="sit_status2" name="sit_status" class="custom-control-input" value="0" {{ (old('sit_status', $product->sit_status)=="0")? "checked" : "" }}>
          <label class="custom-control-label" for="sit_status2">Invisible</label>
        </div>
      </div>
      @error('sit_status')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>
    <div class="form-group col-md-6">
      <label class="radio control-label">Status</label>
      <div class="controls">
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" id="status1" name="status" class="custom-control-input" value="1" {{ (old('status', $product->status ?? "1")=="1")? "checked" : "" }}>
          <label class="custom-control-label" for="status1">Active</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" id="status2" name="status" class="custom-control-input" value="0" {{ (old('status', $product->status)=="0")? "checked" : "" }}>
          <label class="custom-control-label" for="status2">Inactive</label>
        </div>
      </div>
      @error('status')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>
    
  </div>



  <!-- Product Price Entry -->

  <div class="tile">
    <div id="rate_type_form" class="content">
     <div class="row mb-2">
        <div class="col-md-5">Rate</div>
        <div class="col-md-5">Price</div>
      </div>
        @foreach(old('rate_id', $productRateIds) as $index => $oldRateId)
          <div class="row rate_type_group">
        
            @php
              $curProductPrice = old('product_price', $productPrices)[$index];
              $curProductPrice = ($curProductPrice)?$curProductPrice:'';
            @endphp
            <div class="form-group col-md-5" >
              <select name="rate_id[]" class="form-control">
                <option value = ''>Select Rate Type</option>
                @foreach($rates as $rate)
                <option value="{{$rate->id}}" {{$rate->id == $oldRateId ? 'selected' : '' }}>{{$rate->type}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-5" >
              <input name="product_price[]" value="{{ $curProductPrice ? number_format($curProductPrice, $decimalLength) : '' }}" class="form-control prod-sup-price @error('product_price') is-invalid @enderror" type="text" placeholder="Product Price">
              <span class="text-danger">{{ $errors->has('additional_body') ? $errors->first('body') : ''  }}</span>
            </div>
            <div class="form-group rate_btn_group d-flex col-md-2" style="gap: 5px;">
              <button type="button" class="rate_btn_add btn btn-success btn-sm float-right"><i class="fa fa-plus"></i></button>
              <button type="button" class="rate_btn_remove btn btn-danger btn-sm btnRemove float-right"><i class="fa fa-trash"></i></button>
            </div>
          </div>
        @endforeach
      </div>
    
  </div>

  <div class="form-group col-md-4 align-self-end">
    <button id="submit-data" class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>{{$editPage ? "Update" : "Add"}} Product</button>
  </div>

</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {

    //Accept Only Float Number Price In Quantity Field 
    $(document).on('input', '.prod-sup-price, .base_rate', function() {
      this.value = this.value.replace(/[^0-9.]/g, '');
      
      const parts = this.value.split('.');
      if (parts.length > 2) {
        this.value = parts[0] + '.' + parts.slice(1).join('');
      }
    });

    $(document).on('click','.rate_btn_add', function () {
      console.log("f")
      var newField = $('.rate_type_group').first().clone();
      newField.css("display", 'flex');
      newField.find("input").val(""); 
      newField.find("select").prop("selectedIndex", 0);
      $('#rate_type_form').append(newField) 
    });

    $(document).on('click', '.rate_btn_remove', function () {
      var rowCount = $('#rate_type_form').find('.rate_type_group').length;
      console.log(rowCount);
      if (rowCount > 2) {
        console.log("Inside",rowCount);
        $(this).closest('.rate_type_group').remove();
      } else {
        $('#error_message').html("You can't delete the last field")
      }
    });
  });
</script>