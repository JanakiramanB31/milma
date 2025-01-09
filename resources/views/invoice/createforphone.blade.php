@extends('layouts.master')

@section('title', 'Receipt | ')
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
          <!-- Page Header Section -->
          <div class="d-flex justify-content-between align-items-center">
            <h3 class="tile-title mb-0">{{$userRole == "admin" ? "Invoice" : "Receipt"}}</h3>
            <h5 class="mb-0">Date: <p class="mx-1 mb-0 d-inline"></p>{{now()->format('d-m-Y')}}</h5>
          </div>
          <div class="d-flex justify-content-end align-items-center mt-2">
            <div class="d-flex h-100 justify-content-center align-items-center">
              <p class="mb-0 ">Bal Amt:</p><p class="mb-0 mx-1 d-inline"></p>
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

          <!-- Alert Error Section -->
          <div id="alert-container" class="alert alert-danger alert-dismissible fade show" role="alert" hidden>
            <strong id="alert-message" ></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <!-- Form Section -->
          <div class="tile-body">
            <form  method="POST" action="{{route('invoice.store')}}">
              @csrf
              <!-- Gathering Customer Name and Date -->
              <div class="row" >
                <div class="form-group col">
                  <label class="control-label">Company Name</label>
                  <select name="customer_id" class="form-control select2" id="customer_name" data-live-search="true">
                    <option value = '0'>Select Customer</option>
                    @foreach($customers as $customer)
                    <option name="customer_id" value="{{$customer->id}}">{{$customer->company_name}} </option>
                    @endforeach
                  </select>
                  <div id="customer-name-error" class="text-danger"></div> 
                </div>

                <!-- <div class="form-group col-6">
                  <label class="control-label">Date</label>
                  <input name="date"  class="form-control datepicker"  value="" type="date" placeholder="Enter your email">
                </div> -->
              </div>

              <!-- Return Items Adding Button -->
              <div class="d-flex justify-content-end mb-3">
                <button id="return-button-add" type="button" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#returnForm">
                  <i class="fa fa-plus"></i>
                  <span>Return</span>
                </button>
              </div>
          
              <!-- Purchased Product List Table -->
              <div class="row">

                <!-- Purchased Product Adding to Invoice Section --> 
                <div class="col-md-5 mb-5">
                  <div class="table-responsive">
                    <table class="d-table table table-striped " style="border-collapse: collapse;">
                      <thead>
                        <tr>
                          <th scope="col" class="col-4">Product</th>
                          <th scope="col" class="col-2">Qty</th>
                          <th scope="col" hidden>Price</th>
                          <th scope="col" class="col-5">Amt</th>
                          <th scope="col" class="col-1">Action</th>
                        </tr>
                      </thead>
                      <tbody id="product-section" style="height: 270px;overflow-y: auto;">
                      </tbody>
                      <tfoot>
                        <tr>
                          <td><input type="hidden" name="acc_bal_amt" id="balance-amount" class="form-control balance-amount" /></td>
                          <td hidden></td>
                          <td><input type="hidden" name="total" id="total" class="form-control total" /></td>
                          <td><b>Total</b></td>
                          <td><b class="currency"></b><b class="total" id="purchase-tot"></b></td>
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
                    <div id="product-list" class="d-flex flex-wrap h-100 " style="gap: 10px;">
                      @if(count($products) == 0)
                        <div class="d-flex w-100 h-100 justify-content-center align-items-center">
                          <p>No Products Found</p>
                        </div>  
                      @else
                        @foreach($products as $product)
                          <figure class="flex-{grow|shrink}-1">
                            <image class="product-select" data-id="{{$product->id}}" data-name="{{$product->name}}" src={{asset('images/product/' . $product->image)}} width='50px' height='50px'/>
                            <figcaption style="width: 50px;"><p class="d-inline" style=" white-space: normal;word-wrap: break-word;overflow-wrap: break-word;">{{$product->name}}<p class="d-inline">-</p><b>{{$product->quantity}}</b><p class="d-inline">({{$product->unit->name}})</p></p></figcaption>
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
                      <table  class="table table-bordered" style="border-collapse: collapse;">
                        <thead>
                          <tr>
                            <th scope="col" class="col-4">Product</th>
                            <th scope="col" class="col-2">Qty</th>
                            <th scope="col" hidden>Price</th>
                            <th scope="col" class="col-5">Amt</th>
                            <th scope="col" class="col-1">Actions</th>
                          </tr>
                        </thead>
                        <tbody id="return-product-body">
                          <tr>
                            <td class="d-flex align-items-center p-1" style="gap: 10px;"><b class="return-symbol" style="color: red;" hidden>R</b>
                              <select id="return-product-id" name="product_id[]" class="form-control p-1 return-product-id" >
                                <option value =''>Select Return Product</option>
                                @if(session('routeEmptyError'))
                                  <option value = ''>{{session('routeEmptyError')}}</option>
                                @elseif(count($returnProducts) == 0)
                                  <option value = ''>No Products Found</option>
                                @else
                                  @foreach($returnProducts as $returnProduct)
                                  <option value="{{$returnProduct->id}}" data-id="{{$returnProduct->id}}">{{$returnProduct->name}}</option>
                                  @endforeach
                                @endif
                              </select>
                            </td>
                            <td class="p-1">
                              <input type="text" name="qty[]"  class="form-control text-center p-1 return-qty">
                              <input type="hidden" name="type[]" value="returns" class="form-control" >
                            </td>
                            <td hidden>
                              <input type="text" name="price[]"  class="form-control p-2 return-price" readonly>
                            </td>
                            <td class="p-1">
                              <input type="text" name="amount[]"  class="form-control text-center p-1 return-amount" >
                            </td>
                            <td hidden >
                              <p>Reason:</p>
                              <input type="text" name="reason[]"  class="form-control return-reason" />
                            </td>
                            <td hidden> 
                              <i class="fa fa-remove btn btn-danger btn-sm remove"></i>
                            </td>
                            <td align="center" class="p-1">
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
                            <td><b class="return-currency"></b><b name="return-total" class="return-total"></b></td>
                            <td hidden></td>
                          </tr>
                        </tfoot> 
                      </table>
                      <!-- Return Items Errors -->
                      <div id="return-table-error" class="text-danger"></div>
                    </div>
                    <!-- Return Items Form Footer --> 
                    <div class="modal-footer d-flex justify-content-center">
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                      <button id="return-entry-button" type="button" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>

             <!--  Triggering Received Amt Form Button and Close Button  -->
              <div class="d-flex justify-content-center" style="gap: 10px;">
                <!-- Back to Index Page Button -->
                <div>
                  <button id="invoice-close-btn" type="button" class="btn btn-danger">Close</button>
                </div>

                <!-- Triggering Received Amount Form Section Model Button -->
                <div >
                  <button id="product-form-data" type="button" class="btn btn-primary">Submit</button>
                </div>
              </div>

              <!-- Received Amount PopUp Form -->
              <div class="modal fade" id="amountForm" tabindex="-1" aria-labelledby="amountFormLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md">
                  <div class="modal-content ">
                    <!-- Received Amount PopUp Form Header -->
                    <div class="modal-header d-flex justify-content-center">
                      <h3 class="modal-title text-center" id="amountFormLabel">Enter Received Amount</h3>
                    </div>
                    <div style="padding:18px;">
                      <label class="form-label">Payment Type</label>
                      <select id="payment_type" name="payment_type" class="form-control">
                        <option value = ''>Select Payment Type</option>
                        @foreach($paymentMethods as $paymentMethod)
                        <option name="payment_type"  value="{{$paymentMethod}}" @if($paymentMethod == 'Cash') selected @endif>{{$paymentMethod}}</option>
                        @endforeach
                      </select>
                      <div id="payment-type-error" class="text-danger"></div>
                    </div>
                    <!-- Received Amount PopUp Form Content -->
                    <div class="modal-body d-flex flex-column justify-content-center">
                    <label class="form-label">Amount</label>
                      <input id="received_amt" type="text"  name="received_amt" class="form-control" style=" padding:20px;font-size:20px;" min="0"/>
                      <div id="received-amt-error" class="text-danger"></div>
                    </div>
                    <!-- Received Amount PopUp Form Footer -->
                    <div class="modal-footer d-flex justify-content-center">
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
                <div class="modal-footer  d-flex justify-content-center">
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
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
                <!-- <div class="modal-footer  d-flex justify-content-center">
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div> -->
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript">
    var prodData= '';
    var cusID = '' ;
    var returnProducts = [];
    var selectedReturnProductIDs = [];
    //console.log("Data",prodData)
    $(document).ready(function(){
      $('#alert-container').attr("hidden", false);
      $('#alert-container').hide();
      $('.select2').select2();
      $('#bal-amt-symbol').text("£");
      $('#bal-amt').text(parseFloat(0).toFixed(2));
      $('[data-toggle="tooltip"]').tooltip();

      //Number Input Field Up and Down Arrow Hiding Style
      $('input[type=number]').css({
        '-moz-appearance': 'textfield', 
        '-webkit-appearance': 'none', 
        'appearance': 'none' 
      });

      // For WebKit browsers to hide the spin buttons
      $('input[type=number]').on('focus', function() {
        $(this).css({
          '-webkit-appearance': 'none',
          'margin': '0'
        });
      });

      //Accept Only Number Input In Quantity Field 
      $(document).on('input','.return-qty, .qty', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
      });

      //Accept Only Float Number Price In Quantity Field 
      $(document).on('input', '.return-price, .price,  #received_amt', function() {
        this.value = this.value.replace(/[^0-9.]/g, '');
        
        const parts = this.value.split('.');
        if (parts.length > 2) {
          this.value = parts[0] + '.' + parts.slice(1).join('');
        }
      });

      //Product Name Showing in Tooltip
      $(document).on('click','.productname', function() {
        var toolTip = $(this);
        //console.log(this)
        toolTip.tooltip('show');
        setTimeout(function() {
          toolTip.tooltip('hide');
        }, 3000); 
      });

      //Return Product Name Showing in Tooltip
      $(document).on('click', ' .return-product-id', function() {
        var toolTip = $(this);
        //console.log(toolTip)
        var selectedOption = toolTip.find('option:selected');
        if (selectedOption.length && selectedOption.val() !== '') {
          var value = selectedOption.text();
          toolTip.attr('title', value);
          toolTip.tooltip('show');
          setTimeout(function() {
            toolTip.tooltip('hide');
          }, 3000);
        } else {
            console.log('No option selected.');
        }
      });

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
        //console.log(prodData)
        var prodPrices = prodData.productIdsAndPrices;
        var productPrice = '';
        if(prodPrices[productID]) {
          productPrice = prodPrices[productID];
        } else {
          productPrice = prodData.prodIDsAndBasePrices[productID];
          $('#alert-message').text("The selected product rate type price is not available, so the base rate has been applied instead.");
          $('#alert-container').show();
        }
        tr.find('.price').val(productPrice);
      });

      //Calculating the Total Amount for Selected Product
      $('#product-section').delegate('.qty,.price', 'keyup', function () {
        var tr = $(this).parent().parent();
        var qty = tr.find('.qty').val();
        var priceVal = tr.find('.price').val();
        var price = priceVal;
        //console.log("Checking Price",price)
        var amount = (qty * price);
        tr.find('.amount').val(amount ? amount : 0);
        total();
      });

      //Calculating the Total Amount for Selected Product when Foucs in Amount Field
      $('.amount').on('keyup', function () {
        total();
      });

      //Calculating the Total Amount for Selected Product Function
      function total(){
        var salesTotal = 0;
        var returnsTotal = 0;

       //Selling Product Total Amount
        $('#product-section .amount').each(function () {
          var salesAmount =$(this).val()-0;
          salesTotal += salesAmount;
          //console.log(salesAmount)
        });

        //Return Product Total Amount
        $('#product-section .return-amount').each(function () {
          var returnsAmount =$(this).val()-0;
          returnsTotal += returnsAmount;
          //console.log("Returns Amount",returnsTotal)
        });

        //Calculation the Balance Amount
        var total = salesTotal - returnsTotal;
        //console.log("total",total)
        $('.currency').html("£");
        $('.total').html(parseFloat(total ? total : 0).toFixed(2));
        $('#total').val(parseFloat(total ? total : 0).toFixed(2));
      }

      //Adding New Product Row
      $('.addProductRow').on('click', function () {
        addProductRow();
      });

      //Removing Selected Product Functionality 
      $(document).on('click', '.prod-remove', function () {
        const row = $(this).closest('tr');
        deleteProductRow(function(confirmDelete) {
          if (confirmDelete) {
            row.remove();
            total();
          }
        });
      });

      //Fetching Customer Details Functionality
      $('#customer_name').on('change', function() {
        fetchReturnProducts();
      });

      //Fetch Return Products Function
      function fetchReturnProducts() {
        var customerID = $('#customer_name').val();
        //console.log(customerID);
        cusID = customerID;
        if(customerID) {
          $('#customer-name-error').hide();
          $.ajax({
            url: '{{ route("invoice.getProducts",":id") }}'.replace(':id', customerID),
            type: 'POST',
            data: {
              customer_id: customerID,
              _token: '{{ csrf_token() }}' 
            },
            success: function(response) {
              try {
                //console.log("Working",response);
                prodData= response;
                console.log("proddata",prodData);
                var balAmount = response.balance_amount.balance_amt ?? 0;
                //console.log("Balance Amount", balAmount)
                $('#bal-amt-symbol').text("£")
                $('#bal-amt').text(parseFloat(balAmount).toFixed(2));
                $('#balance-amount').val(parseFloat(balAmount).toFixed(2));
                $('.return-product-id').empty().append('<option value="">Select Return Product</option>');
                if (response.returnProducts.length > 0) {
                  returnProducts = response.returnProducts;
                  //console.log("returnProducts",returnProducts);
                  $.each(response.returnProducts, function(index, returnProduct) {
                    $('.return-product-id').append('<option value="' + returnProduct.id + '">' + returnProduct.name + '</option>');
                  });
                } else {
                  $('.return-product-id').append('<option value="">No products available</option>');
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
      }


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
        tr.find('.return-amount').val(amount);
        returnTotal();
        $('#return-entry-button').on("click", function(){
          $('#return-product-name-entry').val();
          $('#return-qty-entry').val(qty);
          $('#return-price-entry').val(price);
        });
      });

      //Calculating Total Amount for All Return Products Function
      function returnTotal(){
        var total = 0;
        $('#return-product-body .return-amount').each(function (i,e) {
          var amount = $(this).val()-0;
          total += amount;
        });
        $('.return-currency').html("£");
        $('.return-total').html(parseFloat(total ? total : 0).toFixed(2));
      }

      //Adding New Return Product Row
      $('#return-button-add').on("click", function() {
        var returnTableLength = $('#return-product-body').find('tr').length;
        $('.return-total').html('');
        if (returnTableLength <= 0) {
          fetchReturnProducts();
          addReturnMobileRow();
          $('.return-total').html('');
        }
      });

      //Return Product Row for Mobile Responsiveness
      function addReturnMobileRow() {

        var returnOptions = returnProducts.map((item) => {
          return `<option value="${item.id}" data-id="${item.id}">${item.name}</option>`;
        }).join('');

        var newRow = `
          <tr>
            <td class="d-flex align-items-center p-1" style="gap: 10px;"><b class="return-symbol" style="color: red;" hidden>R</b>
              <select id="return-product-id" name="product_id[]" class="form-control p-1 return-product-id" data-toggle="tooltip" data-placement="top" aria-label="Select Return Product">
                <option value =''>Select Return Product</option>
                ${returnOptions}
              </select>
            </td>
            <td class="p-1">
              <input type="text"  name="qty[]" class="form-control text-center p-1 return-qty" />
              <input type="hidden" name="type[]" value="returns" class="form-control" />
            </td>
            <td hidden>
              <input type="text"  name="price[]" class="form-control return-price" readonly/>
            </td>
            <td class="p-1">
              <input type="text"  name="amount[]" class="form-control text-center p-1 return-amount" />
            </td>
            <td hidden>
              <input type="text" name="reason[]"  class="form-control return-reason" />
            </td>
            <td align="center" class="p-1">
              <button type="button" class="btn btn-secondary popoverButton"  data-toggle="popover" data-bs-placement="top" data-html="true">
                <i class="fa fa-ellipsis-h"></i>
              </button>
            </td>
          </tr>`;
        $('#return-product-body').append(newRow);
      }

      //Adding New Return Product Row
      $('.add-return-row').on("click",function() {
        fetchReturnProducts();
        addReturnMobileRow();
      });

      //Removing Return Product Row
      $('#return-product-body').on('click', '.remove', function() {
        $(this).closest('tr').remove();
        returnTotal();
      });

      //Adding Return Items Details to Invoice Form Functionality
      $('#return-entry-button').on("click", function () {
        var customerID = parseInt($('#customer_name').val(), 10);
        if ( isNaN(customerID)|| customerID <= 0) {

          $('#customer-name-error').html("Please select Customer Name");
          $('#return-table-error').html("Please select Customer Name");
          $('#customer-name-error').show();
          $('#return-table-error').show();
          setTimeout(()=> {
            $('#customer-name-error').hide();
            $('#return-table-error').hide();
          }, 3000);
        }
        else {
          var returnSection = $('#return-product-body').find('tr');
          let allFilled = true;
          let productSet = new Set(); 

          returnSection.each(function () {
            var selectedValue = $(this).val();
            $(this).val(selectedValue);
            var inputValue = $(this).find('input').val();
            var selectValue = $(this).find('select').val();

            if ( selectValue.length == 0) {
              $('#return-table-error').html("Please Select the Product");
              $('#return-table-error').show();
              setTimeout(()=> {
                $('#return-table-error').hide();
              }, 3000);
              allFilled = false; 
              return false;
            } else if (inputValue.length == 0 || inputValue == 0) {
              $('#return-table-error').html("Please Enter the Quantity");
              $('#return-table-error').show();
              setTimeout(()=> {
                $('#return-table-error').hide();
              }, 3000);
              allFilled = false; 
              return false;
            } else {
              if (productSet.has(selectValue)) {
                $('#return-table-error').text("Same Product added multiple times");
                $('#return-table-error').show();
                setTimeout(() => {
                    $('#return-table-error').hide();
                }, 3000);
                allFilled = false;
                return false;
              } else {
                productSet.add(selectValue);
              }
            }
          });

          if (allFilled) {
            returnSection.each(function() {
              $(this).find('td').last().html('<i class="fa fa-trash-o fa-sm btn btn-danger prod-remove"></i>');
              $(this).find('input').attr('readonly', true);
              $(this).find('select').attr('disabled', true);
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
        }
      });

      $('#received_amt').on('change', function (){
        let receivedAmount = $(this).val();
        $(this).val(receivedAmount ? receivedAmount : 0);
      });

      $('#payment_type').on('change', function () {
        let paymentType = $(this).val();
        let totalAmount = $('#purchase-tot').text();
        $('#received_amt').val(totalAmount);
      })

      //After Submitting Return Items to Invoice Make this to Non Editable
      $('#submit-data').on('click', function () {
        let paymentType = $('#payment_type').val();
        let receivedAmt = $('#received_amt').val();
        if(!paymentType){
          $(this).attr("disabled", true);
          $('#payment-type-error').html("Please select Payment Type");
          $('#payment-type-error').show();
          setTimeout(()=> {
            $(this).attr("disabled", false);
            $('#payment-type-error').hide();
          }, 3000);
        } else if(receivedAmt) {
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
          '<td class="p-1"><input type="text" name="product_id[]" value="' +productID+'" hidden/><input type="text" value="' +productName+'" data-id="'+productID+'" data-toggle="tooltip" data-placement="top" title="'+ productName+'"  class="form-control p-1 productname" readonly></td>\n' +
          '<td class="p-1"><input type="text" name="qty[]" style="-moz-appearance: textfield;" data-id="'+productID+'" data-prodname="' +productName+'" class="form-control text-center p-1 fs-6 qty" ><input type="hidden" name="type[]" value="sales" class="form-control" ></td>\n' +
          '<td hidden><input type="text" value="'+productPrice+'"  name="price[]" class="form-control p-1 fs-6 price" ></td>\n' +
          '<td class="p-1"><input type="text"  name="amount[]" class="form-control text-center p-1 fs-6 amount" ></td>\n' +
          '<td hidden><input type="hidden" name="reason[]" class="form-control p-1 fs-6 reason" ></td>\n' +
          '<td class="p-1" align="center"><i class="fa fa-trash-o fa-sm btn btn-danger prod-remove" data-id="' +productID+'" ></i></td>\n'+
          '</tr>';
        $('#product-section').append(addProductRow);
      };

      //Validating Customer Selected and Avoiding Same Products selecting Multiple Times Function  
      $(document).on("click", '.product-select', function() {
        var productID = $(this).data('id');
        var productName = $(this).data('name');
        var customerID = parseInt($('#customer_name').val(), 10);
        //console.log(customerID)

        if ( isNaN(customerID)|| customerID <= 0) {

          $('#customer-name-error').html("Please select Customer Name");
          $('#customer-name-error').show();
          let cusErr = $('#customer-name-error').text().length;
          if(cusErr > 0) {
            $('html, body').animate({
              scrollTop: $('#customer_name').offset().top - 300
            }, 500);
          }
          setTimeout(()=> {
            $('#customer-name-error').hide();
          }, 3000);
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
            //console.log("Error Checking",prodData)
          var prodPrices = prodData.productIdsAndPrices;
          var productPrice = '';
          if(prodPrices[productID]) {
            productPrice = prodPrices[productID];
          } else {
            productPrice = prodData.prodIDsAndBasePrices[productID];
            $('#alert-message').text("The selected product rate type price is not available, so the base rate has been applied instead.");
            $('#alert-container').show();
          }
          addProductMobileRow(productID, productName, productPrice);
          } else {
            $('#alert-message').text("Already added the Product");
            $('#alert-container').show();
            let isErr = $('#alert-message').text().length;
            if (isErr > 0) {
              $('html, body').animate({
                  scrollTop: $('#alert-message').offset().top - 300
              }, 500);
            }
            setTimeout(()=> {
              $('#alert-container').hide();
            }, 3000);
            //alert('Already added the Product');
            $('.toast-body').text("Already added the Product");
            $('.toast').toast('show');
          }
        }
      });

      //Avoiding Same Products selecting Multiple Times in the Return Form Function
      $(document).on("change", '.return-product-id', function () {
        var selectedValue = $(this).val();
        var parentRow = $(this).closest('tr');
        //console.log(selectedValue);
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
            $('#return-view-price-currency').text('£');
            $('#prod-price').text(prodPrice);
            $('#prod-rtn-reason').text(rtnReason);
            $('#return-view-tot-currency').text('£');
            $('#prod-tot-amt').text(totalAmt ? totalAmt : 0);
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
          const row = $(this);
          var selectedValue = $(this).find('select').val();
          if (selectedValue == productId) {
            deleteProductRow(function(confirmDelete) {
              if (confirmDelete) {
                row.remove();
                returnTotal();
              }
            });            
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
          $('#customer-name-error').html("Please select Customer Name");
          $('#customer-name-error').show();
          let cusErr = $('#customer-name-error').text().length;
          if(cusErr > 0) {
            $('html, body').animate({
              scrollTop: $('#customer_name').offset().top - 300
            }, 500);
          }
          setTimeout(()=> {
            $('#customer-name-error').hide();
          }, 3000);
        } else {
          var productTableBody = $('#product-section');
          let allFilled = true;
          let allValid = true;
          let productsCount = productTableBody.find('tr').length;
          //console.log(productsCount)
          if (productsCount == 0) {
            $('#alert-message').text("Please add minimum of 1 Product");
            $('#alert-container').show();
            let isErr = $('#alert-message').text().length;
            if (isErr > 0) {
              $('html, body').animate({
                  scrollTop: $('#alert-message').offset().top - 300
              }, 500);
            }
            setTimeout(()=> {
              $('#alert-container').hide();
            }, 3000);
          } else {
            productTableBody.find('tr').each(function () {
              $('#product-table-error').hide();
              let productID = $(this).find('.productname').data('id');
              //console.log("productID",productID)
              let productQty = $(this).find('.qty').val();

              if (productID == "") {
                //console.log("productID",productID);
                $('#alert-message').text("Please select the Product");
                $('#alert-container').show();
                let isErr = $('#alert-message').text().length;
                  if (isErr > 0) {
                  $('html, body').animate({
                      scrollTop: $('#alert-message').offset().top - 300
                  }, 500);
                }
                setTimeout(()=> {
                  $('#alert-container').hide();
                }, 3000);
                allFilled = false; 
                allValid = false;
                return false;
              } else if (productQty == "" || productQty == 0) {
                $('#alert-message').text("Please enter the Quantity");
                $('#alert-container').show();
                let isErr = $('#alert-message').text().length;
                  if (isErr > 0) {
                  $('html, body').animate({
                      scrollTop: $('#alert-message').offset().top - 300
                  }, 500);
                }
                setTimeout(()=> {
                  $('#alert-container').hide();
                }, 3000);
                allFilled = false; 
                allValid = false;
                return false;
              } else {
                $('#alert-container').hide();
                $('#product-table-error').hide();
                allFilled = true; 
                allValid = true;
                return true;
              }
            });
            if(allFilled && allValid) {
              $('#product-table-error').hide();
              checkQty();
            }
          }

        }
      });
      
      //Check Available Qty Function
      async function checkQty() {
        $('#alert-container').hide();
        $('#product-table-error').hide();
        let allValidated = true; 

        const quantityChecks = $('.qty').map(async function() {
          const qtyVal = parseInt($(this).val());
          const productID = $(this).data('id');
          const prodName = $(this).data('prodname');

          if (!productID) {
            console.log("Failed: No product ID");
            allValidated = false;
            return;
          }

          try {
            const response = await $.ajax({
              url: '{{ route("invoice.fetchProducts", ":id") }}'.replace(':id', productID),
              type: 'POST',
              data: {
                product_id: productID,
                _token: '{{ csrf_token() }}'
              }
            });

            const availableQty = parseInt(response.productIDsandQuantitites[productID]);
            //console.log("Available Quantity Checking", availableQty);

            if (qtyVal && availableQty === 0) {
              $('#alert-message').text(`${prodName} is Out of Stock`);
              $('#alert-container').show();
              let isErr = $('#alert-message').text().length;
              if (isErr > 0) {
                $('html, body').animate({
                    scrollTop: $('#alert-message').offset().top - 300
                }, 500);
              }
              allValidated = false;
            } else if (qtyVal > availableQty) {
              $('#alert-message').text(`${prodName} Quantity exceeds available stock`);
              $('#alert-container').show();
              let isErr = $('#alert-message').text().length;
                if (isErr > 0) {
                $('html, body').animate({
                  scrollTop: $('#alert-message').offset().top - 300
                }, 500);
              }
              allValidated = false;
            } else if (qtyVal < 0) {
              $('#alert-message').text(`Please enter a valid non-negative quantity for ${prodName}`);
              $('#alert-container').show();
              let isErr = $('#alert-message').text().length;
                if (isErr > 0) {
                $('html, body').animate({
                  scrollTop: $('#alert-message').offset().top - 300
                }, 500);
              }
              allValidated = false;
            } else {
              $('#alert-container').hide();
            }
          } catch (error) {
            console.log("Failed", error);
            $('#error-message').html('An error occurred. Please try again.');
            $('#alert-container').show();
            allValidated = false;
          }
        }).get(); 

        await Promise.all(quantityChecks); 

        if (allValidated) {
          $('#amountForm').modal('show');
          $('#product-form-data').attr("disabled", false);
        } else {
          $('#alert-container').show();
          $('#product-form-data').prop("disabled", true);
        }
      }

      //Removing Input Highlighting Border Color
      $('#product-section').on('focus', '.return-qty, .return-amount', function () {
        $(this).css('border-color','#ced4da');
      });

      //Back to Index Page Button
      $('#invoice-close-btn').on('click', function() {
        window.location.href = "{{ route('invoice.index') }}";
      });

      //Fetch Quantity
      $(document).on('input','.qty' ,function (){
        let qtyVal = parseInt($(this).val());
        let productID = $(this).data('id');
        //console.log(productID);

        if (!productID) {
          console.log("Failed: No product ID");
          return;
        }

        /* if (!$.isNumeric(qtyVal) || qtyVal < 0) {
          $('#product-form-data').attr("disabled", true);
          $('#alert-message').text("Please enter a valid non-negative quantity.").show();
          return;
        } */

        if(productID) {
          $('#product-table-error').hide();
          $.ajax({
            url: '{{ route("invoice.fetchProducts",":id") }}'.replace(':id', productID),
            type: 'POST',
            data: {
              product_id: productID,
              _token: '{{ csrf_token() }}' 
            },
            success: function(response) {
              try {
                //console.log("Working",response);
                var qtyData = response.productIDsandQuantitites;
                var availableQty = parseInt(qtyData[productID]);
                //console.log("Available Quantity Check",availableQty);
                if (qtyVal && availableQty == 0) {
                  $('#product-form-data').attr("disabled", true);
                  $('#alert-message').text('Out of Stock');
                  $('#alert-container').show();
                  return false;
                } else if(qtyVal > availableQty){
                 // console.log("QtyValue", qtyVal,"Available Qty",availableQty)
                  $('#product-form-data').attr("disabled", true);
                  $('#alert-message').text("Quantity exceeds available stock");
                  $('#alert-container').show();
                  return false;
                } else if(qtyVal < 0) {
                  $('#product-form-data').attr("disabled", true);
                  $('#alert-message').text("Please enter a valid non-negative quantity.");
                  $('#alert-container').show();
                  return false;
                } else {
                  $('#alert-container').hide();
                  $('#product-form-data').attr("disabled", false);
                  return true;
                }
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

      //Delete Button Confirmation
      function deleteProductRow(callback) {
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: "btn btn-success mx-2",
            cancelButton: "btn btn-danger"
          },
          buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#dc3545',
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "No, cancel!",
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            event.preventDefault();
            callback(true);
            swalWithBootstrapButtons.fire({
              title: "Deleted!",
              text: "Product has been deleted.",
              icon: "success"
            });
          } else if (result.dismiss === swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
              title: 'Cancelled',
              text: 'Your data is safe :)',
              icon: 'error',
              showCancelButton: false,
              confirmButtonColor: '#28a745',
              confirmButtonText: 'Ok',
              buttonsStyling: true,
            });
            callback(false);
          }
        });
      }

      /* $('#received_amt').on('input', function () {
        var receivedAmtVal = $(this).val();
        var prodTotal = $('.total').val();

        if(receivedAmtVal > prodTotal) {
          $('#received-amt-error').html("Please enter Amount below than Total Amount");
          $('#received-amt-error').show();
          setTimeout(()=> {
            $('#received-amt-error').hide();
          }, 3000);
          $('#submit-data').attr("disabled", true);
        } else {
          $('#submit-data').attr("disabled", false);
        }
      }); */

    });
  </script>
@endpush
