@extends('layouts.master')

@section('titel', 'Home | ')
@section('content')
  @include('partials.header')
  @include('partials.sidebar')
    <main class="app-content">

      <div class="app-title">
        <div>
          <h1><i class="fa fa-file-text"></i> X-Reports</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Reports</a></li>
          <li class="breadcrumb-item">X-Report</li>
        </ul>
      </div>


      <div class="row mt-2">
        <div class="col-md-12">
          <div class="tile">
            <!-- Alert Error Section -->
          <div id="alert-message" class="alert alert-danger" role="alert" hidden></div>
            <div>
              <label  for="startDate">Date :</label>
              <div class="row">
                <div class="col-5  mb-2">
                  <input id="startDate" name="startDate" type="date" class="form-control"/>
                </div>
                <div class=" d-flex justify-content-center align-items-center">
                  <p>To</p>
                </div>
                <div class="col-5 mb-2">
                  <input id="endDate" name="endDate" type="date" class="form-control"/>
                </div>
                <div>
                  <button id="date-submit" class="btn btn-success">View</button>
                </div>
              </div>
            </div>

            <div class="tile-body">
              <table class="table table-hover table-bordered" id="sampleTable">
                <thead>
                  <tr>
                    <td><b>Title</b></td>
                    <td><b>Qty</b></td>
                    <td><b>Title</b></td>
                    <td><b>Amount</b></td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><b>No. of Cash Sales</b></td>
                    <td id="cashSalesCount">{{$cashPayments['transaction_count']}}</td>
                    <td><b>Amount of Cash Sales</b></td>
                    <td id="cashSalesAmount"><p class="float-right mb-0">£ {{ number_format($cashPayments['total_received_amt'], 2) }}</p></td>
                  </tr>
                  <tr>
                    <td><b>No. of Bank Transfer Sales</b></td>
                    <td id="bankSalesCount">{{$bankPayments['transaction_count']}}</td>
                    <td><b>Amount of Bank Transfer Sales</b></td>
                    <td id="bankSalesAmount"><p class="float-right mb-0">£ {{ number_format($bankPayments['total_received_amt'], 2) }}</p></td>
                  </tr>
                  <tr>
                    <td><b>No. of Credit Sales</b></td>
                    <td id="cardSalesCount">{{$creditTransactionCount}}</td>
                    <td><b>Amount of Credit Sales</b></td>
                    <td id="cardSalesAmount"><p class="float-right mb-0">£ {{ number_format($totalCreditAmount, 2) }}</p></td>
                  </tr>
                  <tr>
                    <td><b>Total No. of Sales</b></td>
                    <td id="salesCount">{{$saleType['qty_count']}}</td>
                    <td><b>Total Amount of Sales</b></td>
                    <td id="salesAmount"><p class="float-right mb-0">£ {{ number_format($saleType['total_amt'], 2) }}</p></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                    <td><b>Amount of Credit Sales</b></td>
                    <td id="cardSalesAmount"><p class="float-right mb-0">(-) £ {{ number_format($totalCreditAmount, 2) }}</p></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td ></td>
                    <td><b>Total Amount</b></td>
                    <td id="totalAmount"><p class="float-right mb-0">£ {{ number_format(($cashPayments['total_received_amt'] + $bankPayments['total_received_amt']), 2) }}</p></td>
                  </tr>
                  <tr>
                    <td><b>No. of Return Orders</b></td>
                    <td id="returnCount">{{$returnType['qty_count']}}</td>
                    <td><b>Total Amount of Return Orders</b></td>
                    <td id="returnAmount"><p class="float-right mb-0">(-) £ {{ number_format($returnType['total_amt'], 2) }}</p></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td ></td>
                    <td><b>Total Net Amount</b></td>
                    <td id="totalAmount"><p class="float-right mb-0">£ {{ number_format(($cashPayments['total_received_amt'] + $bankPayments['total_received_amt']), 2) }}</p></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div>
        <button class="btn btn-primary" id="print">Print</button>
      </div>

  </main>
@endsection

@push('js')
<script type="text/javascript">
  $(document).ready(function () {
    
    $('#date-submit').on('click', function () {
      let selectedStartDate = $('#startDate').val();
      let selectedEndDate = $('#endDate').val();
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
        fetchInvoiceByDate(selectedStartDate, selectedEndDate);
      }
    });

    function fetchInvoiceByDate(selectedStartDate, selectedendDate) {
      const data = {
        "fromDate": selectedStartDate,
        "toDate": selectedendDate
      }

      if(selectedStartDate && selectedendDate) {
        $.ajax({
          url: '{{ route("x_report.fetchByDate",":data") }}'.replace(':data', data),
          type: 'POST',
          data: {
            date: data,
            _token: '{{ csrf_token() }}' 
          },
          success: function(response) {
            try {
              console.log("Success", response);  
              $('#salesCount').text(response.saleType.qty_count);
              $('#salesAmount').text('£ ' + response.saleType.total_amt.toFixed(2));
              $('#returnCount').text(response.returnType.qty_count);
              $('#returnAmount').text('£ ' + response.returnType.total_amt.toFixed(2));
              $('#cashSalesCount').text(response.cashPayments.transaction_count);
              $('#cashSalesAmount').text('£ ' + response.cashPayments.total_received_amt.toFixed(2));
              $('#bankSalesCount').text(response.bankPayments.transaction_count);
              $('#bankSalesAmount').text('£ ' + response.bankPayments.total_received_amt.toFixed(2));
              $('#cardSalesCount').text(response.cardPayments.transaction_count);
              $('#cardSalesAmount').text('£ ' + response.cardPayments.total_received_amt.toFixed(2));
                  
            } catch(error) {
              console.log("Failed",error);
              
            }
          },
          error: function(xhr) {
            var errorMessage =  'An error occurred. Please try again.';
            $('#alert-message').html(errorMessage).show();
          }
        });
      } else {
        console.log("Failed")
      }
    }

    $('#print').on('click', function () {
      let selectedStartDate = $('#startDate').val();
      let selectedEndDate = $('#endDate').val();
      let cashSalesCount = $('#cashSalesCount').text();
      let cashSalesAmount = $('#cashSalesAmount').text().replace("£", "").replace(" ", "").replace("(","").replace(")","").replace("-","");
      let bankSalesCount = $('#bankSalesCount').text();
      let bankSalesAmount = $('#bankSalesAmount').text().replace("£", "").replace(" ", "").replace("(","").replace(")","").replace("-","");
      let cardSalesCount = $('#cardSalesCount').text();
      let cardSalesAmount = $('#cardSalesAmount').text().replace("£", "").replace(" ", "").replace("(","").replace(")","").replace("-","");
      let salesCount = $('#salesCount').text();
      let returnCount = $('#returnCount').text();
      let returnAmount = $('#returnAmount').text().replace("£", "").replace(" ", "").replace("(","").replace(")","").replace("-","");
      let totalAmount = $('#totalAmount').text().replace("£", "").replace(" ", "").replace("(","").replace(")","").replace("-","");
      const data = {
        "selectedStartDate": selectedStartDate,
        "selectedEndDate": selectedEndDate,
        "cashSalesCount": cashSalesCount,
        "cashSalesAmount": cashSalesAmount,
        "bankSalesCount": bankSalesCount,
        "bankSalesAmount": bankSalesAmount,
        "cardSalesCount": cardSalesCount,
        "cardSalesAmount": cardSalesAmount,
        "salesCount": salesCount,
        "returnCount": returnCount,
        "returnAmount": returnAmount,
        "totalAmount": totalAmount
      }
      console.log(data)
      printData(data);
    });

    function printData(data) {

      if(data) {
        $.ajax({
          url: '{{ route("x_report_print",":data") }}'.replace(':data', data),
          type: 'POST',
          data: {
            data: data,
            _token: '{{ csrf_token() }}' 
          },
          success: function(response) {
            try {
              console.log("Success", response);  
                  
            } catch(error) {
              console.log("Failed",error);
              
            }
          },
          error: function(xhr) {
            var errorMessage =  'An error occurred. Please try again.';
            $('#alert-message').html(errorMessage).show();
          }
        });
      } else {
        console.log("Failed")
      }
    }
  });
</script>
@endpush