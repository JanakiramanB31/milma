@extends('layouts.master')

@section('title', 'Receipt | ')
@section('content')
  @include('partials.header')
  @include('partials.sidebar')
  <main class="app-content">
      <!-- <div class="app-title">
          <div>
              <h1><i class="fa fa-edit"></i> Form Samples</h1>
              <p>Sample forms</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
              <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
              <li class="breadcrumb-item">Forms</li>
              <li class="breadcrumb-item"><a href="#">Sample Forms</a></li>
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
            <h5 class="mb-0"> <p class="d-inline" style="font-weight: 400;">Date:</p> <p class="mx-1 mb-0 d-inline"></p>{{now()->format('d-m-Y')}}</h5>
          </div>
         <!--  <div class="d-flex justify-content-end align-items-center mt-2">
            <div class="d-flex h-100 justify-content-center align-items-center">
              <p class="mb-0 ">Bal Amt:</p><p class="mb-0 mx-1 d-inline"></p>
              <b id="bal-amt-symbol" class="h5 mb-0 mr-1"></b><b id="bal-amt" class="h5 mb-0">{{number_format($invoice->prev_acc_bal_amt, $decimalLength)}}</b>
            </div>
          </div> -->

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
          <div id="alert-message" class="alert alert-danger" role="alert" hidden></div>

          <!-- Form Section -->
          <div class="tile-body">
            <form  method="POST" action="{{route('invoice.update',$invoice->id)}}">
              @csrf
              @method('PUT')
              <!-- Gathering Customer Name -->
              <div class="row" >
                <div class="form-group col-md-12 mt-1">
                  <label class="control-label">Company Name</label>
                  <select name="customer_id" class="form-control select2" id="customer_name" data-live-search="true">
                    <option value = '0'>Select Customer</option>
                    @foreach($customers as $customer)
                    <option name="customer_id" value="{{$customer->id}}" {{ $invoice->customer->id == $customer->id ? 'selected' : '' }}>{{$customer->company_name}} </option>
                    @endforeach
                  </select>
                  <div id="customer-name-error" class="text-danger"></div> 
                </div>
              </div>

              <!-- Return Items Adding Button -->
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex h-100 justify-content-center align-items-center">
                  <p class="mb-0 ">Bal Amt:</p><p class="mb-0 mx-1 d-inline"></p>
                  <b id="bal-amt-symbol" class="h5 mb-0 mr-1"></b><b id="bal-amt" class="h5 mb-0"></b>
                </div>  
                <button id="return-button-add" type="button" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#returnForm">
                  <i class="fa fa-plus"></i>
                  <span>Return</span>
                </button>
              </div>

              <!-- Purchased Product List Table -->
              <div class="row">

                <!-- Purchased Product Adding to Invoice Section --> 
                <div class="col-md-5 mb-lg-5">
                  <div class="table-responsive">
                    <table class="d-table table table-striped ">
                      <thead>
                        <tr>
                          <th scope="col" class="col-4">Product</th>
                          <th scope="col" class="col-2">Qty</th>
                          <th scope="col" hidden>Price</th>
                          <th scope="col" class="col-5">Amt</th>
                          <th scope="col" class="col-1">Action</th>
                        </tr>
                      </thead>
                      <tbody id="product-section" style="min-height: auto;max-height: 150px;overflow-y: auto;">
                        @foreach($sales as $sale)
                        <tr>
                          <td class="d-flex justify-content-center align-items-center p-1" style="gap: 10px;">
                            @if($sale->type == "sales")
                              <select name="product_id[]" class="form-control p-1 productname" data-id="{{ $sale->product->id}}" data-toggle="tooltip" data-placement="top" title="{{ $sale->product->name ?? 'Select the Product' }}">
                                <option name="product_id[]" value="0">Select the Product</option>
                                @foreach($products as $product)
                                <option name="product_id[]" value="{{$product->id}}" {{ $sale->product->id == $product->id ? 'selected' : '' }}>{{$product->name}}</option>
                                @endforeach
                              </select>
                            @endif
                            @if($sale->type == "returns")
                              <b class="return-symbol" style="color: red;" >R</b>
                              <select name="product_id[]" class="form-control p-1 return-product-id" >
                                <option name="product_id[]" value ='0'>Select Return Product</option>
                                @foreach($returnProducts as $returnProduct)
                                <option name="product_id[]" value="{{$returnProduct->product_id}}" data-id="{{$returnProduct->product_id}}" {{ optional($sale->product)->id == $returnProduct->product->id ? 'selected' : '' }}>
                                {{ $returnProduct->product->name }}</option>
                                @endforeach                            
                              </select>
                            @endif
                          </td>
                          <td class="p-1" style="gap: 10px;"hidden>
                              <select name="product_return_reason[]" class="form-control p-1 product_return_reason" >
                                <option value =''>Select Return Reason</option>
                                @foreach($returnReasons as $returnReason)
                                <option value="{{$returnReason['name']}}" data-id="{{$returnReason['name']}}"{{ optional($sale->reason) == $returnReason['name'] ? 'selected' : '' }}>
                                {{ $returnReason['name'] }}</option>
                                @endforeach                            
                              </select>
                          </td>
                          <td class="p-1" >
                            <input type="text" name="qty[]" value="{{$sale->qty}}" data-qty = "{{$sale->qty}}" data-id="{{$sale->product->id}}" data-prodname="{{$sale->product->name}}" class="form-control text-center p-1 fs-6 {{$sale->type == 'sales'? 'qty' :'return-qty'}}">
                            <input type="hidden" name="prev_qty[]" value="{{$sale->qty}}" >
                            <input type="hidden" name="type[]" value="{{$sale->type == 'sales'? 'sales' : 'returns'}}" class="form-control" >
                          </td>
                          <td hidden><input type="text" value="{{number_format($sale->price, $decimalLength)}}"  name="price[]" class="form-control p-1 fs-6 {{$sale->type == 'sales'? 'price' :'return-price'}} " ></td>
                          <td class="p-1"><input type="text" value="{{number_format($sale->total_amount, $decimalLength)}}" name="amount[]" class="form-control text-center p-1 fs-6 {{$sale->type == 'sales'? 'amount' :'return-amount'}}" ></td>
                          <td hidden><input type="hidden" name="reason[]" class="form-control p-1 fs-6 {{$sale->type == 'sales'? 'reason' :'return-reason'}}" ></td>
                          <td align="center" class="p-1"><i class="fa fa-trash-o fa-sm btn btn-danger prod-remove"></i></td>

                          <!-- <td><a   class="btn btn-danger remove"> <i class="fa fa-remove"></i></a></td> -->
                        </tr>
                        <!-- <td><input type="text" name="product_id[]" value="' +productID+'" hidden/>
                        <input type="text" value="' +productName+'" data-id="'+productID+'" data-toggle="tooltip" data-placement="top" title="'+ productName+'"  
                        class="form-control p-1 productname" readonly></td> -->

                    @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                          <td><input type="hidden" name="acc_bal_amt" id="balance-amount" class="form-control balance-amount" /></td>
                          <td hidden></td>
                          <td><input type="hidden" name="total" id="total" class="form-control total" /></td>
                          <td><b>Total</b></td>
                          <td><b class="currency">£</b><b class="total" id="purchase-tot"></b></td>
                        </tr>
                      </tfoot>
                    </table>
                    <div id="product-table-error" class="text-danger"></div>
                  </div>
                </div>

                <!-- Total Products List -->
                <div class="col-md-7 mb-3">

                  <!-- Products Search -->
                  <!-- <div class="input-group mb-4" style="position: relative;">
                    <div class="input-group-prepend ">
                      <span class="input-group-text icon-container " style="border-right: none;background:transparent">
                        <i style="color: #6c757d;" class="fa fa-search"></i>
                      </span>
                    </div>
                    <input id="product-search" type="text" style="border-left: none;" class="form-control pl-0" placeholder="Search Products..."/>
                  </div> -->

                  <!-- Products List with Image -->
                  <div class="overflow-y p-1 border border-primary rounded" style="height: 200px;overflow-y: auto;">
                    <div id="product-list" class="d-flex flex-wrap h-100 " style="gap: 10px;">
                      @if(count($products) == 0)
                        <div class="d-flex w-100 h-100 justify-content-center align-items-center">
                          <p>No Products Found</p>
                        </div>
                      @else
                        @foreach($products as $product)
                          <figure class="flex-{grow|shrink}-1">
                            <image class="product-select" data-id="{{$product->id}}" data-name="{{$product->name}}" src="{{ asset('images/product/' . ($product->image ?? 'default_image.png')) }}" width='50px' height='50px'/>
                            <figcaption style="width: 50px;"><p class="d-inline" style=" white-space: normal;word-wrap: break-word;overflow-wrap: break-word;">{{$product->name}}
                            @if(Auth::user()->role != 'admin')  
                              <p class="d-inline">-</p>
                              <b>{{$product->quantity}}</b><p class="d-inline">({{$product->unit->name}})</p></p>
                            @endif
                            </figcaption>
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
                            <th scope="col" class="col-4">Product</th>
                            <th scope="col" class="col-2" hidden>Reason</th>
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
                                  <option value="{{$returnProduct->product_id}}" data-id="{{$returnProduct->product_id}}">{{ $returnProduct->product->name }}</option>
                                  @endforeach
                                @endif
                              </select>
                            </td>
                            <td class="p-1" style="gap: 10px;" hidden>
                              <input id="return-product-reason" name="product_return_reason[]" class="form-control p-1 return-product-reason" />
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
                      <option name="payment_type"  value="{{$paymentMethod}}" {{ old('payment_type', $invoice->payment_type) == $paymentMethod ? 'selected' : '' }}>{{$paymentMethod}}</option>
                      @endforeach
                    </select>
                    <div id="payment-type-error" class="text-danger"></div>
                  </div>
                  <div style="padding:18px;">
                    <label class="form-label">Total Amt</label>
                    <input id="prod_tot_amt" type="text"  name="prod_tot_amt" readonly class="form-control"  min="0"/>
                  </div>
                  <div style="padding:18px;">
                    <label class="form-label">Previously Paid Amt</label>
                    <input id="prev_received_amt" type="text"  name="prev_received_amt" readonly value="{{number_format(($invoice->received_amt - $invoice->returned_amt), $decimalLength)}}" class="form-control"  min="0"/>
                  </div>

                  <div class="modal-body d-flex flex-column justify-content-center">
                    <label id="cus_received_amt_label" class="form-label">Amount</label>
                    <input id="cus_received_amt" type="text"  name="cus_received_amt"  class="form-control" style=" padding:20px;font-size:20px;" min="0"/>
                    <div id="cus_received-amt-error" class="text-danger"></div>
                  </div>
                  <!-- Received Amount PopUp Form Content -->
                  <div class="modal-body d-none flex-column justify-content-center">
                    <label id="received_amt_label" class="form-label">Amount</label>
                    <input id="received_amt" type="text"  name="received_amt"  class="form-control" style=" padding:20px;font-size:20px;" min="0"/>
                    <div id="received-amt-error" class="text-danger"></div>
                  </div>

                  <div style="padding: 1rem;" id="show_credit_amt_on_print" class="modal-body form-check flex-column justify-content-center">
                    <input id="show_credit_amt" style="transform: scale(1.5); margin-right: 0.5rem;" type="checkbox" name="show_credit_amt" />  
                    <label for="show_credit_amt" class="form-check-label">Show Bal. Amount on Print</label>
                    <div id="show_credit_amt_error" class="text-danger"></div>
                  </div>

                  <!-- Received Amount PopUp Form Footer -->
                  <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button id="submit-data" class="btn btn-primary" type="submit">Submit</button>
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

           <!-- Return Reason PopUp Form -->
           <div class="modal fade" id="returnProductReasonForm" tabindex="-1" aria-labelledby="returnProductReasonFormLabel" aria-hidden="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg p-5" role="document">
              <div class="modal-content ">
                <!-- Return Reason PopUp Form Header -->
                <div class="modal-header">
                  <h5 class="modal-title" id="returnProductReasonFormLabel">Reason</h5>
                  <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i></button>
                </div>
                <!-- Return Reason PopUp Form Content -->
                <div class="modal-body ">
                  <div class="d-flex flex-column">
                    <div>
                    @foreach ($returnReasons as $returnReason)
                    
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio{{$returnReason['name']}}" value="{{$returnReason['name']}}">
                      <label class="form-check-label" for="inlineRadio{{$returnReason['name']}}">{{$returnReason['name']}}</label>
                    </div>
                    @endforeach
                    </div>

                  </div>
                </div>
                <!-- Return Reason PopUp Form Footer -->
                <div class="modal-footer  d-flex justify-content-center">
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                  <button id="return-product-reason-entry-button" type="button" class="btn btn-primary">Save</button>
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
      $('#alert-message').attr("hidden", false);
      $('#alert-message').hide();
      $('.select2').select2();
      $('#bal-amt-symbol').text("£");
      $('#bal-amt').text(parseFloat(0).toFixed(2));
      total();
      fetchReturnProducts();
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
      $(document).on('input', '.return-price, .price, .amount, .return-amount, #received_amt,  #cus_received_amt', function() {
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
        //console.log(toolTip.val())
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
        var prodPrices = prodData.productIdsAndPrices;
        var productPrice = '';
        if(prodPrices[productID]) {
          productPrice = prodPrices[productID];
        } else {
          productPrice = prodData.prodIDsAndBasePrices[productID];
          $('#alert-message').text("The selected product rate type price is not available, so the base rate has been applied instead.");
          $('#alert-message').show();
        }
        tr.find('.price').val(parseFloat(prodPrices[id]).toFixed(2));
      });

      //Calculating the Total Amount for Selected Product
      $('#product-section').delegate('.qty,.price', 'keyup', function () {
        var tr = $(this).parent().parent();
        var qty = tr.find('.qty').val();
        var price = tr.find('.price').val();
        var amount = parseFloat(qty * price).toFixed(2);
        tr.find('.amount').val(parseFloat(amount ? amount : 0).toFixed(2));
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
          //console.log("SalesAmount",salesAmount);
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
                //console.log("proddata",prodData);
                var PrevbalAmt = response.prev_acc_bal_amt.prev_acc_bal_amt ?? 0;
                //console.log("Balance Amount", balAmount)
                $('#bal-amt-symbol').text("£")
                $('#bal-amt').text(parseFloat(PrevbalAmt).toFixed(2));
                $('#balance-amount').val(parseFloat(PrevbalAmt).toFixed(2));
                /* $('.return-product-id').empty().append('<option value="">Select Return Product</option>');
                if (response.returnProducts.length > 0) {
                  returnProducts = response.returnProducts;
                  //console.log("returnProducts",returnProducts);
                  $.each(response.returnProducts, function(index, returnProduct) {
                    $('.return-product-id').append('<option value="' + returnProduct.id + '">' + returnProduct.name + '</option>');
                  });
                } else {
                  $('.return-product-id').append('<option value="">No products available</option>');
                } */
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
            row.find('.return-price').val(parseFloat(productDetails.price).toFixed(2));
          } else {
            row.find('.return-price').val('');
          }
          $('#returnProductReasonForm').modal('show');
          $('#returnProductReasonForm').data('productID', selectedProductID);
        }
      });

      $(document).on('input','.return-qty', function () {
        var tr = $(this).closest('tr');
        var returnQtyVal = parseInt(tr.find('.return-qty').val());
        var returnProdPrice = tr.find('.return-price').val();
        var returnAmt = parseFloat(returnQtyVal * returnProdPrice).toFixed(2);
        tr.find('.return-amount').val(parseFloat(returnAmt ? returnAmt : 0).toFixed(2));
        total();
      });

      //Calculating Total Amount for Each Return Products
      $('#return-product-body').delegate('.return-qty,.return-price', 'keyup', function () {
        var tr = $(this).closest('tr');
        var qty = tr.find('.return-qty').val();
        var price = tr.find('.return-price').val();
        var amount = (qty * price);
        tr.find('.return-amount').val(parseFloat(amount ? amount : 0).toFixed(2));
        returnTotal();
        $('#return-entry-button').on("click", function(){
          $('#return-product-name-entry').val();
          $('#return-qty-entry').val(qty);
          $('#return-price-entry').val(parseFloat(price).toFixed(2));
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
        fetchReturnProducts();
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
            <td class="d-flex align-items-center" style="gap: 10px;"><b class="return-symbol" style="color: red;" hidden>R</b>
              <select id="return-product-id" name="product_id[]" class="form-control p-1 return-product-id" data-toggle="tooltip" data-placement="top" aria-label="Select Return Product">
                <option value =''>Select Return Product</option>
                @foreach($returnProducts as $returnProduct)
                <option value="{{$returnProduct->product_id}}" data-id="{{$returnProduct->product_id}}">{{ $returnProduct->product->name }}</option>
                @endforeach
              </select>
            </td>
            <td class="p-1" style="gap: 10px;" hidden>
              <input id="return-product-reason" name="product_return_reason[]" class="form-control p-1 return-product-reason" />
            </td>
            <td>
              <input type="text"  name="qty[]" class="form-control text-center p-1 return-qty" />
              <input type="hidden" name="type[]" value="returns" class="form-control" />
            </td>
            <td hidden>
              <input type="text"  name="price[]" class="form-control return-price" readonly/>
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
        fetchReturnProducts();
        addReturnMobileRow();
      });

      //Removing Return Product Row
      $('#return-product-body').on('click', '.remove', function() {
        $(this).closest('tr').remove();
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
            var reasonValue = $(this).find('.return-product-reason').val();

            if ( selectValue.length == 0) {
              $('#return-table-error').html("Please Select the Product");
              $('#return-table-error').show();
              setTimeout(()=> {
                $('#return-table-error').hide();
              }, 3000);
              allFilled = false; 
              return false;
            } else if (reasonValue.length == 0) {
              $('#return-table-error').html("Please Select the Reason");
              $('#return-table-error').show();
              setTimeout(()=> {
                $('#return-table-error').hide();
              }, 3000);
              allFilled = false; 
              return false;
            } else if (inputValue.length == 0) {
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
              $(this).find('.return-product-reason').parent().attr('hidden', true);
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
        $(this).val(parseFloat(receivedAmount ? receivedAmount : 0).toFixed(2));
      });

      $('#payment_type').on('change', function () {
        let paymentType = $(this).val();
        let prevPaidAmt = $('#prev_received_amt').val();
        let totalAmount = $('#purchase-tot').text();
        let balAmount =  totalAmount - prevPaidAmt;
        if (balAmount < 0) {
          $('#cus_received_amt_label').html("Refund Amount Due")
          
        } else {
          $('#cus_received_amt_label').html("Remaining Amount to Pay")
        }
        $('#prod_tot_amt').val(parseFloat(totalAmount).toFixed(2));
        $('#received_amt').val(parseFloat(balAmount).toFixed(2));
        $('#cus_received_amt').val(parseFloat(Math.abs(balAmount)).toFixed(2));
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
          '<td class="p-1"><input type="text" name="product_id[]" value="' +productID+'" hidden/><input type="text" value="' +productName+'" data-id="'+productID+'" data-toggle="tooltip" data-placement="top" title="'+ productName+'"  class="form-control p-1 productname get-prod-id" readonly></td>\n' +
          '<td class="p-1"><input type="text" name="qty[]" data-id="'+productID+'" data-prodname="' +productName+'" class="form-control text-center p-1 fs-6 qty" ><input type="hidden" name="type[]" value="sales" class="form-control" ></td>\n' +
          '<td hidden><input type="text" value="'+productPrice+'"  name="price[]" class="form-control p-1 fs-6 price" ></td>\n' +
          '<td class="p-1"><input type="text"  name="amount[]" class="form-control text-center p-1 fs-6 amount" ></td>\n' +
          '<td hidden><input type="hidden" name="product_return_reason[]" class="form-control p-1 fs-6 product_return_reason" ></td>\n' +
          '<td hidden><input type="hidden" name="reason[]" class="form-control p-1 fs-6 reason" ></td>\n' +
          '<td align="center" class="p-1"><i class="fa fa-trash-o fa-sm btn btn-danger prod-remove"></i></td>\n'+
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
            console.log("Error Checking",prodData)
          var prodPrices = prodData.productIdsAndPrices;
          var productPrice = '';
          if(prodPrices[productID]) {
            productPrice = prodPrices[productID];
          } else {
            productPrice = prodData.prodIDsAndBasePrices[productID];
            $('#alert-message').text("The selected product rate type price is not available, so the base rate has been applied instead.");
            $('#alert-message').show();
          }
          addProductMobileRow(productID, productName, productPrice);
          } else {
            $('#alert-message').text("Already added the Product");
            $('#alert-message').show();
            let isErr = $('#alert-message').text().length;
            if (isErr > 0) {
              $('html, body').animate({
                  scrollTop: $('#alert-message').offset().top - 300
              }, 500);
            }
            setTimeout(()=> {
              $('#alert-message').hide();
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

      $(document).on('click', '#return-product-reason-entry-button', function () {
        var returnReason = $('input[name="inlineRadioOptions"]:checked').val();
        var productID = $('#returnProductReasonForm').data('productID');
        console.log("returnReason", returnReason);
        console.log("productID", productID)
        var found = false;

        $('#return-product-body').find('tr').each(function () {
          var selectValue = $(this).find('select').val();
          if (selectValue == productID) {
            $(this).find('.return-product-reason').val(returnReason);
            $('#returnProductReasonForm').modal('hide');
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
            $('#prod-price').text(parseFloat(prodPrice).toFixed(2));
            $('#prod-rtn-reason').text(rtnReason);
            $('#return-view-tot-currency').text('£');
            $('#prod-tot-amt').text(parseFloat(totalAmt ? totalAmt : 0).toFixed(2));
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
            $('#alert-message').show();
            let isErr = $('#alert-message').text().length;
            if (isErr > 0) {
              $('html, body').animate({
                  scrollTop: $('#alert-message').offset().top - 300
              }, 500);
            }
            setTimeout(()=> {
              $('#alert-message').hide();
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
                $('#alert-message').show();
                let isErr = $('#alert-message').text().length;
                  if (isErr > 0) {
                  $('html, body').animate({
                      scrollTop: $('#alert-message').offset().top - 300
                  }, 500);
                }
                setTimeout(()=> {
                  $('#alert-message').hide();
                }, 3000);
                allFilled = false; 
                allValid = false;
                return false;
              } else if (productQty == "") {
                $('#alert-message').text("Please enter the Quantity");
                $('#alert-message').show();
                let isErr = $('#alert-message').text().length;
                  if (isErr > 0) {
                  $('html, body').animate({
                      scrollTop: $('#alert-message').offset().top - 300
                  }, 500);
                }
                setTimeout(()=> {
                  $('#alert-message').hide();
                }, 3000);
                allFilled = false; 
                allValid = false;
                return false;
              } else {
                $('#alert-message').hide();
                $('#product-table-error').hide();
                allFilled = true; 
                allValid = true;
                return true;
              }
            });
            if(allFilled && allValid) {
              $('#product-table-error').hide();
              checkQty();
              //checkReturnQty();
            }
          }

        }
      });
      
      //Check Available Qty Function
      async function checkQty() {
        $('#alert-message').hide();
        $('#product-table-error').hide();
        let allValidated = true; 

        const quantityChecks = $('.qty').map(async function() {
          const qtyVal = parseInt($(this).val());
          const productID = $(this).data('id');
          const prodName = $(this).data('prodname');
          const prevQty = parseInt($(this).data('qty'));
          console.log("prevQty",prevQty)

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
            //console.log("Available Quantity", availableQty);

            if (qtyVal && prevQty+availableQty === 0) {
              $('#alert-message').text(`${prodName} is Out of Stock`).show();
              allValidated = false;
            } else if ((prevQty-qtyVal) > availableQty) {
              $('#alert-message').text(`${prodName} Quantity exceeds available stock`).show();
              allValidated = false;
            } else if (qtyVal < 0) {
              $('#alert-message').text(`Please enter a valid non-negative quantity for ${prodName}`).show();
              allValidated = false;
            } else {
              $('#alert-message').hide();
            }
          } catch (error) {
            console.log("Failed", error);
            $('#error-message').html('An error occurred. Please try again.').show();
            allValidated = false;
          }
        }).get(); 

        await Promise.all(quantityChecks); 

        if (allValidated) {
          // checkReturnQty();
          
          $('#amountForm').modal('show');
          let prevPaidAmt = $('#prev_received_amt').val();
          let totalAmount = $('#purchase-tot').text();
          let balAmount =  totalAmount - prevPaidAmt;
          if (balAmount < 0) {
            $('#received_amt_label').html("Refund Amount Due")
            $('#cus_received_amt_label').html("Refund Amount Due")
          } else {
            $('#received_amt_label').html("Remaining Amount to Pay")
            $('#cus_received_amt_label').html("Remaining Amount to Pay")
          }
          $('#prod_tot_amt').val(parseFloat(totalAmount).toFixed(2));
          $('#received_amt').val(parseFloat( balAmount).toFixed(2));
          $('#cus_received_amt').val(parseFloat(Math.abs(balAmount)).toFixed(2));
          $('#product-form-data').attr("disabled", false);
        } else {
          $('#alert-message').show();
          $('#product-form-data').prop("disabled", true);
        }
      }

      // //Check Return Qty Function
      // async function checkReturnQty() {
      //   console.log('coming return Qty')
      //   $('#alert-message').hide();
      //   $('#product-table-error').hide();
      //   let allValidated = true; 

      //   const returnQtyChecks = $('#product-secion').find('.return-qty').map(function(){
      //     const returnQtyVal = $(this).val();
      //     const prodName = $(this).data('prodname');
      //     console.log("return Qty:", returnQtyVal)
      //     console.log("Return Product Name",prodName)

      //     if(isNaN(returnQtyVal) || returnQtyVal <= 0) {
      //       console.log("return Qty:", returnQtyVal)
      //       $('#alert-message').text(`Enter Quantity or Remove ${prodName} Product`).show();
      //       allValidated = false;
      //     } else {
      //       $('#alert-message').hide();
      //     }
      //   }).get();
      //   await Promise.all(returnQtyChecks); 
      //   if (allValidated) {
      //     $('#amountForm').modal('show');
      //     $('#product-form-data').attr("disabled", false);
      //   } else {
      //     $('#alert-message').show();
      //     $('#product-form-data').prop("disabled", true);
      //   }
      // }

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
        const prevQty = parseInt($(this).data('qty'));
          console.log("prevQty",prevQty)
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
                //console.log("Available Quantity",availableQty);
                if (qtyVal && prevQty+availableQty == 0) {
                  $('#product-form-data').attr("disabled", true);
                  $('#alert-message').text('Out of Stock').show();
                  return false;
                } else if ((qtyVal-prevQty) > availableQty) {
                  //console.log("QtyValue", qtyVal,"Available Qty",availableQty)
                  $('#product-form-data').attr("disabled", true);
                  $('#alert-message').text("Quantity exceeds available stock");
                  $('#alert-message').show();
                  return false;
                } else if(qtyVal < 0) {
                  $('#product-form-data').attr("disabled", true);
                  $('#alert-message').text("Please enter a valid non-negative quantity.").show();
                  return false;
                } else {
                  $('#alert-message').hide();
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

    });
  </script>
@endpush



