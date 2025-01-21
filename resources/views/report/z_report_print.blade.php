@php
    $paperWidth = "400px";
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
          <div class="hr-line-dashed" ></div>
          <div class="pos-report">
            <h3 style="text-align: center;"><span style="text-align: center;">MILMA FOODS UK LIMITED</span></h3>
           <!--  <h4><span style="text-align: center;">Z Report</span></h4> -->
            <!-- <h5 style="font-size: 14px;">Taken: {{ \Carbon\Carbon::now()->format('d-m-Y h:i a') }} </h5> -->
            <hr style="margin: 10px 20px; width: {{ $paperWidth }};"/>
            @if ($fromDate->format('d-m-Y') == $toDate->format('d-m-Y')) 
            <div class="d-flex align-items-space-between justify-content-space-between">
              <b style="font-size: 14px;">Date: {{ \Carbon\Carbon::parse($fromDate)->format('d-m-Y') }}</b>
            </div>
            @else
            <div class="d-flex align-items-space-between justify-content-space-between">
              <b style="font-size: 14px;">From: {{ \Carbon\Carbon::parse($fromDate)->format('d-m-Y') }}</b>
              <b style="font-size: 14px;">To: {{ \Carbon\Carbon::parse($toDate)->format('d-m-Y') }}</b>
            </div>
            @endif
            <div style="margin: 10px 20px; width: {{ $paperWidth }}; text-align: left;">
              <hr/>
            </div>
            <div class="hr-line-dashed"></div>
            <table id="invoice-table" class="table" style="margin: 10px 10px 10px 20px; width: {{ $paperWidth }}; text-align: left;" data-value="{{ json_encode($invoiceIDList) }}">
            <tr>
              <th>SALE RETURN</th>
              <th>ITEM</th>
              <th style="text-align: right;">GROSS</th>
            </tr>
            @php
                $totalReturnsAmount = $salesReturns->sum('amount');
            @endphp
            @foreach($salesReturns as $salesReturn)
            <tr>
              <td></td>
              <td>{{$salesReturn->product->name}}</td>
              <td style="text-align: right;"><span>{{$currency}} </span>{{number_format($salesReturn->amount,  $decimalLength )}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="3"><hr /></td>
            </tr>
            <tr>
              <th>TOTAL</th>
              <td></td>
              <td style="text-align: right;"><span>{{$currency}} </span>{{number_format($totalReturnsAmount,  $decimalLength )}}</td>
            </tr>
            <tr>
              <td colspan="3"><hr /></td>
            </tr>
            
            <tr>
              <th>STOCK</th>
              <td></td>
              <td></td>
            </tr>
            <tr></tr>
            <tr>
              <th>Product</th>
              <th>Start</th>
              <th style="text-align: right;">End</th>
            </tr>
            <tr>
              <td colspan="3"><hr /></td>
            </tr>
            @foreach ($loadedProducts as $loadedProduct)
              <tr>
                <td>{{ $loadedProduct->product->name }}</td>
                <td>{{ $loadedProduct->start_quantity }}</td>
                <td style="text-align: right;">{{ $loadedProduct->quantity }}</td>
              </tr>
            @endforeach
            <tr>
              <td colspan="3"><hr/></td>
            </tr>
            <tr>
              <th>RECEIPTS</th>
              <td></td>
              <td></td>
            </tr>
            <tr></tr>
            <tr>
                <th>Company</th>
                <th>Method</th>
                <th style="text-align: right;">Received Amt</th>
              </tr>
              <tr>
                <td colspan="3"><hr/></td>
              </tr>
              @foreach ($filteredInvoices as $companyName => $invoice) 
                
                @if ($invoice->payment_type == "Cash")
                <tr>
                  <td>{{$invoice->customer->company_name}}</td>
                  <td>{{$invoice->payment_type}}</td>
                  <td style="text-align: right;"><span>{{$currency}} </span>{{number_format($invoice->received_amt,  $decimalLength )}}</td>
                </tr>
                @endif
              @endforeach
              <tr>
                <td colspan="3"><hr/></td>
              </tr>

              <tr>
                <td>Cash</td>
                <td>Total</td>
                <td style="text-align: right;"><span>{{$currency}} </span>{{$totalCashAmount}}</td>
              </tr>
              
              <tr>
                <td colspan="3"><hr/><hr/></td>
              </tr>
              @foreach ($filteredInvoices as $companyName => $invoice) 
                @php
                    $totalAmount = $filteredInvoices->sum('total_amount');
                @endphp
                @if ($invoice->payment_type == "Bank Transfer")
                <tr>
                  <td>{{$invoice->customer->company_name}}</td>
                  <td>Transfer</td>
                  <td style="text-align: right;"><span>{{$currency}} </span>{{number_format($invoice->received_amt,  $decimalLength )}}</td>
                </tr>
                @endif
              @endforeach
              <tr>
                <td colspan="3"><hr/></td>
              </tr>

              <tr>
                <td>Transfer</td>
                <td>Total</td>
                <td style="text-align: right;"><span>{{$currency}} </span>{{$totalTransferAmount}}</td>
              </tr>
              
              <tr>
                <td colspan="3"><hr/><hr/></td>
              </tr>
              <tr>
                <td colspan="3" style="text-align: center;">**** END OF SHIFT REPORT ****</td>
              </tr>
              <tr>
                <td colspan="3"><hr/><hr/></td>
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
  <button><a class="btn btn-primary nextbutton " style="text-decoration: none;color: #000;" href="{{route('z_report')}}"><i class="fa fa-plus"></i> Close</a></button>
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

        var invoiceIDList = $('#invoice-table').data('value');

        $.ajax({
          url: '{{ route("zReportInvoiceUpdate") }}',
          type: 'POST',
          data: {
            data: invoiceIDList,
            _token: '{{ csrf_token() }}' 
          },
          success: function(response) {
            try {
              console.log("Success",response); 
              window.location.href= "{{route('z_report')}}"                        
            } catch(error) {
              console.log("Failed",error)
            }
          },
          error: function(xhr) {
            var errorMessage =  'An error occurred. Please try again.';
            $('#error-message').html(errorMessage).show();
          }
        });
    }

  });
</script>
