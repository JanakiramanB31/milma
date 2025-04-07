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
  <div id="error_message" class="alert alert-success">
    {{ session()->get('message') }}
  </div>
  @endif

  <div class="">
    <a class="btn btn-primary" href="{{route('product.index')}}"><i class="fa fa-edit"></i> Manage Products</a>
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
          @include('product.form.form')
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
        var rateFieldHTML = '<div><select name="rate_id[]" class="form-control"><option class="form-control">Select Rate Type</option>@foreach($rates as $rate)<option value="{{$rate->id}}">{{$rate->name}}</option>@endforeach</select><input name="product_price[]" class="form-control" type="number" placeholder="Product Price"></div>';
        var x = 1; //Initial field counter is 1

        //Once add button is clicked
        // $(addButton).click(function(){
        //     //Check maximum number of input fields
        //     if(x < maxField){
        //         x++; //Increment field counter
        //         $(wrapper).append(fieldHTML); //Add field html
        //     }
        // });

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

        // $('#example-3').multifield({
        //     section: '.group',
        //     btnAdd:'.rate_btn_add',
        //     btnRemove:'.btnRemove'
        // });
    });
</script>

@endpush