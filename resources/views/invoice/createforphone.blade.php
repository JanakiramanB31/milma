@extends('layouts.master')

@section('title', 'Invoice | ')
@section('content')
  @include('partials.header')
  @include('partials.sidebar')
  <main class="app-content">
    <!-- <div class="app-title">
      <div>
        <h1><i class="fa fa-edit"></i> Create Invoice</h1>
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item">Invoices</li>
        <li class="breadcrumb-item"><a href="#">Create</a></li>
      </ul>
    </div> -->

    <div class="row">
      <div class="clearix"></div>
      <div class="col-md-12">
        <div class="tile">
          <div id="error-message"></div>
          <div class="d-flex justify-content-between align-items-center">
            <h3 class="tile-title">Invoice</h3>
            <h5 >Date: {{now()->format('d-m-Y')}}</h5>
            <div class="d-flex h-100 justify-content-center align-items-center">
              <p class="mb-0 ">Bal Amt</p><p class="mb-0 mx-2">:</p>
              <b id="bal-amt-symbol" class="h5 mb-0 mr-1"></b><b id="bal-amt" class="h5 mb-0"></b>
            </div>
          </div>

          <!-- Errors Section -->
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
              <!-- Gathering Customer Name and Date -->
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

                <!-- <div class="form-group col-6">
                  <label class="control-label">Date</label>
                  <input name="date"  class="form-control datepicker"  value="<?php echo date('Y-m-d')?>" type="date" placeholder="Enter your email">
                </div> -->
              </div>

              <!-- Return Items Adding Button -->
              <div class="d-flex justify-content-end mb-3">
                <button id="return-button-add" type="button" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#returnForm">
                  <i class="fa fa-plus"></i>
                  <span>Return Items</span>
                </button>
              </div>
          
              <!-- Purchased Product List Table -->
              <div class="row">

                <!-- Purchased Product Adding to Invoice Section --> 
                <div class="col-md-5 mb-5">
                  <div class="table-responsive">
                    <table class="d-table table table-striped ">
                      <thead>
                        <tr>
                          <th scope="col">Product</th>
                          <th scope="col">Quantity</th>
                          <th scope="col" hidden>Price</th>
                          <th scope="col">Amount</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody id="product-section" style="height: 270px;overflow-y: auto;">
                      </tbody>
                      <tfoot>
                        <tr>
                          <td><input type="hidden" name="prev_balance_amt" id="balance-amount" class="form-control balance-amount" /></td>
                          <td hidden></td>
                          <td><input type="hidden" name="total" id="total" class="form-control total" /></td>
                          <td><b>Total</b></td>
                          <td><b class="currency"></b><b class="total"></b></td>
                        </tr>
                      </tfoot>
                    </table>
                    <div id="product-table-error" class="text-danger"></div>
                  </div>
                </div>

                <!-- Total Products List -->
                <div class="col-md-7 mb-5">

                  <!-- Products Search -->
                  <div class="input-group mb-4" style="position: relative;">
                    <div class="input-group-prepend ">
                      <span class="input-group-text icon-container " style="border-right: none;background:transparent">
                        <i style="color: #6c757d;" class="fa fa-search"></i>
                      </span>
                    </div>
                    <input id="product-search" type="text" style="border-left: none;" class="form-control pl-0" placeholder="Search Products..."/>
                  </div>

                  
                  <!-- <select name="product_id[]" class="form-control productname mb-5">
                    <option value = ''>Select Product</option>
                    @foreach($products as $product)
                      <option value="{{$product->id}}">{{$product->name}}</option>
                    @endforeach
                  </select> -->

                  <!-- Products List with Image -->
                  <div class="overflow-y p-1 border border-primary rounded" style="height: 300px;overflow-y: auto;">
                    <div id="product-list" class="d-flex h-100 justify-content-between">
                      @if(count($products) == 0)
                        <div class="d-flex w-100 h-100 justify-content-center align-items-center">
                          <p>No Products Found</p>
                        </div>  
                      @else
                        @foreach($products as $product)
                          <figure>
                            <image class="product-select" data-id="{{$product->id}}" data-name="{{$product->name}}" src={{asset('images/product/' . $product->image)}} width='100px' height='100px'/>
                            <figcaption class="text-center">{{$product->name}}</figcaption>
                          </figure>
                        @endforeach
                      @endif
                    </div>
                  </div>

                </div>

              </div>

              <!-- Return Items Adding PopUp Form --> 
              <div class="modal fade" id="returnForm" tabindex="-1" aria-labelledby="returnFormLabel" aria-hidden="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                  <div class="modal-content ">
                    <!-- Return Items Form Header --> 
                    <div class="modal-header">
                      <h5 class="modal-title" id="returnFormLabel">Return Items</h5>
                      <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i></button>
                    </div>
                    <!-- Return Items Form Content --> 
                    <div class="modal-body table-responsive">
                      <table  class="table table-bordered" >
                        <thead>
                          <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Quantity</th>
                            <th scope="col" hidden>Price</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Actions</th>
                          </tr>
                        </thead>
                        <tbody id="return-product-body">
                          <tr>
                            <td class="d-flex align-items-center" style="gap: 10px;"><b class="return-symbol" style="color: red;" hidden>R</b>
                              <select id="return-product-id" name="product_id[]" class="form-control p-1 return-product-id" >
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
                            <td>
                              <input type="text" name="qty[]"  class="form-control text-center p-1 return-qty">
                              <input type="hidden" name="type[]" value="returns" class="form-control" >
                            </td>
                            <td hidden>
                              <input type="number" name="price[]"  class="form-control p-2 return-price" readonly>
                            </td>
                            <td>
                              <input type="text" name="amount[]"  class="form-control text-center p-1 return-amount" >
                            </td>
                            <td hidden >
                              <p>Reason:</p>
                              <input type="text" name="reason[]"  class="form-control return-reason" />
                            </td>
                            <td hidden> 
                              <i class="fa fa-remove btn btn-danger btn-sm remove"></i>
                            </td>
                            <td align="center">
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
                            <td hidden></td>
                          </tr>
                        </tfoot> 
                      </table>
                      <!-- Return Items Errors -->
                      <div id="return-table-error" class="text-danger"></div>
                    </div>
                    <!-- Return Items Form Footer --> 
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button id="return-entry-button" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Triggering Received Amount Form Section Model Button -->
              <div >
                <button id="product-form-data" type="button" class="btn btn-primary">Submit</button>
              </div>

              <!-- Received Amount PopUp Form -->
              <div class="modal fade" id="amountForm" tabindex="-1" aria-labelledby="amountFormLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md">
                  <div class="modal-content ">
                    <!-- Received Amount PopUp Form Header -->
                    <div class="modal-header d-flex justify-content-center">
                      <h3 class="modal-title text-center" id="amountFormLabel">Enter Received Amount</h3>
                    </div>
                    <!-- Received Amount PopUp Form Content -->
                    <div class="modal-body d-flex flex-column justify-content-center">
                      <input id="received_amt" type="number"  name="received_amt" class="form-control-md" style=" padding:20px;font-size:20px;" min="0"/>
                      <div id="received-amt-error" class="text-danger"></div>
                    </div>
                    <!-- Received Amount PopUp Form Footer -->
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                      <button id="submit-data" class="btn btn-primary" type="submit">Submit</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>

          <!-- Return Reason PopUp Form -->
          <div class="modal fade" id="returnReasonForm" tabindex="-1" aria-labelledby="returnReasonFormLabel" aria-hidden="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
              <div class="modal-content ">
                <!-- Return Reason PopUp Form Header -->
                <div class="modal-header">
                  <h5 class="modal-title" id="returnReasonFormLabel">Add Reason</h5>
                  <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i></button>
                </div>
                <!-- Return Reason PopUp Form Content -->
                <div class="modal-body ">
                  <div>
                    <label>Enter Reason</label>
                    <textarea id="return-reason-entry" name="popup-reason" class="return-popup-reason form-control-lg col-12"></textarea>
                  </div>
                </div>
                <!-- Return Reason PopUp Form Footer -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button id="return-reason-entry-button" type="button" class="btn btn-primary">Save</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Product Details Popup Model -->
          <div class="modal fade" id="productDetailsModal" tabindex="-1" role="dialog" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <!-- Product Details Popup Form Header -->
                <div class="modal-header">
                  <h5 class="modal-title" id="productDetailsModalLabel">Product Details</h5>
                  <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-remove"></i>
                  </button>
                </div>
                <!-- Product Details Popup Form Content -->
                <div class="modal-body table-responsive">
                  <table class="table table-hover">
                    <tr>
                      <td><strong>Product Name:</strong></td>
                      <td><p id="prod-name"></p></td>
                    </tr>
                    <tr>
                      <td><strong>Quantity:</strong></td>
                      <td><p id="prod-qty"></p></td>
                    </tr>
                    <tr>
                      <td><strong>Price:</strong></td>
                      <td class="d-flex"><b id="return-view-price-currency"></b><p id="prod-price"></p></td>
                    </tr>
                    <tr>
                      <td><strong>Return Reason:</strong></td>
                      <td><p id="prod-rtn-reason"></p></td>
                    </tr>
                    <tr>
                      <td><strong>Total Amount:</strong></td>
                      <td class="d-flex"><b id="return-view-tot-currency" ></b><p id="prod-tot-amt"></p></td>
                    </tr>
                  </table>
                </div>
                <!-- Product Details Popup Form Footer -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </main>
  

@endsection
@push('js')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="{{asset('/')}}js/multifield/jquery.multifield.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
 
  <script type="text/javascript">
    var prodData= '';
    var cusID = '' ;
    console.log("Data",prodData)
    $(document).ready(function(){
      $('#bal-amt-symbol').text("€");
      $('#bal-amt').text(parseFloat(0).toFixed(2));
      // Return Items Form PopOver Section Contents
      $('[data-toggle="popover"]').popover({
        html: true,
        content:`
          <i class="fa fa-remove btn btn-danger btn-sm action-icon remove" title="Remove"></i>
          <i class="fa fa-edit btn btn-success btn-sm add-return-reason" title="Edit"></i>
          <i class="fa fa-eye btn btn-info btn-sm action-icon" title="View"></i>
          <i class="fa fa-plus btn btn-primary btn-sm action-icon add-return-row" title="Add"></i>`
      });

      //Triggerring Focus to Quantity When Product Name Change in the Product Section
      $('#product-section').delegate('.productname', 'change', function () {
        var  tr = $(this).parent().parent();
        tr.find('.qty').focus();
      })

      //Find the Selected Product Price Functionality
      $('#product-section').delegate('.productname', 'change', function () {
        var tr =$(this).parent().parent();
        var id = tr.find('.productname').val();
        var prodPrices = prodData.productIdsAndPrices;
        tr.find('.price').val(parseFloat(prodPrices[id]).toFixed(2));
      });

      //Calculating the Total Amount for Selected Product
      $('#product-section').delegate('.qty,.price', 'keyup', function () {
        var tr = $(this).parent().parent();
        var qty = tr.find('.qty').val();
        var price = tr.find('.price').val();
        var amount = (qty * price);
        tr.find('.amount').val(parseFloat(amount).toFixed(2));
        total();
      });

      //Calculating the Total Amount for Selected Product when Foucs in Amount Field
      $('.amount').on('keyup', function () {
        total();
      })

      //Calculating the Total Amount for Selected Product Function
      function total(){
        var salesTotal = 0;
        var returnsTotal = 0;

       //Selling Product Total Amount
        $('#product-section .amount').each(function () {
          var salesAmount =$(this).val()-0;
          salesTotal += salesAmount;
          console.log(salesAmount)
        });

        //Return Product Total Amount
        $('#product-section .return-amount').each(function () {
          var returnsAmount =$(this).val()-0;
          returnsTotal += returnsAmount;
          console.log("Returns Amount",returnsTotal)
        });

        //Calculation the Balance Amount
        var total = salesTotal - returnsTotal;
        console.log("total",total)
        $('.currency').html("€");
        $('.total').html(parseFloat(total).toFixed(2));
        $('#total').val(parseFloat(total).toFixed(2));
      }

      //Adding New Product Row
      $('.addProductRow').on('click', function () {
        addProductRow();
      });

      //Removing Selected Product Functionality 
      $(document).on('click', '.prod-remove', function () {
        var length = $('#product-section').find('tr').length;
        console.log(length)
        if (length == 1) {
          alert('you cant delete last one')
        } else {
          $(this).closest('tr').remove();
        }
      });

      //Fetching Customer Details Functionality
      $('#customer_name').on('change', function() {
        var customerID = $(this).val();
        //console.log(customerID);
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
                console.log("proddata",prodData);
                var balAmount = response.balance_amount.balance_amt ?? 0;
                console.log("Balance Amount", balAmount)
                $('#bal-amt-symbol').text("€")
                $('#bal-amt').text(parseFloat(balAmount).toFixed(2));
                $('#balance-amount').val(balAmount);
                $('#return-product-id').empty().append('<option value="">Select Return Product</option>');
                if (response.products.length > 0) {
                  
                  $.each(response.products, function(index, product) {
                    $('#return-product-id').append('<option value="' + product.id + '">' + product.name + '</option>');
                  });
                } else {
                  $('#return-product-id').append('<option value="">No products available</option>');
                }
                //console.log(response.quantityAndPrices);
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

      //Fetch Return Product Details
      $('#return-product-body').on('change', '.return-product-id', function() {
        var selectedProductID = $(this).val(); 
        var row = $(this).closest('tr');

        if (selectedProductID) {
          var productDetails = prodData.quantityAndPrices.find(item => item.product_id == selectedProductID);
          if (productDetails) {
            row.find('.return-price').val(productDetails.price);
          } else {
            row.find('.return-price').val('');
          }
        }
      });

      //Calculating Total Amount for Each Return Products
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

      //Calculating Total Amount for All Return Products Function
      function returnTotal(){
        var total = 0;
        $('.return-amount').each(function (i,e) {
          var amount = $(this).val()-0;
          total += amount;
        });
        $('.return-currency').html("€");
        $('.return-total').html(parseFloat(total).toFixed(2));
      }

      //Adding New Return Product Row
      $('#return-button-add').on("click", function() {
        var returnTableLength = $('#return-product-body').find('tr').length;
        if (returnTableLength <= 0) {
          addReturnMobileRow();
          $('.return-total').html('');
        }
      });

      //Return Product Row for Mobile Responsiveness
      function addReturnMobileRow() {
        var newRow = `
          <tr>
            <td class="d-flex align-items-center" style="gap: 10px;">
              <b class="return-symbol" style="color: red;" hidden>R</b>
              <select name="product_id[]" id="return-product-id" class="form-control p-1 return-product-id return-product-name">
                <option value="">Select Return Product</option>
                @foreach($products as $product)
                  <option value="{{$product->id}}">{{ $product -> name}}</option>
                @endforeach
              </select>
            </td>
            <td>
              <input type="text"  name="qty[]" class="form-control text-center p-1 return-qty" />
              <input type="hidden" name="type[]" value="returns" class="form-control" />
            </td>
            <td hidden>
              <input type="number"  name="price[]" class="form-control return-price" readonly/>
            </td>
            <td>
              <input type="text"  name="amount[]" class="form-control text-center p-1 return-amount" />
            </td>
            <td hidden>
              <input type="text" name="reason[]"  class="form-control return-reason" />
            </td>
            <td align="center">
              <button type="button" class="btn btn-secondary popoverButton"  data-toggle="popover" data-bs-placement="top" data-html="true">
                <i class="fa fa-ellipsis-h"></i>
              </button>
            </td>
          </tr>`;
        $('#return-product-body').append(newRow);
      }

      //Adding New Return Product Row
      $('.add-return-row').on("click",function() {
        addReturnMobileRow();
      });

      //Removing Return Product Row
      $('#return-product-body').on('click', '.remove', function() {
        $(this).closest('tr').remove();
      });

      //Adding Return Items Details to Invoice Form Functionality
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
          returnSection.each(function() {
            $(this).find('td').last().html('<i class="fa fa-trash-o fa-sm btn btn-danger prod-remove"></i>');
          });
          /* returnSectionModified = returnSection.find('tr').last('td').each(function (){
            $(this).remove();
            $(this).parent().add('td').val('<td align="center"><i class="fa fa-trash-o fa-sm btn btn-danger prod-remove"></i></td>')
          }) */
          $('.return-symbol').attr("hidden", false);
          $('#product-section').append(returnSection);
          total();
          $('.return-total').html('');
          $('#returnForm').modal('hide');
          addReturnMobileRow();
        }
      });

      $('#received_amt').on('change', function (){
        let receivedAmount = $(this).val();
        $(this).val(parseFloat(receivedAmount).toFixed(2));
      });

      //After Submitting Return Items to Invoice Make this to Non Editable
      $('#submit-data').on('click', function () {
        let receivedAmt = $('#received_amt').val();
        if(receivedAmt) {
          $(this).attr("disabled", false)
          $('#product-section').find('tr').each(function (){
            $(this).find('select').attr('disabled', false);
            $(this).find('input').attr('readonly', false);
          });
          setTimeout(()=>{
            $('#product-section').find('tr').each(function (){
              $(this).find('select').attr('disabled', true);
              $(this).find('input').attr('readonly', true);
            });
          }, 3000);
        } else {
          $(this).attr("disabled", true);
          setTimeout(()=> {
            $(this).attr("disabled", false);
          }, 3000)
          $('#received-amt-error').html("Please enter Amount");
          $('#received-amt-error').show();
          setTimeout(()=> {
            $('#received-amt-error').hide();
          }, 3000)
        }
      });

      //Adding New Invoice Product Row
      function addProductMobileRow(productID, productName, productPrice) {
        var addProductRow = '<tr>\n' +
          '<td><input type="text" name="product_id[]" value="' +productID+'" hidden/><input type="text" value="' +productName+'" data-id="'+productID+'" data-toggle="tooltip" data-placement="top" title="'+ productName+'"  class="form-control p-1 fs-6 productname" readonly></td>\n' +
          '<td><input type="text" name="qty[]" class="form-control text-center p-1 fs-6 qty" ><input type="hidden" name="type[]" value="sales" class="form-control" ></td>\n' +
          '<td hidden><input type="number" value="'+productPrice+'"  name="price[]" class="form-control p-1 fs-6 price" ></td>\n' +
          '<td><input type="text"  name="amount[]" class="form-control text-center p-1 fs-6 amount" ></td>\n' +
          '<td hidden><input type="hidden" name="reason[]" class="form-control p-1 fs-6 reason" ></td>\n' +
          '<td align="center"><i class="fa fa-trash-o fa-sm btn btn-danger prod-remove"></i></td>\n'+
          '</tr>';
        $('#product-section').append(addProductRow);
      };

      //Validating Customer Selected and Avoiding Same Products selecting Multiple Times Function  
      $(document).on("click", '.product-select', function() {
        var productID = $(this).data('id');
        var productName = $(this).data('name');
        var customerID = parseInt($('#customer_name').val(), 10);
        console.log(customerID)

        if ( isNaN(customerID)|| customerID <= 0) {
          $('#customer-name-error').html("Please Select Customer Name");
          $('#customer-name-error').show();
          setTimeout(()=> {
            $('#customer-name-error').hide();
          }, 3000)
        } else {
          $('#customer-name-error').hide();
          var isProductExists = false;
          $('#product-section').find('tr').each(function () {
            var ExistingProdID =  $(this).find('.productname').data('id');
            if (ExistingProdID == productID) {
              isProductExists = true;
              return false;
            }
          });

          if (!isProductExists) {
          var prodPrices = prodData.productIdsAndPrices;
          var productPrice = parseFloat(prodPrices[productID]).toFixed(2);
          addProductMobileRow(productID, productName, productPrice);
          } else {
            alert('Already added the Product');
            $('.toast-body').text("Already added the Product");
            $('.toast').toast('show');
          }
        }
      });

      //Avoiding Same Products selecting Multiple Times in the Return Form Function
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
      });

      //Adding New Row Functionality in the Return Form
      $(document).on('click', '[id^="addrow-"]', function() {
        addReturnMobileRow();
        setTimeout(()=> {
          $('#return-product-body').find('.popoverButton').popover('hide'); 
        }, 500);
      });

      //Adding Selected Product Reason PopUp Form Functionality in the Return Form
      $(document).on('click', '[id^="edit-"]', function() {
        var productId = $(this).attr('id').split('-')[1];

        $('#returnReasonForm').modal('show');

        $('#returnReasonForm').data('productID', productId);

        setTimeout(()=> {
          $('#return-product-body').find('.popoverButton').popover('hide'); 
        }, 500);
      });

      //Storing PopUp Form Reason Value to Selected Product Return Value Functionality in the Return Form
      $(document).on('click', '#return-reason-entry-button', function () {
        var returnReason = $('#return-reason-entry').val();
        var productID = $('#returnReasonForm').data('productID');
        var found = false;

        $('#return-product-body').find('tr').each(function () {
          var selectValue = $(this).find('select').val();
          if (selectValue == productID) {
            $(this).find('.return-reason').val(returnReason);
            $('#returnReasonForm').modal('hide');
            $('#return-reason-entry').val('');
            found = true;
            return false;
          }
        });
      });

      //Showing Selected Product Details Functionality in the Return Form
      $(document).on('click', '[id^="view-"]', function() {
        console.log($.fn.modal);
        var productId = $(this).attr('id').split('-')[1];
        var parentRow = $('#return-product-body').find('tr').each(function () {
          var selectedValue = $(this).find('select').val();
          if (selectedValue == productId) {
            var prodName = $(this).find('select option:selected').text();
            var qtyValue = $(this).find('.return-qty').val(); 
            var prodPrice = $(this).find('.return-price').val();
            var totalAmt = $(this).find('.return-amount').val();                    
            var rtnReason = $(this).find('.return-reason').val();

            $('#prod-name').text(prodName);
            $('#prod-qty').text(qtyValue);
            $('#return-view-price-currency').text('$');
            $('#prod-price').text(parseFloat(prodPrice).toFixed(2));
            $('#prod-rtn-reason').text(rtnReason);
            $('#return-view-tot-currency').text('$');
            $('#prod-tot-amt').text(parseFloat(totalAmt).toFixed(2));

            $('#productDetailsModal').modal('show');

            setTimeout(()=> {
              $(this).find('.popoverButton').popover('hide'); 
            }, 500);
          }
        });
      });

      //Removing Selected Product Details Functionality in the Return Form
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

      //Searching Products
      $('#product-search').on('keyup', function () {
        var searchText = $(this).val().toLowerCase();
        //console.log(searchText)

        $('#product-list figure').each(function () {
          var productName = $(this).find('figcaption').text().toLowerCase();
          if(productName.includes(searchText)) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
      });

      //Adding Border to Search Icon
      $('#product-search').on('click', function () {
        $('.input-group-text').addClass("border-2 border-primary");
      });      

      //Removing Border to Search Icon
      $('#product-search').on('blur', function () {
        $('.input-group-text').removeClass("border-2 border-primary");
      });

      //Validating Product Data
      $('#product-form-data').on('click', function() {
        var customerID = parseInt($('#customer_name').val(), 10);
        if ( isNaN(customerID)|| customerID <= 0) {
          $('#customer-name-error').html("Please Select Customer Name");
          $('#customer-name-error').show();
          setTimeout(()=> {
            $('#customer-name-error').hide();
          }, 3000)
        } else {
          var productTableBody = $('#product-section');
          let allFilled = true;
          let productsCount = productTableBody.find('tr').length;
          console.log(productsCount)
          if(productsCount == 0) {
            //alert("Please add minimum of 1 products");
            $('#product-table-error').html("Please add minimum of 1 product");
            $('#product-table-error').show();
            setTimeout(()=> {
              $('#product-table-error').hide();
            }, 3000)
          } else {
            productTableBody.find('tr').each(function () {
              let productID = $(this).find('.productname').data('id');
              console.log("productID",productID)
              let productQty = $(this).find('.qty').val();
              // console.log("productID", productID)
              // console.log("productQty", productQty)

              if (productID == "") {
                console.log("productID",productID)
                $('#product-table-error').html("Please Select the Product");
                $('#product-table-error').show();
                setTimeout(()=> {
                  $('#product-table-error').hide();
                }, 3000);
                allFilled = false; 
                return false;
              } else if (productQty == "") {
                $('#product-table-error').html("Please Enter the Quantity");
                $('#product-table-error').show();
                setTimeout(()=> {
                  $('#product-table-error').hide();
                }, 3000)
                allFilled = false; 
                return false;
              } else {
                allFilled = true; 
                return true;
              }
            });

            if(allFilled) {
              $('#amountForm').modal('show');
            }
          }

        }
      });
      
    });
  </script>
@endpush
