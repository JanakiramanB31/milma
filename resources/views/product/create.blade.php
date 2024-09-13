@extends('layouts.master')

@section('title', 'Add Product | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i>Add New Product</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Product</li>
                <li class="breadcrumb-item"><a href="#">Add Products</a></li>
            </ul>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="">
            <a class="btn btn-primary" href="{{route('product.index')}}"><i class="fa fa-edit"></i> Manage Product</a>
        </div>
        <div class="row mt-2">

            <div class="clearix"></div>
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Product</h3>
                    <div class="tile-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{route('product.store')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label">Product</label>
                                    <input name="name" class="form-control @error('name') is-invalid @enderror" type="text" placeholder="Product Name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">SKU code</label>
                                    <input name="sku_code" class="form-control @error('sku_code') is-invalid @enderror" type="text" placeholder="Enter SKU code">
                                    @error('sku_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">Brand</label>
                                    <input name="brand_name" class="form-control @error('brand_name') is-invalid @enderror" type="text" placeholder="Brand Name">
                                    @error('brand_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">Barcode</label>
                                    <input name="barcode" class="form-control @error('barcode') is-invalid @enderror" type="text" placeholder="Enter Barcode">
                                    @error('barcode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="control-label">Model</label>
                                    <input name="model" class="form-control @error('name') is-invalid @enderror" type="text" placeholder="Enter Model">
                                    @error('model')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">Category</label>

                                    <select name="category_id" class="form-control">
                                        <option>---Select Category---</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>

                                    @error('category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="control-label">Selling Price</label>
                                    <input name="sales_price" class="form-control @error('sales_price') is-invalid @enderror" type="number" placeholder="Enter Selling Price">
                                    @error('sales_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">Rate</label>
                                    <select name="rate_id" class="form-control">
                                        <option>---Select rate---</option>
                                        @foreach($rates as $rate)
                                            <option value="{{$rate->id}}">{{$rate->type}}</option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">Unit</label>
                                    <select name="unit_id" class="form-control">
                                        <option>---Select Unit---</option>
                                        @foreach($units as $unit)
                                            <option value="{{$unit->id}}">{{$unit->name}}</option>
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
                                    <select name="stock_type" class="form-control">
                                        <option>---Select Stock Type---</option>
                                        @foreach($stockTypes as $stockType)
                                            <option value="{{$stockType['short_name']}}">{{$stockType['name']}}</option>
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
                                    <input name="image"  class="form-control @error('image') is-invalid @enderror" type="file" >
                                    @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">Tax </label>
                                    <select name="tax_id" class="form-control">
                                        <option>---Select Tax---</option>
                                        @foreach($taxes as $tax)
                                            <option value="{{$tax->id}}">{{$tax->name}} %</option>
                                        @endforeach
                                    </select>
                                    @error('tax_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label class="radio control-label">Stock in Transit Display</label>
                                    <div class="controls">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="sit_status1" name="sit_status" class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="sit_status1" >Visible</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="sit_status2" name="sit_status" class="custom-control-input" value="0">
                                            <label class="custom-control-label" for="sit_status2">Invisible</label>
                                        </div>
                                    </div>
                                    @error('sit_status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                <label class="radio control-label">Status</label>
                                    <div class="controls">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="status1" name="status" class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="status1" >Active</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="status2" name="status" class="custom-control-input" value="0">
                                            <label class="custom-control-label" for="status2">Inactive</label>
                                        </div>
                                    </div>
                                    @error('tax_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label">MOQ Number</label>
                                    <input name="moq_number" class="form-control @error('moq_number') is-invalid @enderror" type="number" placeholder="Enter MOQ">
                                    @error('moq_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="tile">

                                <div id="example-2" class="content">
                                    <div class="group row">
                                        <div class="form-group col-md-5">
                                             <select name="supplier_id[]" class="form-control">
                                                <option>Select Supplier</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{$supplier->id}}">{{$supplier->name}} </option>
                                                @endforeach
                                            </select>
                                         </div>
                                        <div class="form-group col-md-5">
                                             <input name="supplier_price[]" class="form-control @error('supplier_price') is-invalid @enderror" type="number" placeholder="Purchase Price">
                                            <span class="text-danger">{{ $errors->has('additional_body') ? $errors->first('body') : '' }}</span>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <button type="button" id="btnAdd-2" class="btn btn-success btn-sm float-right"><i class="fa fa-plus"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm btnRemove float-right"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                             </div>
                            <div class="form-group col-md-4 align-self-end">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Add Product</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

     </main>
@endsection
@push('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
     <script src="{{asset('/')}}js/multifield/jquery.multifield.min.js"></script>




    <script type="text/javascript">
        $(document).ready(function(){
            var maxField = 10; //Input fields increment limitation
            var addButton = $('.add_button'); //Add button selector
            var wrapper = $('.field_wrapper'); //Input field wrapper
            var fieldHTML = '<div><select name="supplier_id[]" class="form-control"><option class="form-control">Select Supplier</option>@foreach($suppliers as $supplier)<option value="{{$supplier->id}}">{{$supplier->name}}</option>@endforeach</select><input name="supplier_price[]" class="form-control" type="text" placeholder="Enter Sales Price"><a href="javascript:void(0);" class="remove_button btn btn-danger" title="Delete field"><i class="fa fa-minus"></i></a></div>'
            var x = 1; //Initial field counter is 1

            //Once add button is clicked
            $(addButton).click(function(){
                //Check maximum number of input fields
                if(x < maxField){
                    x++; //Increment field counter
                    $(wrapper).append(fieldHTML); //Add field html
                }
            });

            //Once remove button is clicked
            $(wrapper).on('click', '.remove_button', function(e){
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x--; //Decrement field counter
            });

            $('#example-2').multifield({
                section: '.group',
                btnAdd:'#btnAdd-2',
                btnRemove:'.btnRemove'
            });
        });
    </script>

@endpush



