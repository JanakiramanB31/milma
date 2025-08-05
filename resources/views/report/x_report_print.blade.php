@php
    $paperWidth = "300px";
    // Use the dates that were processed in the controller
    $startDate = isset($fromDate) ? $fromDate : now();
    $endDate = isset($toDate) ? $toDate : now();
@endphp

<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row" id="xReport">
    <style>
      .currency {
        margin-right: 5px;
        float: right;
      }
      .boldfont {
        font-weight: bolder;
      }
      th, td {
        font-weight: bolder;
      }
    </style>
    <div class="col-lg-12" style="width: {{ $paperWidth }}; text-align:center;">
      <div class="ibox float-e-margins">
        <div class="ibox-content">
          <div class="hr-line-dashed"></div>
          <div class="d-flex justify-content-center align-items-center" style="width: 100%;">
            <h3 style="text-align: center;"><span style="text-align: center;"  class="boldfont">MILMA FOODS UK LIMITED</span></h3>
            <h4><span style="text-align: center;"  class="boldfont">X Report</span></h4>
            <!-- <h5 style="font-size: 14px;"  class="boldfont">Taken: {{ \Carbon\Carbon::now()->format('d-m-Y h:i a') }} </h5> -->
            <hr />
            @if ($startDate->format('d-m-Y') == $endDate->format('d-m-Y')) 
            <div class="d-flex align-items-space-between justify-content-space-between"  class="boldfont">
              <b style="font-size: 14px;"  class="boldfont">Date: {{ $startDate->format('d-m-Y') }}</b>
            </div>
            @else
            <div class="d-flex align-items-space-between justify-content-space-between">
              <b style="font-size: 14px;"  class="boldfont">From: {{ $startDate->format('d-m-Y') }}</b>
              <b style="font-size: 14px;"  class="boldfont">To: {{ $endDate->format('d-m-Y') }}</b>
            </div>
            @endif
            <div style="text-align: left;">
              <hr/>
            </div>
            <div class="hr-line-dashed"></div>
            <table class="table" style="width: {{$paperWidth}}; text-align: left;">
              <tr>
                <th>No. of Cash Sales: </th>
                <td class="currency">{{ $totCashSales }}</td>
              </tr>
              <tr>
                <th>No. of Bank Transfer Sales:</th>
                <td class="currency">{{ $totBankSales }}</td>
              </tr>
              <tr>
                <th>No. of Credit Sales:</th>
                <td class="currency">{{ $totcreditSales ? $totcreditSales : "0" }}</td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>

              <tr>
                <th>Total Sales: </th>
                <td class="currency">{{ $totSales }}</td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>

              <tr>
                <th>Cash Sales:</th>
                <td class="currency">{{ $currency }} {{ number_format($cashTotPayments, $decimalLength) }}</td>
              </tr>
              <tr>
                <th>Bank Transfer Sales: </th>
                <td class="currency">{{ $currency }} {{ number_format($bankTotPayments, $decimalLength) }}</td>
              </tr>
              <tr>
                <th>Credit Sales:</th>
                <td class="currency">{{ $currency }} {{ number_format($creditTotPayments, $decimalLength) }}</td>
              </tr>

              <tr>
                <td colspan="2"><hr/></td>
              </tr>
             
              <tr>
                <th>Total Sale Amt:</th>
                <td class="currency">{{ $currency }} {{  number_format($totAmtOfSales, $decimalLength) }}</td>
              </tr>
              <tr>
                <th>Amt of Credit Sales:</th>
                <td class="currency">(-) {{ $currency }} {{  number_format($creditTotPayments, $decimalLength) }}</td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th>Total Amt: </th>
                <td class="currency">
                {{ $currency }} {{  number_format($totAmt, $decimalLength) }}
                </td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th>Total Return Orders: </th>
                <td class="currency">{{ $totReturns }}</td>
              </tr>
              <tr>
                <th>Total Amt of Returns: </th>
                <td class="currency">(-) {{ $currency }} {{  number_format($totReturnsAmt, $decimalLength) }}</td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>

              <tr>
                <th>Total Amt of Expenses: </th>
                <td class="currency">(-) {{ $currency }} {{  number_format($totalExpAmt, $decimalLength) }}</td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th>Total Net Amt: </th>
                <td class="currency">
                {{ $currency }} {{  number_format($totNetAmt, $decimalLength) }}
                </td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th>Cash in Hand:</th>
                <td class="currency">{{ $currency }} {{  number_format($cashTotPayments, $decimalLength) }}</td>
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
