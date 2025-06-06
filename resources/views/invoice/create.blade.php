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
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
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
            
            <div class="table-responsive">
              <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">Product</th>
                      <th scope="col">Quantity</th>
                      <th scope="col">Price</th>
                  <!--<th scope="col">Discount %</th> -->
                      <th scope="col">Amount</th>
                      <th scope="col">Reason</th>
                      <th scope="col"><a class="addRow badge badge-success text-white"><i class="fa fa-plus"></i> Add Row</a></th>
                    </tr>
                  </thead>
                  <tbody id="product-section">
                    <tr>
                      <td>
                        <select name="product_id[]" class="form-control productname" >
                          <option value = ''>Select Product</option>
                          
                          @if($routeEmptyError)
                            <option value = ''>{{$routeEmptyError}}</option>
                          @elseif(count($products) == 0)
                            <option value = ''>No Products Found</option>
                          @else
                            @foreach($products as $product)
                            <option value="{{$product->id}}">{{$product->name}}</option>
                            @endforeach
                          @endif
                        </select>
                      </td>
                      <td><input type="number" name="qty[]"  class="form-control qty" step="0.01" min="0"><input type="hidden" name="type[]" value="sales" class="form-control" ></td>
                      <td><input type="number" name="price[]"  class="form-control price" step="0.01" min="0"></td>
                      <!-- <td><input type="text" name="dis[]" class="form-control dis" ></td> -->
                      <td><input type="number" name="amount[]"  class="form-control amount" step="0.01" min="0"></td>
                      <td><input type="hidden" name="reason[]" class="form-control reason" /></td>
                      <td><a   class="btn btn-danger remove"> <i class="fa fa-remove"></i></a></td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td></td>
                      <!-- <td></td> -->
                      <td><input type="hidden" name="total" id="total" class="form-control total" /></td>
                      <td><b>Total</b></td>
                      <td><b class="currency"></b><b class="total"></b></td>
                      <td></td>
                     
                    </tr>
                  </tfoot>
              </table>
            </div>
            <div class="modal fade" id="returnForm" tabindex="-1" aria-labelledby="returnFormLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                  <div class="modal-content ">
                      <div class="modal-header">
                        <h5 class="modal-title" id="returnFormLabel">Return Items</h5>
                        <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i></button>
                      </div>
                      <div class="modal-body table-responsive">
                      <table  class="table table-bordered" >
                        <thead>
                          <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Price</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Reason</th>
                            <th scope="col"><a id="add-return-row" class=" badge badge-success text-white"><i class="fa fa-plus"></i> Add Row</a></th>
                          </tr>
                        </thead>
                        <tbody id="return-product-body">
                          <tr>
                            <td class="d-flex align-items-center" style="gap: 10px;"><b style="color: red;">R</b>
                              <select id="return-product-id" name="product_id[]" class="form-control return-product-id" >
                                <option value =''>Select Return Product</option>
                                @foreach($products as $product)
                                <option value="{{$product->id}}">{{$product->name}}</option>
                                @endforeach
                              </select>
                            </td>
                            <td><input type="number" name="qty[]"  class="form-control return-qty" ><input type="hidden" name="type[]" value="returns" class="form-control" ></td>
                            <td><input type="number" name="price[]"  class="form-control p-2 return-price" readonly></td>
                            <td><input type="number" name="amount[]"  class="form-control return-amount" ></td>
                            <td><input type="text" name="reason[]"  class="form-control return-reason" /></td>
                            <td><a   class="btn btn-danger remove"> <i class="fa fa-remove"></i></a></td>
                          </tr>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td></td>
                            <td></td>
                            <td><b>Total</b></td>
                            <td><b class="return-currency"></b><b class="return-total"></b></td>
                            <td></td>
                            <td></td>
                          </tr>
                        </tfoot> 
                      </table>
                      <div id="return-table-error" class="text-danger"></div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button id="return-entry-button" type="button" class="btn btn-primary">Save changes</button>
                      </div>
                  </div>
              </div>
            </div>
            <div >
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#amountForm">Submit</button>
            </div>
            <div class="modal fade" id="amountForm" tabindex="-1" aria-labelledby="amountFormLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-md">
                  <div class="modal-content ">
                      <div class="modal-header d-flex justify-content-center">
                        <h3 class="modal-title text-center" id="amountFormLabel">Enter Received Amount</h3>
                      </div>
                      <div class="modal-body d-flex justify-content-center">
                        <input id="received_amt" type="number"  name="received_amt" class="form-control-md" style=" padding:20px;font-size:20px;" step="0.01" min="0"/>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                          <button id="submit-data" class="btn btn-primary" type="submit">Submit</button>
                      </div>
                  </div>
              </div>
            </div>
          </form>
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
    var cusID = '' ;
    console.log("Data",prodData)
    $(document).ready(function(){
      
      $('#product-section').delegate('.productname', 'change', function () {
        var  tr = $(this).parent().parent();
        tr.find('.qty').focus();
      })

      $('#product-section').delegate('.productname', 'change', function () {
        var tr =$(this).parent().parent();
        var id = tr.find('.productname').val();
        var prodPrices = prodData.productIdsAndPrices;
        console.log("DATA ID :", id);
        console.log("Price :", prodPrices[id]);
        tr.find('.price').val(prodPrices[id].toFixed(2));
        /* $.ajax({
          type    : 'GET',
          url     :'{!! URL::route('findPrice') !!}',

          dataType: 'json',
          data: {"_token": $('meta[name="csrf-token"]').attr('content'), 'id':id},
          success:function (data) {
              tr.find('.price').val(data.sales_price);
          } */
       /*  }); */
      });

      $('#product-section').delegate('.qty,.price', 'keyup', function () {
        var tr = $(this).parent().parent();
        var qty = tr.find('.qty').val();
        var price = tr.find('.price').val();
        var amount = qty * price;
        tr.find('.amount').val(parseFloat(amount).toFixed(2));
        total();
      });

      $('.amount').on('keyup', function () {
        total();
      })

      function total(){
        var salesTotal = 0;
        var returnsTotal = 0;
       
        $('#product-section .amount').each(function () {
          var salesAmount =$(this).val()-0;
          salesTotal += salesAmount;
          console.log(salesAmount)
        })
        $('#product-section .return-amount').each(function () {
          var returnsAmount =$(this).val()-0;
          returnsTotal += returnsAmount;
          console.log("Returns Amount",returnsTotal)
        })
        var total = salesTotal - returnsTotal;
        $('.currency').html("£");
        $('.total').html(parseFloat(total).toFixed(2));
        $('#total').val(parseFloat(total).toFixed(2));
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
          '<td><input type="number"  name="qty[]" class="form-control qty" ><input type="hidden" name="type[]" value="sales" class="form-control" ></td>\n' +
          '<td><input type="number"  name="price[]" class="form-control price" ></td>\n' +
          '<td><input type="number"  name="amount[]" class="form-control amount" ></td>\n' +
          '<td><input type="hidden" name="reason[]" class="form-control reason" ></td>\n' +
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
        cusID = customerID;
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
            row.find('.return-price').val(productDetails.price.toFixed(2));
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
        tr.find('.return-amount').val(parseFloat(amount).toFixed(2));
        returnTotal();
        $('#return-entry-button').on("click", function(){
          $('#return-product-name-entry').val();
          $('#return-qty-entry').val(parseFloat(qty).toFixed(2));
          $('#return-price-entry').val(parseFloat(price).toFixed(2));
        });
      });

      function returnTotal(){
        var total = 0;
        $('.return-amount').each(function (i,e) {
          var amount =$(this).val()-0;
          total += amount;
        })
        $('.return-currency').html("£");
        $('.return-total').html(parseFloat(total).toFixed(2));
      }

      
      $('#return-button-add').on("click", function() {
        /* $('#return-table').css('display','block') */
        var returnTableLength = $('#return-product-body').find('tr').length;
        //console.log("Length",returnTableLength);
        if (returnTableLength <= 0) {
          addReturnRow();
          $('.return-total').html(''.toFixed(2));
        }
       
      });

      function updateAmount(row) { 
        const qty = parseFloat(row.find('.return-qty').val()) || '';
        const price = parseFloat(row.find('.return-price').val()) || '';
        const amount = qty * price;
        row.find('.return-amount').val(parseFloat(amount).toFixed(2));
      }

      function addReturnRow() {
        var newRow = `<tr>
                        <td class="d-flex align-items-center" style="gap: 10px;"><b style="color: red;">R</b>
                          <select name="product_id[]" id="return-product-id" class="form-control return-product-id return-product-name">
                              <option value="">Select Return Product</option>
                              @foreach($products as $product)
                              <option value="{{$product->id}}">{{ $product -> name}}</option>
                              @endforeach
                          </select>
                        </td>
                        <td><input type="number"  name="qty[]" class="form-control return-qty" ><input type="hidden" name="type[]" value="returns" class="form-control" ></></td>
                        <td><input type="number"  name="price[]" class="form-control return-price" readonly></></td>
                        <td><input type="number"  name="amount[]" class="form-control return-amount" ></></td>
                        <td><input type="text" name="reason[]"  class="form-control return-reason" /></td>
                        <td><a class="btn btn-danger remove"><i class="fa fa-remove"></i></a></td>
                      </tr>`;
        $('#return-product-body').append(newRow);
      }

      $('#add-return-row').on("click",function() {
        addReturnRow();
      });

      $('#return-product-body').on('click', '.remove', function() {
        $(this).closest('tr').remove()
      });

      $('#return-entry-button').on("click", function () {
        var returnSection = $('#return-product-body').find('tr');
        let allFilled = true;
        returnSection.each(function () {
          var selectedValue = $(this).val();
          $(this).val(selectedValue);
          var inputValue = $(this).find('input').val();
          var selectValue = $(this).find('select').val();

          if ( selectValue.length == 0) {
            $('#return-table-error').html("Please Select the Product");
            allFilled = false; 
            return false;
          } else if (inputValue.length == 0) {
            $('#return-table-error').html("Please Enter the Quantity")
            allFilled = false; 
            return false;
          } else {
            $(this).find('input').attr('readonly', true);
            $(this).find('select').attr('disabled', true);
          }
        });
        if (allFilled) {
          $('#product-section').append(returnSection);
          total();
          addReturnRow();
          $('.return-total').html('');
          $('#returnForm').modal('hide');
        }
      });

      $('#submit-data').on('click', function () {
        $('#product-section').find('tr').each(function (){
          $(this).find('select').attr('disabled', false);
          $(this).find('input').attr('readonly', false);
        });
        setTimeout(()=>{
          $('#product-section').find('tr').each(function (){
          $(this).find('select').attr('disabled', true);
          $(this).find('input').attr('readonly', true);
        });
        }, 3000)
      });
      
    });
  </script>
@endpush