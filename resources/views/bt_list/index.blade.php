@extends('layouts.master')

@section('titel', 'Home | ')
@section('content')
  @include('partials.header')
  @include('partials.sidebar')
    <main class="app-content">

      <div class="app-title">
        <div>
          <h1><i class="fa fa-file-text"></i> Bank Transfer List</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Bank Transfer List</a></li>
        </ul>
      </div>


      <div class="row mt-2">
        <div class="col-md-12">
          <div class="tile">
            <!-- Alert Error Section -->
          <div id="alert-message" class="alert alert-danger" role="alert" hidden></div>
            
            <div class="tile-body">
            <label for="fetchDate">Date :</label>
              <div class="row">
                <div class="col-md-6  mb-2">
                  <input id="startDate" name="startDate" type="date" class="form-control" value="{{ date('Y-m-d') }}"/>
                </div>
                <div class=" d-flex justify-content-center align-items-center">
                  <p>To</p>
                </div>
                <div class="col-md-5 mb-2">
                  <input id="endDate" name="endDate" type="date" class="form-control" value="{{ date('Y-m-d') }}"/>
                </div>
                <!-- <div>
                  <button id="date-submit" class="btn btn-success">View</button>
                </div> -->
                <div class="form-group col-md-12">
                  <label class="form-label">Payment Type</label>
                  <select id="payment_type" name="payment_type" class="form-control">
                    <option value = ''>Select Payment Type</option>
                    @foreach($paymentMethods as $paymentMethod)
                    <option name="payment_type"  value="{{$paymentMethod}}">{{$paymentMethod}}</option>
                    @endforeach
                  </select>
                  <div id="payment-type-error" class="text-danger"></div>
                </div>
              </div>
             
              <div class="form-group  ">

              <div class="tile-body table-responsive">
                <table class="table table-hover table-bordered" id="sampleTable">
                  <thead>
                    <tr>
                      <th class="text-center">Invoice ID </th>
                      <th class="text-center">Date</th>
                      <th class="text-center">Company Name </th>
                      <th class="text-center">Payment Type </th>
                      <th class="text-center">Total Amt</th>
                      <th class="text-center">Received Amt</th>
                      <th class="text-center">Acc Bal Amt</th>
                      <th class="text-center">Bal Amt</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($filteredInvoices as $companyName => $invoice) 
                    @php
                        $totalAmount = $filteredInvoices->sum('total_amount');
                    @endphp
                    <tr>
                      <td class="text-center">{{1000+$invoice->id}}</td>
                      <td class="text-center">{{$invoice->created_at->format('d-m-Y')}}</td>
                      <td class="text-center">{{$invoice->customer->company_name}}</td>
                      <td class="text-center">{{$invoice->payment_type}}</td>
                      <td class="text-center"><span>{{$currency}} </span>{{ number_format($invoice->total_amount,  $decimalLength )}}</td>
                      <td class="text-center"><span>{{$currency}} </span>{{number_format($invoice->received_amt,  $decimalLength )}}</td>
                      <td class="text-center"><span>{{$currency}} </span>{{number_format($invoice->acc_bal_amt,  $decimalLength )}}</td>
                      <td class="text-center"><span>{{$currency}} </span>{{number_format($invoice->balance_amt,  $decimalLength )}}</td>
                      <td class="d-flex justify-content-center" style="gap: 10px;">
                        <a class="btn btn-info btn-sm" href="{{ route('invoice.show', '') }}/{{$invoice->id}}"><i class="fa fa-eye" ></i></a>
                        <i class="fa  fa-check fa-sm btn btn-success payment-approve" data-id="{{$invoice->id}}" ></i>
                        <i class="fa  fa-times fa-sm btn btn-danger payment-denied" data-id="{{$invoice->id}}" ></i>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td class="text-right"><b>Total</b></td>
                      <td id="tot-amt" class="text-center"><span>{{$currency}} </span>{{$totalAmount}}</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
                
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="paymentApproveForm" tabindex="-1" aria-labelledby="paymentApproveFormLabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content ">
            <!-- paymentApproveForm PopUp Form Header -->
            <div class="modal-header">
              <h5 class="modal-title" id="paymentApproveFormLabel">Add Reference Number</h5>
              <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i></button>
            </div>
            <!--paymentApproveForm PopUp Form Content -->
            <div class="modal-body ">
              <div>
                <label>Enter Reference Number</label>
                <textarea id="reference-number-entry" name="popup-reason" class="reference-number-popup form-control-lg col-12"></textarea>
              </div>
            </div>
            <!-- paymentApproveForm PopUp Form Footer -->
            <div class="modal-footer  d-flex justify-content-center">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
              <button id="reference-number-entry-button" type="button" class="btn btn-primary">Save</button>
            </div>
          </div>
        </div>
      </div>

  </main>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
  $(document).ready(function () {

    $('#startDate,#endDate,#payment_type').on('change', function () {
      let selectedStartDate = $('#startDate').val();
      let selectedEndDate = $('#endDate').val();
      var paymentMethod = $('#payment_type').find('option:selected').val();
      console.log(selectedEndDate)
      let today = new Date().toISOString().split('T')[0];

      if (!selectedStartDate) {
        $('#alert-message').attr("hidden", false);
        $('#alert-message').html("Please Select From Date");
        setTimeout(function() {
          $('#alert-message').show();
          $('#alert-message').attr("hidden", true);
        }, 3000);
      } else if (selectedStartDate > today) {
        // Check if start date is in the future
        $('#alert-message').attr("hidden", false);
        $('#alert-message').html("Start Date cannot be in the future");
        setTimeout(function() {
          $('#alert-message').show();
          $('#alert-message').attr("hidden", true);
        }, 3000);
      } else if (!selectedEndDate) {
        // Check if end date is missing
        $('#alert-message').attr("hidden", false);
        $('#alert-message').html("Please Select End Date");
        setTimeout(function() {
          $('#alert-message').show();
          $('#alert-message').attr("hidden", true);
        }, 3000);
      } else if (selectedEndDate < selectedStartDate) {
        // Check if end date is before the start date
        $('#alert-message').attr("hidden", false);
        $('#alert-message').html("End Date must be greater than or equal to Start Date");
        setTimeout(function() {
          $('#alert-message').show();
          $('#alert-message').attr("hidden", true);
        }, 3000);
      } else {
        // Both start and end dates are valid
        const data = {
          "fromDate": selectedStartDate,
          "toDate": selectedEndDate,
          "paymentMethod": paymentMethod
        }
        fetchInvoices(data);
      }
    });

    function fetchInvoices(data) {
      console.log("coming")
      

      const data1Json = JSON.stringify(data);
      if(data) {
        $.ajax({
          url: '{{ route("fetchBTInvoicesByDate",":data") }}'.replace(':data', data1Json),
          type: 'POST',
          data: {
            data: data1Json,
            _token: '{{ csrf_token() }}' 
          },
          success: function(response) {
            try {
              console.log("Success",response);  
              $('#sampleTable tbody').empty();
              const currency = response.currency + " ";
              const decimalLength = response.decimalLength;
              const invoiceData = response.filteredInvoices;
              const totalAmt = invoiceData.reduce((sum, invoice) => sum + parseFloat(invoice.total_amount), 0);
              $('#tot-amt').text(currency + parseFloat(totalAmt).toFixed(decimalLength))
              if (invoiceData.length > 0) {
                invoiceData.forEach(invoice => {
                  $('#sampleTable tbody').append(`
                      <tr>
                        <td class="text-center">${1000+(invoice.id)}</td>
                        <td class="text-center">${new Date(invoice.created_at).toLocaleDateString('en-GB')}</td>
                        <td class="text-center">${invoice.customer.name}</td>
                        <td class="text-center">${invoice.payment_type}</td>
                        <td class="text-center">${currency +parseFloat(invoice.total_amount).toFixed(decimalLength)}</td>
                        <td class="text-center">${currency +parseFloat(invoice.received_amt).toFixed(decimalLength)}</td>
                        <td class="text-center">${currency +parseFloat(invoice.acc_bal_amt).toFixed(decimalLength)}</td>
                        <td class="text-center">${currency +parseFloat(invoice.balance_amt).toFixed(decimalLength)}</td>
                        <td class="d-flex justify-content-center" style="gap: 10px;">
                          <a class="btn btn-info btn-sm" href="{{ route('invoice.show', '') }}/${parseInt(invoice.id)}"><i class="fa fa-eye" ></i></a>
                        </td>
                      </tr>
                  `);
                });
              } else {
                $('#sampleTable tbody').append('<tr><td colspan="9" class="text-center">No invoices found for this date.</td></tr>');
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
    }

    // $(document).on('click', '#reference-number-entry-button', function () {
    //   var referenceNumber = $('#reference-number-entry').val();
    //   var invoiceID = $('#paymentApproveForm').data('invoice-id');
    //   console.log(referenceNumber, invoiceID)
    //   if (referenceNumber.trim() == '') {
    //     Swal.fire({
    //         icon: 'error',
    //         title: 'Oops...',
    //         text: 'Reference number cannot be empty!',
    //     });
    //     return;
    //   }

    //   $.ajax({
    //     url: '{{ route("bt_list.update",":id") }}'.replace(':id', invoiceID),
    //     type: 'POST',
    //     data: {
    //       invoice_id: invoiceID,
    //       reference_number: referenceNumber,
    //       _token: '{{ csrf_token() }}'
    //     },
    //     success: function (response) {
    //       if (response.success) {
    //         Swal.fire({
    //           icon: 'success',
    //           title: 'Success',
    //           text: response.message,
    //         });
    //         $('#paymentApproveForm').modal('hide'); 
    //       } else {
    //         Swal.fire({
    //           icon: 'error',
    //           title: 'Error',
    //           text: response.message,
    //         });
    //       }
    //     },
    //     error: function (xhr) {
    //       Swal.fire({
    //         icon: 'error',
    //         title: 'Error',
    //         text: 'Something went wrong. Please try again.',
    //       });
    //     }
    //   });
    // });

    // $(document).on('click', '.payment-approve', function () {
    //   var invoiceID = $(this).data('id'); 
    //   $('#paymentApproveForm').data('invoice-id', invoiceID);
    //   $('#paymentApproveForm').modal('show');
    // });

    $(document).on('click', '.payment-denied', function () {
      paymentAction();
    });


  });
</script>
@endpush