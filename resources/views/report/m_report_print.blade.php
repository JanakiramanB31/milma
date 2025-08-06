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
      .table-container {
        width: 100%;
        display: flex;
        justify-content: center;
        margin: 10px 0;
      }
      .responsive-table {
        width: 100%;
        max-width: 900px;
        border-collapse: collapse;
        margin: 0 auto;
        text-align: left;
      }
      .responsive-table th,
      .responsive-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #ddd;
      }
      .table-border-top {
        border-top: 1px solid black;
      }
      .table-border-bottom {
        border-bottom: 1px solid black;
      }
      @media screen and (max-width: 768px) {
        .responsive-table {
          font-size: 14px;
        }
        .responsive-table th,
        .responsive-table td {
          padding: 6px 8px;
        }
      }
      @media screen and (max-width: 480px) {
        .responsive-table {
          font-size: 12px;
        }
        .responsive-table th,
        .responsive-table td {
          padding: 4px 6px;
        }
      }
    </style>
  </head>
  <body>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row" id="mReport">
        
        <div style=" text-align:center;">
          <div >
            <div >
              <div class="hr-line-dashed" ></div>
              <div  class="d-flex justify-content-center align-items-center">
                <div class="d-flex justify-content-center align-items-center"style="margin: 10px 20px 10px 0px;">
                  <h3 style="text-align: center;"class="boldfont">MILMA FOODS UK LIMITED</h3>
                </div>
               <!--  <h4><span style="text-align: center;">Z Report</span></h4> -->
                <!-- <h5 style="font-size: 14px;">Taken: {{ \Carbon\Carbon::now()->format('d-m-Y h:i a') }} </h5> -->
               <!--  <hr style="margin: 10px 20px 10px 0px;"/>
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
                <div style="margin: 10px 20px 10px 0px; text-align: left;">
                  <hr/>
                </div>
                <div class="hr-line-dashed"></div> -->
                <div class="table-container">
                  <table id="invoice-table" class="responsive-table" data-value="{{ json_encode($invoiceIDList) }}">
                    <!-- <tr>
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
                    </tr> -->
                    <tr >
                      <th class="boldfont" colspan="7">
                        Payments Summary for the Period between: {{ \Carbon\Carbon::parse($fromDate)->format('d-m-Y') }} and {{ \Carbon\Carbon::parse($toDate)->format('d-m-Y') }}
                      </th>
                    </tr>
                    <tr></tr>
                    <tr style="border-top: 2px solid black;">
                      <th class="boldfont" style="border-bottom: 2px solid black;white-space:wrap;">Customer</th>
                      <th class="boldfont" style="border-bottom: 2px solid black;">Invoice</th>
                      <th class="boldfont" style="border-bottom: 2px solid black;">Date</th>
                      <th class="boldfont" style="border-bottom: 2px solid black;">Method</th>
                      <th class="boldfont" style="border-bottom: 2px solid black;">Sale</th>
                      <th class="boldfont" style="border-bottom: 2px solid black;">Return</th>
                      <th class="boldfont" style="border-bottom: 2px solid black;">Each</th>
                      <th class="boldfont" style="text-align: right;border-bottom: 2px solid black;">Gross</th>
                    </tr>
                    <!-- <tr>
                      <td colspan="4"><hr/></td>
                    </tr> -->
                    @foreach ($filteredInvoices as $companyName => $invoice)

                      @php
                        $previousBalance = (float)($invoice->customer->previous_balance ?? 0);
                        $paidAmount = (float)($invoice->paid_amt ?? 0);
                        $creditCash = ($invoice->payment_type == $paymentMethods[1])
                                      ? $previousBalance - $paidAmount
                                      : $previousBalance;
                      @endphp

                      @if ($invoice->payment_type == $paymentMethods[0])
                        @foreach ($invoice->sales as $sale)
                          <tr>
                            <td style="border-bottom: none;white-space:wrap;">{{ $invoice->customer->company_name ?? "N/A" }}</td>
                            <td style="border-bottom: none;">{{ $invoice->id ?? "N/A" }}</td>
                            <td style="border-bottom: none">{{ $invoice->created_at->format('d-m-Y') }}</td>

                            <td style="border-bottom: none;">
                                @if ($creditCash > 0.00)
                                    C Cash
                                    <!-- <p style="margin: 0; font-size:13px; font-weight:400;">
                                         {{ number_format($previousBalance, $decimalLength) }}
                                    </p> -->
                                @else
                                    {{ $invoice->payment_type }}
                                @endif
                            </td>
                            @if($sale->type == 'sales')
                              <td style="border-bottom: none;">{{ $sale->qty }}</td>
                              <td style="border-bottom: none;"></td>
                              <td style="border-bottom: none;">  {{ number_format($sale->price, $decimalLength) }}</td>
                              <td style="text-align: right;border-bottom: none;">
                                 {{ number_format($sale->total_amount, $decimalLength) }}
                              </td>
                            @else
                              <td style="border-bottom: none;"></td>
                              <td style="border-bottom: none;">{{ $sale->qty }}</td>
                              <td style="border-bottom: none;">0.00</td>
                              <td style="text-align: right;border-bottom: none;"> 0.00</td>
                            @endif
                            
                          </tr>
                        @endforeach
                      @endif
                    @endforeach

                    <!-- <tr>
                      <td colspan="4"><hr/></td>
                    </tr> -->
                    <tr style="border-top: 2px solid black;">
                      <td class="boldfont" style="border-bottom: 2px solid black;">Cash</td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;">Total</td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="text-align: right;border-bottom: 2px solid black;">{{number_format($totalCashAmount,  $decimalLength )}}</td>
                    </tr>
                    
                    <!-- <tr>
                      <td colspan="4"><hr/><hr/></td>
                    </tr> -->
                    @foreach ($filteredInvoices as $companyName => $invoice) 
                    
                      @if ($invoice->payment_type == $paymentMethods[1])
                      @foreach ($invoice->sales as $sale)
                      <tr>
                        <td style="border-bottom: none;white-space:wrap;">{{$invoice->customer->company_name ?? "N/A"}}</td>
                        <td style="border-bottom: none;">{{$invoice->id ?? "N/A"}}</td>
                        <td style="border-bottom: none;">{{$invoice->created_at->format('d-m-Y')}}</td>
                        <td style="border-bottom: none;">Transfer</td>
                        @if($sale->type == 'sales')
                          <td style="border-bottom: none;">{{ $sale->qty }}</td>
                          <td style="border-bottom: none;"></td>
                          <td style="border-bottom: none;">  {{ number_format($sale->price, $decimalLength) }}</td>
                          <td style="text-align: right;border-bottom: none;">
                             {{ number_format($sale->total_amount, $decimalLength) }}
                          </td>
                        @else
                          <td style="border-bottom: none;"></td>
                          <td style="border-bottom: none;">{{ $sale->qty }}</td>
                          <td style="border-bottom: none;">  0.00</td>
                          <td style="text-align: right;border-bottom: none;">0.00</td>
                        @endif
                      </tr>
                      @endforeach
                      @endif
                    @endforeach
                    <!-- <tr>
                      <td colspan="4"><hr/></td>
                    </tr> -->

                    <tr style="border-top: 2px solid black;">
                      <td class="boldfont" style="border-bottom: 2px solid black;">Transfer</td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;">Total</td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="text-align: right;border-bottom: 2px solid black;">{{number_format($totalTransferAmount,  $decimalLength )}}</td>
                    </tr>
                    
                    <!-- <tr>
                      <td colspan="4"><hr/><hr/></td>
                    </tr> -->
                    @foreach ($filteredInvoices as $companyName => $invoice) 
                      @if ($invoice->payment_type == $paymentMethods[2])
                      @foreach ($invoice->sales as $sale)
                      <tr>
                        <td style="border-bottom: none;white-space:wrap;width: 100px;">{{$invoice->customer->company_name ?? "N/A"}}</td>
                        <td style="border-bottom: none;">{{$invoice->id ?? "N/A"}}</td>
                        <td style="border-bottom: none;">{{$invoice->created_at->format('d-m-Y')}}</td>
                        <td style="border-bottom: none;">Credit</td>
                        @if($sale->type == 'sales')
                          <td style="border-bottom: none;">{{ $sale->qty }}</td>
                          <td class="boldfont" style="border-bottom: none;"></td>
                          <td style="border-bottom: none;">  {{ number_format($sale->price, $decimalLength) }}</td>
                          <td style="border-bottom: none;text-align: right;">
                             {{ number_format($sale->total_amount, $decimalLength) }}
                          </td>
                        @else
                         <td class="boldfont" style="border-bottom: none;"></td>

                          <td style="border-bottom: none;"></td>
                          <td style="border-bottom: none;">{{ $sale->qty }}</td>
                          <td style="border-bottom: none;">  0.00</td>
                          <td style="border-bottom: none;text-align: right;">  0.00</td>
                        @endif
                      </tr>
                      @endforeach
                      @endif
                    @endforeach
                   <!--  <tr>
                     <td colspan="4"><hr/></td>
                    </tr> -->
                    <tr style="border-top: 2px solid black;">
                      <td class="boldfont" style="border-bottom: 2px solid black;">Credit</td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;">Total</td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="text-align: right;border-bottom: 2px solid black;">{{number_format($totalCreditAmount,  $decimalLength )}}</td>
                    </tr>
                    
                    <!-- <tr>
                      <td colspan="4"><hr/><hr/></td>
                    </tr> -->

                    <!-- <tr>
                      <th class="boldfont">EXPENSES</th>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr></tr>
                    <tr style="border-top: 2px solid black;"> 
                      <th class="boldfont" style="border-bottom: 2px solid black;">Date</th>
                      <th class="boldfont" style="border-bottom: 2px solid black;"></th>
                      <th class="boldfont" style="border-bottom: 2px solid black;"></th>
                      <th class="boldfont" style="border-bottom: 2px solid black;">Type</th>
                      <th class="boldfont" style="border-bottom: 2px solid black;"></th>
                      <th class="boldfont" style="border-bottom: 2px solid black;"></th>
                      <th class="boldfont" style="text-align: right;border-bottom: 2px solid black;">Amt</th>
                    </tr> -->
                    <!-- <tr>
                      <td colspan="4"><hr/></td>
                    </tr> -->
                   <!--  @foreach($expenses as $expense)
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
                      <td class="boldfont">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d-m-Y') }}</td>
                      <td class="boldfont"></td>
                      <td class="boldfont"></td>
                      <td class="boldfont">{{$typeName}}</td>
                      <td class="boldfont"></td>
                      <td class="boldfont"></td>
                      <td class="boldfont" style="text-align: right;"><span>{{$currency}} </span>{{number_format($expense->expense_amt,  $decimalLength )}}</td>
                    </tr>
                    @endforeach -->

                    <!-- <tr>
                      <td colspan="4"><hr/></td>
                    </tr> -->
                    <!-- @php
                      $totalExpense = collect($expenses)->sum('expense_amt');
                    @endphp

                    <tr style="border-top: 2px solid black;">
                      <td class="boldfont" style="border-bottom: 2px solid black;">Expenses</td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;">Total</td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="text-align: right;border-bottom: 2px solid black;"><span>{{$currency}} </span>{{number_format($totalExpense,  $decimalLength )}}</td>
                    </tr> -->

                    <!-- <tr>
                      <td colspan="4"><hr/><hr/></td>
                    </tr> -->

                    <tr>
                      <th style="border-bottom: 2px solid black;" colspan="2">Total Amt of Sales: </th>
                      <td style="border-bottom: 2px solid black;"></td>
                      <td style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;">{{$totalSaleQty}}</td>
                      <td class="boldfont" style="border-bottom: 2px solid black;">{{$totalReturnsQty}}</td>
                      <td style="border-bottom: 2px solid black;"></td>
                      <td colspan="2" class="boldfont" style="text-align: right;border-bottom: 2px solid black;">
                        {{  number_format($totalCashAmount + $totalTransferAmount + $totalCreditAmount , $decimalLength) }}
                      </td>
                    </tr>

                    <!-- <tr>
                      <th>Total Amt of Cash: </th>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td class="boldfont" style="text-align: right;">
                      <td class="boldfont" style="text-align: right;">
                      <span>{{$currency}} </span> {{  number_format($totalCashAmount , $decimalLength) }}
                      </td>
                    </tr>
                    <tr>
                      <th>Total Amt of Expenses: </th>
                      <td class="boldfont"></td>
                      <td class="boldfont"></td>
                      <td class="boldfont"></td>
                      <td class="boldfont"></td>
                      <td class="boldfont"></td>
                      <td class="boldfont" style="text-align: right;white-space: nowrap;">
                      <span >(-) {{$currency}} </span> {{  number_format($totalExpense , $decimalLength) }}
                      </td>
                    </tr>

                    @php
                      $netAmt = $totalCashAmount - $totalExpense
                    @endphp

                    <tr>
                      <th style="border-bottom: 2px solid black;">Cash in Hand: </th>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="border-bottom: 2px solid black;"></td>
                      <td class="boldfont" style="text-align: right;border-bottom: 2px solid black;">
                      <td class="boldfont" style="text-align: right;border-bottom: 2px solid black;">
                      <span>{{$currency}} </span> {{  number_format($netAmt , $decimalLength) }}
                      </td>
                    </tr> -->
                    <!-- <tr>
                      <td colspan="6"><hr/></td>
                    </tr> -->
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

<div class="hidden-print" style="margin-left:10px; text-align: center;">
  <button class="btn btn-primary printbutton" id="print" >Print</button>
  <button>
    <a class="btn btn-primary nextbutton " style="text-decoration: none;color: #000;" 
    href="{{route('m_report')}}">
    <i class="fa fa-plus"></i> Close</a>
  </button>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    
    $('.printbutton').on('click', function () {
      console.log("coming1")
      printDiv()
    });

    function printDiv() {
      var content = $('#mReport').html();
      console.log(content)
      var printWindow = window.open('', '', 'height=800,width=600');
      printWindow.document.write('<html><head><title>Print</title>');
      printWindow.document.write('<style>');
      printWindow.document.write('.currency { margin-right: 5px; float: right; }');
      printWindow.document.write('.boldfont { font-weight: bolder; }');
      printWindow.document.write('.table-container { width: 100%; display: flex; justify-content: center; margin: 10px 0; }');
      printWindow.document.write('.responsive-table { width: 100%; max-width: 800px; border-collapse: collapse; margin: 0 auto; text-align: left; }');
      printWindow.document.write('.responsive-table th, .responsive-table td { padding: 8px 12px; border-bottom: 1px solid #ddd; }');
      printWindow.document.write('</style>'); 
      printWindow.document.write('</head><body>');
      printWindow.document.write(content);
      printWindow.document.write('</body></html>');
      printWindow.document.close();
      printWindow.print();
    }

  });
</script>
