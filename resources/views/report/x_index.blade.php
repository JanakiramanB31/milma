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
                  <input id="startDate" name="startDate" type="date" class="form-control" value="{{ date('Y-m-d') }}"/>
                </div>
                <div class=" d-flex justify-content-center align-items-center">
                  <p>To</p>
                </div>
                <div class="col-5 mb-2">
                  <input id="endDate" name="endDate" type="date" class="form-control" value="{{ date('Y-m-d') }}"/>
                </div>
                <div>
                  <button id="date-submit" class="btn btn-success">View</button>
                </div>
                <div class="ml-2">
                  <button class="btn btn-success" id="print">Print</button>
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
                    <td id="cashSalesCount">{{$totCashSales}}</td>
                    <td><b>Amount of Cash Sales</b></td>
                    <td ><p class="float-right mb-0" id="cashSalesAmount"><span>{{ $currency }}</span> {{ number_format($cashTotPayments,  $decimalLength ) }}</p></td>
                  </tr>
                  <tr>
                    <td><b>No. of Bank Transfer Sales</b></td>
                    <td id="bankSalesCount">{{$totBankSales}}</td>
                    <td><b>Amount of Bank Transfer Sales</b></td>
                    <td ><p id="bankSalesAmount" class="float-right mb-0"><span>{{ $currency }}</span> {{ number_format($bankTotPayments,  $decimalLength ) }}</p></td>
                  </tr>
                  <tr>
                    <td><b>No. of Credit Sales</b></td>
                    <td id="cardSalesCount">{{$totcreditSales}}</td>
                    <td><b>Amount of Credit Sales</b></td>
                    <td ><p id="cardSalesAmount" class="float-right mb-0"><span>{{ $currency }}</span> {{ number_format($creditTotPayments,  $decimalLength ) }}</p></td>
                  </tr>
                  <tr>
                    <td><b>Total No. of Sales</b></td>
                    <td id="salesCount">{{$totSales}}</td>
                    <td><b>Total Amount of Sales</b></td>
                    <td ><p id="salesAmount" class="float-right mb-0"><span>{{ $currency }}</span> {{ number_format($totAmtOfSales,  $decimalLength ) }}</p></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                    <td><b>Amount of Credit Sales</b></td>
                    <td class="d-flex justify-content-end"><span class="mr-1" >(-)</span><p class=" mb-0" id="cardSalesAmount2"><span>{{ $currency }}</span> {{ number_format($creditTotPayments,  $decimalLength ) }}</p></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td ></td>
                    <td><b>Total Amount</b></td>
                    <td ><p class="float-right mb-0" id="totalAmount"><span>{{ $currency }}</span> {{ number_format($totAmt,  $decimalLength ) }}</p></td>
                  </tr>
                  <tr>
                    <td><b>No. of Return Orders</b></td>
                    <td id="returnCount">{{$totReturns}}</td>
                    <td><b>Total Amount of Return Orders</b></td>
                    <td class="d-flex justify-content-end"><span class="mr-1" >(-)</span><p class=" mb-0" id="returnAmount"><span>{{ $currency }}</span> {{ number_format($totReturnsAmt,  $decimalLength ) }}</p></td>
                  </tr>

                  <tr>
                    <td></td>
                    <td ></td>
                    <td><b>Total Expenses</b></td>
                    <td class="d-flex justify-content-end"><span class="mr-1" >(-)</span><p class=" mb-0" id="totExpenseAmount"><span>{{ $currency }}</span> {{ number_format($totalExpAmt,  $decimalLength ) }}</p></td>
                  </tr>

                  <tr>
                    <td></td>
                    <td ></td>
                    <td><b>Total Net Amount</b></td>
                    <td ><p class="float-right mb-0" id="totalNetAmount"><span>{{ $currency }}</span> {{ number_format($totNetAmt,  $decimalLength ) }}</p></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
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
      console.log("coming")
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
              const currency = response.currency + " ";
              var decimalLength = response.decimalLength;
              console.log(response)
              $('#salesCount').text(response.totSales);
              $('#salesAmount').text(currency + response.totAmtOfSales.toFixed(decimalLength));
              $('#returnCount').text(response.totReturns);
              $('#returnAmount').text(currency + response.totReturnsAmt.toFixed(decimalLength));
              $('#cashSalesCount').text(response.totCashSales);
              $('#cashSalesAmount').text(currency + response.cashTotPayments.toFixed(decimalLength));
              $('#bankSalesCount').text(response.totBankSales);
              $('#bankSalesAmount').text(currency + response.bankTotPayments.toFixed(decimalLength));
              $('#cardSalesCount').text(response.totcreditSales);
              $('#cardSalesAmount').text(currency + response.creditTotPayments.toFixed(decimalLength));
              $('#cardSalesAmount2').text(currency + response.creditTotPayments.toFixed(decimalLength));
              $('#totalAmount').text(currency + (response.totAmt).toFixed(decimalLength));
              $('#totExpenseAmount').text(currency + (response.totalExpAmt).toFixed(decimalLength));

              $('#totalNetAmount').text(currency + (response.totNetAmt).toFixed(decimalLength));
                  
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
      let totalExpAmount = $('#totExpenseAmount').text().replace("£", "").replace(" ", "").replace("(","").replace(")","").replace("-","");

      let totalNetAmount = $('#totalNetAmount').text().replace("£", "").replace(" ", "").replace("(","").replace(")",""); //.replace("-","");
      console.log("cardSalesCount", cardSalesCount)
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
        "totalAmount": totalAmount,
        "totExpenseAmount": totalExpAmount,
        "totalNetAmount": totalNetAmount
      }
      console.log(data)
      printData(data);
    });

    function printData(data) {
      if(data) {
        $.ajax({
          url: '{{ route("x_report_print") }}',
          type: 'POST',
          data: {
            date: {
              fromDate: data.selectedStartDate,
              toDate: data.selectedEndDate
            },
            _token: $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            var printWindow = window.open('', '_blank');
            printWindow.document.write(response);
            printWindow.document.close();
            //printWindow.print();
          },
          error: function(xhr, status, error) {
            console.error('Error fetching report data:', error);
            alert('Error generating report. Please try again.');
          }
        });
      } else {
        console.log("No data to print");
      }
    }
  });
</script>
@endpush