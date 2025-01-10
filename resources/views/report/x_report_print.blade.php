@php
    $paperWidth = "320px";
@endphp
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row" id="xReport">
    <style>
      .currency {
        margin-right: 5px;
        float: right;
      }
    </style>
    <div class="col-lg-12" style="margin: 10px 10px 10px 20px; width: {{ $paperWidth }}; text-align:center;">
      <div class="ibox float-e-margins">
        <div class="ibox-content">
          <div class="hr-line-dashed"></div>
          <div class="pos-report">
            <h3 style="text-align: center;"><span style="text-align: center;">MILMA FOODS UK LIMITED</span></h3>
            <h4><span style="text-align: center;">X Report</span></h4>
            <h5 style="font-size: 14px;">Taken: {{ \Carbon\Carbon::now()->format('d-m-Y h:i a') }} </h5>
            <hr style="margin: 10px 20px; width: {{ $paperWidth }};"/>
            @if ($salesData['selectedStartDate']->format('d-m-Y') == $salesData['selectedEndDate']->format('d-m-Y')) 
            <div class="d-flex align-items-space-between justify-content-space-between">
              <b style="font-size: 14px;">Date: {{ \Carbon\Carbon::parse($salesData['selectedStartDate'])->format('d-m-Y') }}</b>
            </div>
            @else
            <div class="d-flex align-items-space-between justify-content-space-between">
              <b style="font-size: 14px;">From: {{ \Carbon\Carbon::parse($salesData['selectedStartDate'])->format('d-m-Y') }}</b>
              <b style="font-size: 14px;">To: {{ \Carbon\Carbon::parse($salesData['selectedEndDate'])->format('d-m-Y') }}</b>
            </div>
            @endif
            <div style="margin: 10px 20px; width: {{ $paperWidth }}; text-align: left;">
              <hr/>
            </div>
            <div class="hr-line-dashed"></div>
            <table class="table" style="margin: 10px 10px 10px 20px; width: {{ $paperWidth }}; text-align: left;">
              <tr>
                <th>No. of Cash Sales: </th>
                <td class="currency">{{ $salesData['cashSalesCount'] }}</td>
              </tr>
              <tr>
                <th>No. of Bank Transfer Sales:</th>
                <td class="currency">{{ $salesData['bankSalesCount'] }}</td>
              </tr>
              <tr>
                <th>No. of Credit Sales:</th>
                <td class="currency">{{ $salesData['cardSalesCount'] ? $salesData['cardSalesCount']  : "0" }}</td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>

              <tr>
                <th>Total Sales: </th>
                <td class="currency">{{ $salesData['salesCount'] }}</td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>

              <tr>
                <th>Cash Sales:</th>
                <td class="currency">{{ $currency }} {{ $salesData['cashSalesAmount'] }}</td>
              </tr>
              <tr>
                <th>Bank Transfer Sales: </th>
                <td class="currency">{{ $currency }} {{ $salesData['bankSalesAmount'] }}</td>
              </tr>
              <tr>
                <th>Credit Sales:</th>
                <td class="currency">{{ $currency }} {{ $salesData['cardSalesAmount'] }}</td>
              </tr>

              <tr>
                <td colspan="2"><hr/></td>
              </tr>
             
              <tr>
                <th>Total Sale Amt:</th>
                <td class="currency">{{ $currency }} {{ $salesData['totalAmount'] + $salesData['cardSalesAmount'] }}</td>
              </tr>
              <tr>
                <th>Amt of Credit Sales:</th>
                <td class="currency">(-) {{ $currency }} {{ $salesData['cardSalesAmount'] }}</td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th>Total Amt: </th>
                <td class="currency">
                {{ $currency }} {{ $salesData['totalAmount'] }}
                </td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th>Total Return Orders: </th>
                <td class="currency">{{ $salesData['returnCount'] }}</td>
              </tr>
              <tr>
                <th>Total Amt of Returns: </th>
                <td class="currency">(-) {{ $currency }} {{ $salesData['returnAmount'] }}</td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th>Total Net Amt: </th>
                <td class="currency">
                {{ $currency }} {{ $salesData['totalAmount'] }}
                </td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th>Cash in Hand:</th>
                <td class="currency">{{ $currency }} {{ $salesData['cashSalesAmount'] }}</td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="hidden-print" style="margin-left:10px; text-align: center; width: {{ $paperWidth }};">
  <button class="btn btn-primary printbutton" id="print" >Print</button>
  <button><a class="btn btn-primary nextbutton " style="text-decoration: none;color: #000;" href="{{route('x_report')}}"><i class="fa fa-plus"></i> Close</a></button>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    
    $('.printbutton').on('click', function () {
      console.log("coming1")
      printDiv()
    });

    function printDiv() {
      console.log("coming2")
        var content = $('#xReport').html();
        console.log(content)
        var printWindow = window.open('', '', 'height=800,width=600');
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<style> .currency { margin-right: 5px; float: right; }</style>'); 
        printWindow.document.write('</head><body><center>');
        printWindow.document.write(content);
        printWindow.document.write('</center></body></html>');
        printWindow.document.close();
        printWindow.print();
    }

  });
</script>
