@extends('layouts.master')

@section('title', 'Invoice | ')
@section('content')
  @include('partials.header')
  @include('partials.sidebar')
  <main class="app-content">
    <div class="app-title">
      <div>
        <h1><i class="fa fa-edit"></i> Create Invoice</h1>
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item">Invoices</li>
        <li class="breadcrumb-item"><a href="#">Create</a></li>
      </ul>
    </div>

    <div class="row">
      <div class="clearix"></div>
      <div class="col-md-12">
        <div class="tile">
          <div id="error-message"></div>
          <h3 class="tile-title">Invoice</h3>
          <div class="tile-body">
            <form  method="POST" action="{{route('invoice.store')}}">
                @csrf
                <div class="form-group col-md-3">
                    <label class="control-label">Customer Name</label>
                    <select name="customer_id" class="form-control" id="customer_name">
                        <option>Select Customer</option>
                        @foreach($customers as $customer)
                            <option name="customer_id" value="{{$customer->id}}">{{$customer->name}} </option>
                        @endforeach
                    </select> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Date</label>
                    <input name="date"  class="form-control datepicker"  value="<?php echo date('Y-m-d')?>" type="date" placeholder="Enter your email">
                </div>


                <div class="d-flex justify-content-end mb-3">
                  <button id="return-button-add" type="button" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#returnForm">
                    <i class="fa fa-plus"></i> Add Return Items
                  </button>
                </div>


            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">Product</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                <!--<th scope="col">Discount %</th> -->
                    <th scope="col">Amount</th>
                    <th scope="col"><a class="addRow badge badge-success text-white"><i class="fa fa-plus"></i> Add Row</a></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <select name="product_id[]" class="form-control productname" >
                        <option>Select Product</option>
                        @foreach($products as $product)
                        <option name="product_id[]" value="{{$product->id}}">{{$product->name}}</option>
                        @endforeach
                      </select>
                    </td>
                    <td><input type="text" name="qty[]" class="form-control qty" ><input type="hidden" name="type[]" value="sales" class="form-control" ></td>
                    <td><input type="text" name="price[]" class="form-control price" ></td>
                    <!-- <td><input type="text" name="dis[]" class="form-control dis" ></td> -->
                    <td><input type="text" name="amount[]" class="form-control amount" ></td>
                    <td><a   class="btn btn-danger remove"> <i class="fa fa-remove"></i></a></td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td></td>
                    <td></td>
                    <!-- <td></td> -->
                    <td><b>Total</b></td>
                    <td><b class="total"></b></td>
                    <td></td>
                  </tr>
                </tfoot>
            </table>
            <div class="modal fade" id="returnForm" tabindex="-1" aria-labelledby="returnFormLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                  <div class="modal-content ">
                      <div class="modal-header">
                        <h5 class="modal-title" id="returnFormLabel">Return Items</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body  ">
                      <table  class="table table-bordered" >
                        <thead>
                          <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Price</th>
                            <th scope="col">Amount</th>
                            <th scope="col"><a id="add-return-row" class=" badge badge-success text-white"><i class="fa fa-plus"></i> Add Row</a></th>
                          </tr>
                        </thead>
                        <tbody id="return-product-body">
                          <tr>
                            <td>
                              <select id="return-product-id" name="product_id[]" class="form-control return-product-id" >
                                <option>Select Product</option>
                                @foreach($products as $product)
                                
                                <option name="product_id[]" value="{{$product->id}}">{{$product->name}}</option>
                                @endforeach
                              </select>
                            </td>
                            <td><input type="text" name="qty[]" class="form-control return-qty" ><input type="hidden" name="type[]" value="returns" class="form-control" ></td>
                            <td><input type="text" name="price[]" class="form-control return-price" readonly></td>
                            <td><input type="text" name="amount[]" class="form-control return-amount" ></td>
                            <td><a   class="btn btn-danger remove"> <i class="fa fa-remove"></i></a></td>
                          </tr>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td></td>
                            <td></td>
                            <td><b>Total</b></td>
                            <td><b class="return-total"></b></td>
                            <td></td>
                          </tr>
                        </tfoot>
                      </table>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button id="return-entry-button" type="button" class="btn btn-primary">Save changes</button>
                      </div>
                  </div>
              </div>
            </div>
            <div >
              <button class="btn btn-primary" type="submit">Submit</button>
            </div>
          </form>
          <div class="return_items">
            <table>
              <tr>
            <td>Product Name:</td><td><input type="number" id="return-product-name-entry" readonly/></td>
            </tr>
            <tr>
            <td>Quantity:</td><td><input type="text" id="return-qty-entry" readonly></td>
            </tr>
            <tr>
            <td>Price:</td><td><input type="text" id="return-price-entry" readonly></td>
            </div>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>

@endsection
@push('js')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
  <script src="{{asset('/')}}js/multifield/jquery.multifield.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
 
  <script type="text/javascript">
    var prodData= '';
    console.log("Data",prodData)
    $(document).ready(function(){
      
      $('tbody').delegate('.productname', 'change', function () {
        var  tr = $(this).parent().parent();
        tr.find('.qty').focus();
      })

      $('tbody').delegate('.productname', 'change', function () {
        var tr =$(this).parent().parent();
        var id = tr.find('.productname').val();
        var dataId = {'id':id};
        $.ajax({
          type    : 'GET',
          url     :'{!! URL::route('findPrice') !!}',

          dataType: 'json',
          data: {"_token": $('meta[name="csrf-token"]').attr('content'), 'id':id},
          success:function (data) {
              tr.find('.price').val(data.sales_price);
          }
        });
      });

      $('tbody').delegate('.qty,.price,.dis', 'keyup', function () {
        var tr = $(this).parent().parent();
        var qty = tr.find('.qty').val();
        var price = tr.find('.price').val();
        var amount = (qty * price);
        tr.find('.amount').val(amount);
        total();
      });

      function total(){
        var total = 0;
        $('.amount').each(function (i,e) {
          var amount =$(this).val()-0;
          total += amount;
        })
        $('.total').html(total);
      }

      $('.addRow').on('click', function () {
        addRow();
      });

      $('.add-Row').on('click', function () {
        addRow();
      });

      function addRow() {
        var addRow = '<tr>\n' +
          '<td><select name="product_id[]" class="form-control productname " >\n' +
          '<option value="0" selected="true" disabled="true">Select Product</option>\n' +
          '@foreach($products as $product)\n' +
          '<option value="{{$product->id}}">{{$product->name}}</option>\n' +
          '@endforeach\n' +
          '</select></td>\n' +
          '<td><input type="text" name="qty[]" class="form-control qty" ><input type="hidden" name="type[]" value="sales" class="form-control" ></td>\n' +
          '<td><input type="text" name="price[]" class="form-control price" ></td>\n' +
          '<td><input type="text" name="amount[]" class="form-control amount" ></td>\n' +
          '<td><a   class="btn btn-danger remove"> <i class="fa fa-remove"></i></a></td>\n' +
          '</tr>';
        $('tbody').append(addRow);
      };

      $('.remove').live('click', function () {
        var l =$('tbody tr').length;
        if(l==1){
          alert('you cant delete last one')
        }else{
          $(this).parent().parent().remove();
        }
      });

      $('#customer_name').on('change', function() {
        
        var customerID = $(this).val();
        console.log(customerID);
        if(customerID) {
          $.ajax({
            url: '{{ route("invoice.getProducts",":id") }}'.replace(':id', customerID),
            type: 'POST',
            data: {
              customer_id: customerID,
              _token: '{{ csrf_token() }}' 
            },
            success: function(response) {
              try {
                console.log("Working",response);
                prodData= response;
                console.log("proddata",prodData)
                $('#return-product-id').empty().append('<option value="">Select Return Product</option>');
                  if (response.products.length > 0) {
                    $.each(response.products, function(index, product) {
                      
                      $('#return-product-id').append('<option value="' + product.id + '">' + product.name + '</option>');
                    });
                  } else {
                    $('#return-product-id').append('<option value="">No products available</option>');
                  }
                console.log(response.quantityAndPrices);
              } catch(error) {
                console.log("Failed",error)
              }
            },
            error: function(xhr) {
              var errorMessage =  'An error occurred. Please try again.';
              $('#error-message').html(errorMessage).show();
            }
          });
        } else {
          console.log("Failed")
        }

      });

      $('#return-product-body').on('change', '.return-product-id', function() {

        var selectedProductID = $(this).val(); 
        var row = $(this).closest('tr');

        if (selectedProductID) {
          var productDetails = prodData.quantityAndPrices.find(item => item.product_id == selectedProductID);
          // var productDetails = prodData.quantityAndPrices.find(function(item) {
          //   return item.product_id == selectedProductID; 
          // });

          if (productDetails) {
            row.find('.return-price').val(productDetails.price);
          } else {
            row.find('.return-price').val('');

          }
        }
        
        /* var customerID = $('#customer_name').val();
        console.log(customerID);
        if (customerID) {
          $.ajax({
            url: '{{ route("invoice.getProducts", ":id") }}'.replace(':id', customerID),
              type: 'POST',
              data: {
                customer_id: customerID,
                _token: '{{ csrf_token() }}' 
              },
              success: function(response) {
                try {
                  console.log("Product details retrieved", response);
                  console.log(prodData);
                  var data = response.quantityAndPrices[0];
                  console.log("Data", data)
                  $('.return-qty').val(data.qty);
                  $('.return-price').val(data.price); 
                  var quantity = data.qty || 0;
                  var price = data.price || 0;
                  $('.return-amount').val(quantity * price);
                } catch (error) {
                  console.error("Failed to populate fields", error);
                }
              },
              error: function(xhr) {
                var errorMessage = 'An error occurred while fetching product details. Please try again.';
                $('#error-message').html(errorMessage).show();
              }
          });
        } else {
          console.log("Error")
        } */
      });

      $('#return-product-body').delegate('.return-qty,.return-price', 'keyup', function () {
        var tr = $(this).closest('tr');
        var qty = tr.find('.return-qty').val();
        var price = tr.find('.return-price').val();
        var amount = (qty * price);
        tr.find('.return-amount').val(amount);
        returnTotal();
        $('#return-entry-button').on("click", function(){
          $('#return-product-name-entry').val();
          $('#return-qty-entry').val(qty);
          $('#return-price-entry').val(price);
        });
      });

      function returnTotal(){
        var total = 0;
        $('.return-amount').each(function (i,e) {
          var amount =$(this).val()-0;
          total += amount;
        })
        $('.return-total').html(total);
      }

      
      $('#return-button-add').on("click", function() {
        $('#return-table').css('display','block')
      });

      function updateAmount(row) { 
        const qty = parseFloat(row.find('.return-qty').val()) || '';
        const price = parseFloat(row.find('.return-price').val()) || '';
        const amount = qty * price;
        row.find('.return-amount').val(amount.toFixed(2));
      }

      $('#add-return-row').on("click",function() {
        var newRow = `<tr>
                        <td>
                          <select name="product_id[]" id="return-product-id" class="form-control return-product-id return-product-name">
                              <option valu="">Select Product</option>
                              @foreach($products as $product)
                              <option value="{{$product->id}}">{{ $product -> name}}</option>
                              @endforeach
                          </select>
                        </td>
                        <td><input type="number" name="qty[]" class="form-control return-qty" ><input type="hidden" name="type[]" value="returns" class="form-control" ></></td>
                        <td><input type="number" name="price[]" class="form-control return-price" readonly></></td>
                        <td><input type="text" name="amount[]" class="form-control return-amount" ></></td>
                        <td><a class="btn btn-danger remove"><i class="fa fa-remove"></i></a></td>
                      </tr>`;
        $('#return-product-body').append(newRow);
      });

      $('#return-product-body').on('click', '.remove', function() {
        $(this).closest('tr').remove()
      });

    });
  </script>
@endpush