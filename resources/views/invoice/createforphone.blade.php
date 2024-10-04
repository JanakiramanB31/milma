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
                <div class="row" >
                  <div class="form-group col-6">
                      <label class="control-label">Customer Name</label>
                      <select name="customer_id" class="form-control" id="customer_name">
                          <option value = '0'>Select Customer</option>
                          @foreach($customers as $customer)
                              <option name="customer_id" value="{{$customer->id}}">{{$customer->name}} </option>
                          @endforeach
                      </select>
                      <div id="customer-name-error" class="text-danger"></div> 
                  </div>
                  <div class="form-group col-6">
                      <label class="control-label">Date</label>
                      <input name="date"  class="form-control datepicker"  value="<?php echo date('Y-m-d')?>" type="date" placeholder="Enter your email">
                  </div>
                </div>


                <div class="d-flex justify-content-end mb-3">
                  <button id="return-button-add" type="button" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#returnForm">
                    <i class="fa fa-plus"></i> Add Return Items
                  </button>
                </div>
            
                <div class="row">
                  <div class="col-md-5 mb-5">
                    <div class="table-responsive">
                      <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">Product</th>
                              <th scope="col">Quantity</th>
                              <th scope="col" hidden>Price</th>
                              <th scope="col">Amount</th>
                              <th scope="col">Action</th>
                              <!-- <th scope="col"><a class="addProductRow badge badge-success text-white"><i class="fa fa-plus"></i> Add Row</a></th> -->
                            </tr>
                          </thead>
                          <tbody id="product-section" style="max-height: 100px;overflow-y: auto;">
                           <!--  <tr>
                              <td>
                                <select name="product_id[]" class="form-control productname" >
                                  <option value = ''>Select Product</option>
                                  @foreach($products as $product)
                                  <option value="{{$product->id}}">{{$product->name}}</option>
                                  @endforeach
                                </select>
                              </td>
                              <td><input type="number" name="qty[]"  class="form-control qty" step="0.01" min="0"><input type="hidden" name="type[]" value="sales" class="form-control" ></td>
                              <td hidden><input type="number" name="price[]"  class="form-control price" step="0.01" min="0"></td>
                              <td><input type="number" name="amount[]"  class="form-control amount" step="0.01" min="0"></td>
                              <td><input type="hidden" name="reason[]" class="form-control reason" /></td>
                              <td><a   class="btn btn-danger remove"> <i class="fa fa-remove"></i></a></td>
                            </tr> -->
                          </tbody>
                          <tfoot>
                            <tr>
                              <td></td>
                              <td hidden></td>
                              <td><input type="hidden" name="total" id="total" class="form-control total" /></td>
                              <td><b>Total</b></td>
                              <td><b class="currency"></b><b class="total"></b></td>
                              <!-- <td></td> -->
                            </tr>
                          </tfoot>
                      </table>
                    </div>
                  </div>
                  <div class="col-md-7 mb-5">
                    <select name="product_id[]" class="form-control productname mb-5">
                      <option value = ''>Select Product</option>
                      @foreach($products as $product)
                      <option value="{{$product->id}}">{{$product->name}}</option>
                      @endforeach
                    </select>

                    <div class="overflow-y" style="max-height: 400px;overflow-y: auto;">
                    <div class="d-flex justify-content-between">
                    @foreach($products as $product)
                    <figure >
                      <image class="product-select" data-id="{{$product->id}}" data-name="{{$product->name}}" src={{asset('images/product/' . $product->image)}} width='100px' height='100px'/>
                      <figcaption class="text-center">{{$product->name}}</figcaption>
                      
                    </figure>
                    @endforeach
                    </div>
                    </div>
                  </div>
                </div>
            <div class="modal fade" id="returnForm" tabindex="-1" aria-labelledby="returnFormLabel" aria-hidden="true" role="dialog">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
                            <th scope="col" hidden>Price</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Actions</th>
                            <!-- <th scope="col"><a id="add-return-row" class=" badge badge-success text-white"><i class="fa fa-plus"></i> Add Row</a></th> -->
                          </tr>
                        </thead>
                        <tbody id="return-product-body">
                          <tr>
                            <td class="d-flex align-items-center" style="gap: 10px;"><b style="color: red;">R</b>
                              <select id="return-product-id" name="product_id[]" class="form-control return-product-id" >
                                <option value =''>Select Return Product</option>
                                @if(session('routeEmptyError'))
                                  <option value = ''>{{session('routeEmptyError')}}</option>
                                @elseif(count($products) == 0)
                                  <option value = ''>No Products Found</option>
                                @else
                                  @foreach($products as $product)
                                  <option value="{{$product->id}}" >{{$product->name}}</option>
                                  @endforeach
                                @endif
                              </select>
                            </td>
                            <td ><input type="number" name="qty[]"  class="form-control return-qty" ><input type="hidden" name="type[]" value="returns" class="form-control" ></td>
                            <td hidden><input type="number" name="price[]"  class="form-control p-2 return-price" readonly></td>
                            <td><input type="number" name="amount[]"  class="form-control return-amount" ></td>
                            <td hidden >Reason:<input type="text" name="reason[]"  class="form-control return-reason" /></td>
                            <td style="gap:5px;" hidden> <i class="fa fa-remove btn btn-danger btn-sm remove"></i></td>
                            <!-- <td>
                              <button id="return-button-popup" type="button" class="btn btn-primary btn-sm text-white" data-bs-toggle="collapse" data-bs-target="#returnButtonPopup" aria-expanded="false" aria-controls="returnButtonPopup">
                                  <i class="fa fa-plus"></i>
                              </button>
                              <div id="returnButtonPopup" class="collapse icon-group">
                                <i class="fa fa-remove btn btn-danger btn-sm action-icon remove" title="Remove"></i>
                                <button id="return-button-add-reason" type="button" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#returnReasonForm">
                                  <i class="fa fa-edit btn btn-success btn-sm action-icon add-return-reason" title="Edit"></i>
                                </button>
                                <i class="fa fa-edit btn btn-success btn-sm action-icon add-return-reason" title="Edit"></i>
                                <i class="fa fa-eye btn btn-info btn-sm action-icon " title="View"></i>
                                <i class="fa fa-plus btn btn-primary btn-sm action-icon add-return-row" title="Add"></i>
                              </div>

                            </td> -->
                            <td>
                            <button type="button" class="btn btn-secondary popoverButton"  data-toggle="popover" data-bs-placement="top" data-html="true">
                              <i class="fa fa-ellipsis-h"></i>
                            </button>
                            </td>
                          </tr>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td></td>
                            <td></td>
                            <td><b>Total</b></td>
                            <td><b class="return-currency"></b><b class="return-total"></b></td>
                           <!--  <td></td> -->
                            <td hidden></td>
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

            <div class="modal fade" id="returnReasonForm" tabindex="-1" aria-labelledby="returnReasonFormLabel" aria-hidden="true" role="dialog">
              <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                  <div class="modal-content ">
                      <div class="modal-header">
                        <h5 class="modal-title" id="returnReasonFormLabel">Add Reason</h5>
                        <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i></button>
                      </div>
                      <div class="modal-body ">
                        <div>
                          <label>Enter Reason</label>
                          <textarea name="popup-reason" class="return-popup-reason form-control-lg col-12"></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button id="return-entry-button" type="button" class="btn btn-primary">Save</button>
                      </div>
                  </div>
              </div>
            </div>

           <!--  <div class="modal fade" id="returnButtonPopup" tabindex="-1" aria-labelledby="returnButtonPopupLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="returnButtonPopupLabel">Select an action</h5>
                        <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fa fa-remove btn btn-danger btn-sm action-icon" title="Remove"></i>
                        <i class="fa fa-edit btn btn-success btn-sm action-icon" title="Edit"></i>
                        <i class="fa fa-eye btn btn-info btn-sm action-icon" title="View"></i>
                        <i class="fa fa-check btn btn-primary btn-sm action-icon" title="Confirm"></i>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button id="return-entry-button" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
              </div>
          </div> -->
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
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
 
  <script type="text/javascript">
    var prodData= '';
    var cusID = '' ;
    console.log("Data",prodData)
    $(document).ready(function(){

      //$('[data-toggle="popover"]').popover('show'); 
      $('[data-toggle="popover"]').popover({
        html: true,
        content:`<i class="fa fa-remove btn btn-danger btn-sm action-icon remove" title="Remove"></i>
                            <i class="fa fa-edit btn btn-success btn-sm add-return-reason" title="Edit"></i>
                            <i class="fa fa-eye btn btn-info btn-sm action-icon" title="View"></i>
                            <i class="fa fa-plus btn btn-primary btn-sm action-icon add-return-row" title="Add"></i>`
                    
      });

      //$('[data-toggle="popover"]').popover('show');
      
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
      });

      $('#product-section').delegate('.qty,.price', 'keyup', function () {
        var tr = $(this).parent().parent();
        var qty = tr.find('.qty').val();
        var price = tr.find('.price').val();
        var amount = (qty * price);
        tr.find('.amount').val(amount.toFixed(2));
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
          console.log("Retuens Amount",returnsTotal)
        })
        var total = salesTotal - returnsTotal;
        $('.currency').html("$");
        $('.total').html(total);
        $('#total').val(total.toFixed(2));
      }

      $('.addProductRow').on('click', function () {
        addProductRow();
      });


      /* function addProductRow() {
        var addProductRow = '<tr>\n' +
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
        $('tbody').append(addProductRow);
      }; */

      $('.remove').on('click', function () {
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
        tr.find('.return-amount').val(amount.toFixed(2));
        returnTotal();
        $('#return-entry-button').on("click", function(){
          $('#return-product-name-entry').val();
          $('#return-qty-entry').val(qty);
          $('#return-price-entry').val(price.toFixed(2));
        });
      });

      function returnTotal(){
        var total = 0;
        $('.return-amount').each(function (i,e) {
          var amount =$(this).val()-0;
          total += amount;
        })
        $('.return-currency').html("$");
        $('.return-total').html(total.toFixed(2));
      }

      
      $('#return-button-add').on("click", function() {
        /* $('#return-table').css('display','block') */
        var returnTableLength = $('#return-product-body').find('tr').length;
        //console.log("Length",returnTableLength);
        if (returnTableLength <= 0) {
          addReturnRow();
          $('.return-total').html('');
        }
       
      });

      function updateAmount(row) { 
        const qty = parseFloat(row.find('.return-qty').val()) || '';
        const price = parseFloat(row.find('.return-price').val()) || '';
        const amount = qty * price;
        row.find('.return-amount').val(amount.toFixed(2));
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

      function addReturnMobileRow() {
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
                        <td hidden><input type="number"  name="price[]" class="form-control return-price" readonly></></td>
                        <td><input type="number"  name="amount[]" class="form-control return-amount" ></></td>
                        <td hidden><input type="text" name="reason[]"  class="form-control return-reason" /></td>
                        <td>
                          <button type="button" class="btn btn-secondary popoverButton"  data-toggle="popover" data-bs-placement="top" data-html="true">
                            <i class="fa fa-ellipsis-h"></i>
                          </button>
                        </td>
                      </tr>`;
        $('#return-product-body').append(newRow);
      }

      $('.add-return-row').on("click",function() {
        addReturnMobileRow();
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

      function addProductMobileRow(productID, productName, productPrice) {
        var deletedRow = '<i class="fa fa-eye btn btn-info btn-sm view-prod-details" data-bs-toggle="modal" data-bs-target="#prodDetailsModel"></i>';
        var addProductRow = '<tr>\n' +
          '<td><input type="text" name="product_id[]" value="' +productID+'" hidden/><input type="text" value="' +productName+'" data-id="'+productID+'" data-toggle="tooltip" data-placement="top" title="'+ productName+'"  class="form-control p-1 fs-6 productname" readonly></td>\n' +
          '<td><input type="number" name="qty[]" class="form-control p-1 fs-6 qty" ><input type="hidden" name="type[]" value="sales" class="form-control" ></td>\n' +
          '<td hidden><input type="number" value="'+productPrice+'"  name="price[]" class="form-control p-1 fs-6 price" ></td>\n' +
          '<td><input type="number"  name="amount[]" class="form-control p-1 fs-6 amount" ></td>\n' +
          '<td hidden><input type="hidden" name="reason[]" class="form-control p-1 fs-6 reason" ></td>\n' +
          '<td align="center"> <i class="fa fa-trash-o fa-sm btn btn-danger remove"></i></td>\n'+
          '</tr>';
        $('#product-section').append(addProductRow);
      };

      $('.product-select').on("click", function() {
        var productID = $(this).data('id');
        var productName = $(this).data('name');
        var customerID = $('#customer_name').val();
        // console.log(productID, customer);
        if (customerID <= 0) {
          $('#customer-name-error').html("Please Select Customer Name");
          setTimeout(()=> {
            $('#customer-name-error').hide();
          }, 3000)
        } else {
          var isProductExists = false;
          $('#product-section').find('tr').each(function () {
              var ExistingProdID =  $(this).find('.productname').data('id');
              if (ExistingProdID == productID) {
                isProductExists = true;
                return false;
              }
            });
          }

          if (!isProductExists) {
            var prodPrices = prodData.productIdsAndPrices;
            var productPrice = prodPrices[productID].toFixed(2);
            addProductMobileRow(productID, productName, productPrice);
          } else {
            alert ("Already added the Product")
          }
      });

      /* $('.add-return-reason').on("click", function() {
        var parentRow = $(this).closest('tr');
        var value = parentRow.find('select').val();
        console.log(value);
      }); */

      /* $('#returnForm').on('shown.bs.modal', function () {
        $(this).css('z-index', 1051);
      });

      $('#returnReasonForm').on('show.bs.modal', function () {
        $(this).css('z-index', 1060); 
        $('#returnForm').css('z-index', 1050); 
      });

      $(document).on("click", ".add-return-reason", function() {
        var parentRow = $(this).closest('tr');
        
        var selectValue = parentRow.find('select').val();
        
        console.log(selectValue);
      });

      $(document).on("click",'.view-prod-details', function() {
        console.log("Working");
        prodDetailsModel()
      });
       */

      $(document).on('click', 'return-reason', function(){
        var parentRow = $(this).closest('tr');
        parentRow.find('td').first().attr("hidden", false);
        parentRow.find('td').not(':first').attr("hidden", true);
      });


      $(document).on("change", '.return-product-id', function () {
        var selectedValue = $(this).val();

        // $('#return-product-body').find('tr').each(function (){
        //   var existingProductID = $(this).find('select').val();
        //   if (selectedValue == existingProductID) {
        //     $('#return-table-error').html("Selected Product already Added");
        //   } else {
            var parentRow = $(this).closest('tr');
            console.log(selectedValue);
            var popoverContent = `
            <i id="remove-prodID" class="fa fa-remove btn btn-danger btn-sm action-icon remove" title="Remove"></i>
            <i id="edit-prodID" class="fa fa-edit btn btn-success btn-sm add-return-reason" title="Edit"></i>
            <i id="view-prodID" class="fa fa-eye btn btn-info btn-sm action-icon" title="View"></i>
            <i id="addrow-prodID" class="fa fa-plus btn btn-primary btn-sm action-icon add-return-row" title="Add"></i>
            `.replace(/prodID/g, selectedValue);
            var button = parentRow.find('.popoverButton');
            button.popover('dispose'); 
            button.popover({
                html: true,
                content: popoverContent,
                trigger: 'click'
            }).popover();
        //   }
        // });


        

        /* $(document).on('click', '[id^="remove-"], [id^="edit-"], [id^="view-"], [id^="addrow-"]', function() {
          var actionType = $(this).attr('title');
          var productId = $(this).attr('id').split('-')[1]; // Extract product ID from the clicked element's ID
          console.log(`Action: ${actionType}, Product ID: ${productId}`);
          
          $(this).closest('.popover').prev('#popoverButton').popover('hide'); 
        }); */
      });

      $(document).on('click', '[id^="view-"]', function() {
        var productId = $(this).attr('id').split('-')[1];
        var parentRow = $('#return-product-body').find('tr').each(function () {
          var selectedValue = $(this).find('select').val();
          if (selectedValue == productId) {
            var prodName = $(this).find('select option:selected').text();
            var qtyValue = $(this).find('.return-qty').val(); 
            var prodPrice = $(this).find('.return-price').val();
            var totalAmt = $(this).find('.return-amount').val();                    
            var rtnReason = $(this).find('.return-reason').val();
            console.log('Product Name:', prodName);
            console.log('Quantity:', qtyValue);
            console.log('Price:', prodPrice);
            console.log('Total Amount:', totalAmt);
            console.log('Return Reason:', rtnReason);
            setTimeout(()=> {
              $(this).find('.popoverButton').popover('hide'); 
            }, 500);
          }
        });
       
      });

      $(document).on('click', '[id^="remove-"]', function() {
        var productId = $(this).attr('id').split('-')[1];
        var parentRow = $('#return-product-body').find('tr').each(function () {
          var selectedValue = $(this).find('select').val();
          if (selectedValue == productId) {
            $(this).remove();
            setTimeout(()=> {
              $(this).find('.popoverButton').popover('hide'); 
            }, 500);
          }
        });
      });

      $(document).on('click', '[id^="addrow-"]', function() {
        addReturnMobileRow();
        setTimeout(()=> {
          $('#return-product-body').find('.popoverButton').popover('hide'); 
        }, 500);
      });

        

    });
  </script>
@endpush