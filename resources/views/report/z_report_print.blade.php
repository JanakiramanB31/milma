@php
    $paperWidth = "320px";
@endphp
<html>
  <head>
  <style>
      .currency {
        margin-right: 5px;
        float: right;
      }
      .boldfont {
        font-weight: bolder;
      }
    </style>
  </head>
  <body>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row" id="xReport">
    
    <div style=" width: {{ $paperWidth }}; text-align:center;">
      <div >
        <div >
          <div class="hr-line-dashed" ></div>
          <div  >
            <div class="d-flex justify-content-center align-items-center"style="margin: 10px 20px 10px 0px; width: {{ $paperWidth }};">
              <h3 style="text-align: center;"class="boldfont">MILMA FOODS UK LIMITED</h3>
            </div>
           <!--  <h4><span style="text-align: center;">Z Report</span></h4> -->
            <!-- <h5 style="font-size: 14px;">Taken: {{ \Carbon\Carbon::now()->format('d-m-Y h:i a') }} </h5> -->
            <hr style="margin: 10px 20px 10px 0px; width: {{ $paperWidth }};"/>
            @if ($fromDate->format('d-m-Y') == $toDate->format('d-m-Y')) 
            <div class="d-flex align-items-space-between justify-content-space-between">
              <b style="font-size: 14px;" class="boldfont">Date: {{ \Carbon\Carbon::parse($fromDate)->format('d-m-Y') }}</b>
            </div>
            @else
            <div class="d-flex align-items-space-between justify-content-space-between">
              <b style="font-size: 14px;" class="boldfont">From: {{ \Carbon\Carbon::parse($fromDate)->format('d-m-Y') }}</b>
              <b style="font-size: 14px;" class="boldfont">To: {{ \Carbon\Carbon::parse($toDate)->format('d-m-Y') }}</b>
            </div>
            @endif
            <div style="margin: 10px 20px 10px 0px; width: {{ $paperWidth }}; text-align: left;">
              <hr/>
            </div>
            <div class="hr-line-dashed"></div>
            <table id="invoice-table" class="table" style="margin: 10px 20px 10px 0px; width: {{ $paperWidth }}; text-align: left;" data-value="{{ json_encode($invoiceIDList) }}">
            <tr>
              <th class="boldfont">SALE RETURN</th>
              <th class="boldfont">ITEM</th>
              <th  class="boldfont" style="text-align: right;">GROSS</th>
            </tr>
            @php
                $totalReturnsAmount = $salesReturns->sum('amount');
            @endphp
            @foreach($salesReturns as $salesReturn)
            <tr>
              <td class="boldfont"></td>
              <td class="boldfont">{{$salesReturn->product->name ?? "N/A"}}</td>
              <td class="boldfont" style="text-align: right;"><span>{{$currency}} </span>{{number_format($salesReturn->amount,  $decimalLength )}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="3"><hr /></td>
            </tr>
            <tr>
              <th class="boldfont">TOTAL</th>
              <td></td>
              <td class="boldfont" style="text-align: right;"><span>{{$currency}} </span>{{number_format($totalReturnsAmount,  $decimalLength )}}</td>
            </tr>
            <tr>
              <td colspan="3"><hr /></td>
            </tr>
            
            <tr>
              <th class="boldfont">STOCK</th>
              <td></td>
              <td></td>
            </tr>
            <tr></tr>
            <tr>
              <th class="boldfont">Product</th>
              <th class="boldfont">Start</th>
              <th class="boldfont" style="text-align: right;">End</th>
            </tr>
            <tr>
              <td colspan="3"><hr /></td>
            </tr>
            @foreach ($loadedProducts as $loadedProduct)
              <tr>
                <td class="boldfont">{{ $loadedProduct->product->name ?? "N/A" }}</td>
                <td class="boldfont">{{ $loadedProduct->start_quantity }}</td>
                <td class="boldfont" style="text-align: right;">{{ $loadedProduct->quantity }}</td>
              </tr>
            @endforeach
            <tr>
              <td colspan="3"><hr/></td>
            </tr>
            <tr>
              <th class="boldfont">RECEIPTS</th>
              <td></td>
              <td></td>
            </tr>
            <tr></tr>
            <tr>
                <th class="boldfont">Company</th>
                <th class="boldfont">Method</th>
                <th class="boldfont" style="text-align: right;">Received Amt</th>
              </tr>
              <tr>
                <td colspan="3"><hr/></td>
              </tr>
              @foreach ($filteredInvoices as $companyName => $invoice) 

              @php
                if ($invoice->payment_type == $paymentMethods[1]) {
                  $previousBalance = (float)($invoice->customer->previous_balance ?? 0);
                  $paidAmount = (float)($invoice->paid_amt ?? 0);
                  $creditCash = $previousBalance - $paidAmount;
                } else {
                  $creditCash = (float)($invoice->customer->previous_balance ?? 0);
                }               
              @endphp

               

                @if($creditCash > 0.00 && ($invoice->payment_type == $paymentMethods[0]))
                  <tr>
                    <td class="boldfont">{{$invoice->customer->company_name ?? "N/A"}}</td>
                    <td class="boldfont">C Cash <p style="margin: 0; font-size :13px;font-weight:400;"><span>{{$currency}} </span>{{number_format($invoice->customer->previous_balance, $decimalLength)}}</p></td>
                    <td class="boldfont" style="text-align: right;"><span>{{$currency}} </span>{{number_format($invoice->received_amt,  $decimalLength )}}</td>
                  </tr>

                @else
                  @if ($invoice->payment_type == $paymentMethods[0])
                <tr>
                  <td class="boldfont">{{$invoice->customer->company_name ?? "N/A"}}</td>
                  <td class="boldfont">{{$invoice->payment_type}}</td>
                  <td  class="boldfont" style="text-align: right;"><span>{{$currency}} </span>{{number_format($invoice->received_amt,  $decimalLength )}}</td>
                </tr>
                @endif
                @endif
              @endforeach
              <tr>
                <td colspan="3"><hr/></td>
              </tr>

              <tr>
                <td class="boldfont">Cash</td>
                <td class="boldfont">Total</td>
                <td class="boldfont" style="text-align: right;"><span>{{$currency}} </span>{{number_format($totalCashAmount,  $decimalLength )}}</td>
              </tr>
              
              <tr>
                <td colspan="3"><hr/><hr/></td>
              </tr>
              @foreach ($filteredInvoices as $companyName => $invoice) 
               
                @if ($invoice->payment_type == $paymentMethods[1])
                <tr>
                  <td class="boldfont">{{$invoice->customer->company_name ?? "N/A"}}</td>
                  <td class="boldfont">Transfer</td>
                  <td class="boldfont" style="text-align: right;"><span>{{$currency}} </span>{{number_format($invoice->paid_amt,  $decimalLength )}}</td>
                </tr>
                @endif
              @endforeach
              <tr>
                <td colspan="3"><hr/></td>
              </tr>

              <tr>
                <td class="boldfont">Transfer</td>
                <td class="boldfont">Total</td>
                <td class="boldfont" style="text-align: right;"><span>{{$currency}} </span>{{number_format($totalTransferAmount,  $decimalLength )}}</td>
              </tr>
              
              <tr>
                <td colspan="3"><hr/><hr/></td>
              </tr>
               @foreach ($filteredInvoices as $companyName => $invoice) 
                @if ($invoice->payment_type == $paymentMethods[2])
                <tr>
                  <td class="boldfont">{{$invoice->customer->company_name ?? "N/A"}}</td>
                  <td class="boldfont">Credit</td>
                  <td class="boldfont" style="text-align: right;"><span>{{$currency}} </span>{{number_format($invoice->paid_amt,  $decimalLength )}}</td>
                </tr>
                @endif
              @endforeach
              <tr>
                <td colspan="3"><hr/></td>
              </tr>

              <tr>
                <td class="boldfont">Credit</td>
                <td class="boldfont">Total</td>
                <td class="boldfont" style="text-align: right;"><span>{{$currency}} </span>{{number_format($totalCreditAmount,  $decimalLength )}}</td>
              </tr>
              
              <tr>
                <td colspan="3"><hr/><hr/></td>
              </tr>

              <tr>
              <th class="boldfont">EXPENSES</th>
              <td></td>
              <td></td>
            </tr>
            <tr></tr>
            <tr>
              <th class="boldfont">Type</th>
              <th class="boldfont"></th>
              <th class="boldfont" style="text-align: right;">Amt</th>
            </tr>
            <tr>
                <td colspan="3"><hr/></td>
              </tr>
            @foreach($expenses as $expense)
            @php
              $typeName = '';
              if ($expense->expense_type_id == 0) {
                $typeName = $expense->other_expense_details;
              } else {
                foreach ($expenseTypes as $type) {
                  if ($type['id'] == $expense->expense_type_id) {
                    $typeName = $type['name'];
                    break;
                  }
                }
              }
            @endphp
            
            <tr>
              <td class="boldfont">{{$typeName}}</td>
              <td class="boldfont"></td>
              <td class="boldfont" style="text-align: right;"><span>{{$currency}} </span>{{number_format($expense->expense_amt,  $decimalLength )}}</td>
            </tr>
            @endforeach

            <tr>
                <td colspan="3"><hr/></td>
              </tr>

              @php
                $totalExpense = collect($expenses)->sum('expense_amt');
              @endphp

              <tr>
                <td class="boldfont">Expense</td>
                <td class="boldfont">Total</td>
                <td class="boldfont" style="text-align: right;"><span>{{$currency}} </span>{{number_format($totalExpense,  $decimalLength )}}</td>
              </tr>

            <tr>
              <td colspan="3"><hr/><hr/></td>
            </tr>

            <tr>
                <th>Total Amt of Cash: </th>
                <td></td>
                <td class="boldfont" style="text-align: right;">
                <span>{{$currency}} </span> {{  number_format($totalCashAmount , $decimalLength) }}
                </td>
              </tr>
              <tr>
                <th>Total Amt of Expenses: </th>
                <td class="boldfont"></td>
                <td class="boldfont" style="text-align: right;white-space: nowrap;">
                <span >(-) {{$currency}} </span> {{  number_format($totalExpense , $decimalLength) }}
                </td>
              </tr>

            @php
              $netAmt = $totalCashAmount - $totalExpense
            @endphp

              <tr>
                <th>Cash in Hand: </th>
                <td class="boldfont"></td>
                <td class="boldfont" style="text-align: right;">
                <span>{{$currency}} </span> {{  number_format($netAmt , $decimalLength) }}
                </td>
              </tr>
              <tr>
                <td colspan="3"><hr/></td>

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
</body>
</html>

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
