@php
$paperWidth = "300px";
  $salesTotal = 0;
  $returnsTotal = 0;

  foreach ($sales as $sale) {
  if ($sale->type == "sales") {
  $salesTotal += $sale->amount; 
  } else { 
  $returnsTotal += $sale->amount;
  }
  }
  $total = $salesTotal - $returnsTotal; 
  $currentBalAmt = $amount->total_amount - $amount->received_amt;
  $amount->acc_bal_amt + number_format($currentBalAmt);
  $totAmt = $amount->total_amount + $amount->acc_bal_amt;
@endphp
@extends('layouts.master')

@section('title', 'Invoice | ')
@section('content')
  @include('partials.header')
  @include('partials.sidebar')
  <main class="app-content">
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          <section class="invoice">
            <div class=" mb-3 d-flex w-100 justify-content-between align-items-center">
              <div>
                <h2 class="page-header mb-0"><i class="fa fa-file"></i> MILMA</h2>
              </div>
              <div>
                <p class="mb-0 d-inline" style="white-space: nowrap;">Date:<p class="mx-1 mb-0 d-inline"></p><b>{{$invoice->created_at->format('d-m-Y H:i:s')}}</b></p>
              </div>
            </div>
            <div class="row invoice-info">
              <div class="col-4">From
                <address><strong>Milma</strong><br>Address<br>milma.com</address>
              </div>
              <div class="col-4">To
                <address>
                  <strong>{{$invoice->customer->company_name}}</strong>
                  <br>{{$invoice->customer->address}}
                  <br>Phone: {{$invoice->customer->mobile ? $invoice->customer->mobile : "12345678"}}
                  <br>Email:<p class="mx-1 mb-0 d-inline"></p><p style=" white-space: normal;word-wrap: break-word;overflow-wrap: break-word;">{{$invoice->customer->email ? $invoice->customer->email : "abc@gmail.com"}}</p>
                </address>
              </div>
              <div class="col-4">
                <b class="d-inline">Receipt No:<p class="mx-1 mb-0 d-inline"></p> #{{1000+$invoice->id}}</b><br>
                <b class="d-inline">Payment:<p class="mx-1 mb-0 d-inline"></p></b> {{$invoice->payment_type}}<br>
                <!-- <b class="d-inline">Order ID:</b> 4F3S8J<br> -->
                <b class="d-inline">Payment Due:<p class="mx-1 mb-0 d-inline"></p></b> {{$invoice->created_at->format('d-m-Y')}}<br>
                <!-- <b class="d-inline">Account:</b> 000-12345 -->
              </div>
            </div>
            <div class="row d-flex justify-content-center">
              <div class="col-12 table-responsive">
                <table class="table table-striped col-12">
                  <thead>
                    <tr>
                      <th>Product</th>
                      <th>Qty</th>
                      <th >Price</th>
                      <th class="text-left" >Amt</th>
                    </tr>
                  </thead>
                  
                  <tbody>
                    <div style="display: none">
                      {{$total}}
                    </div>
                    @foreach($sales as $sale)
                    <tr>
                      <td >
                        @if($sale->type == "sales")
                        @else
                        <b style="color: red;">R</b>
                        @endif
                        {{$sale->product->name}}
                      </td>
                      <td>{{ $sale->qty }}{{$sale->product->unit->name}}</td>
                      <td class="text-right text-md-left ">{{$currency}} {{ number_format($sale->price, $decimalLength) }}</td>
                      <td class="text-right text-md-left ">
                        @if($sale->type == "sales")
                        <b></b>
                        @else
                        <b>(-)</b>
                        @endif
                        {{$currency}} {{ number_format($sale->qty * $sale->price, $decimalLength) }}
                      </td>
                      <div style="display: none">
                        {{$total }}
                      </div>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td></td>
                      <td style="text-align: end;"><b>Total Amt</b></td>
                      <td class="text-right text-md-left"><b class="total"  >{{$currency}} {{ number_format($amount->total_amount, $decimalLength) }}</b></td>
                    </tr>
                    <tr >
                      <td></td>
                      <td></td>
                      <td style="text-align: end;"><b>Amt Paid</b></td>
                      <td class="text-right text-md-left"><b class="total">{{$currency}} {{ number_format($amount->received_amt, $decimalLength) }}</b></td>
                    </tr>
                    @if(number_format($amount->prev_acc_bal_amt, $decimalLength)  > 0)
                    <tr>
                      <td></td>
                      <td></td>
                      <td style="text-align: end;"><b>Prev Acc Bal Amt</b></td>
                      <td class="text-right text-md-left"><b class="total"  >{{$currency}} {{ number_format($amount->prev_acc_bal_amt, $decimalLength) }}</b></td>
                    </tr>
                    @endif
                    @if((number_format($amount->acc_bal_amt, $decimalLength) + number_format($currentBalAmt, $decimalLength))  > 0)
                    <tr>
                      <td></td>
                      <td></td>
                      <td style="text-align: end;"><b>Acc Bal Amt</b></td>
                      <td class="text-right text-md-left"><b class="total"  >{{$currency}} {{number_format( $amount->acc_bal_amt + $currentBalAmt, $decimalLength) }}</b></td>
                    </tr>
                    @endif

                    @if(($amount->received_amt - $totAmt ) > 0)
                    <tr>
                      <td></td>
                      <td></td>
                      <td style="text-align: end;"><b>Bal Amt</b></td>
                      <td class="text-right text-lg-left"><b class="total">{{$currency}} {{ number_format($amount->received_amt - $totAmt , $decimalLength) }}</b></td>
                    </tr>
                    @endif
                  </tfoot>
                </table>
              </div>
            </div>
            <!-- <div class="row d-print-none mt-2">
              <div class="col-12 text-right"><a class="btn btn-primary" href="javascript:void(0);" onclick="printInvoice();"><i class="fa fa-print"></i> Print</a></div>
            </div> -->

             <!-- Close Button and Print Button -->
             <div class="d-flex justify-content-center" style="gap: 10px;">
                <!-- Back to Index Page Button -->
                <div>
                  <button id="invoice-close-btn" type="button" class="btn btn-danger">Close</button>
                </div>

                <!-- Print Button -->
                <div >
                  <a href="javascript:void(0);" class="btn btn-primary" onclick="printInvoice();"><i class="fa fa-print"></i> Print</a>
                </div>
              </div>
          </section>
        </div>
      </div>
    </div>
  </main>
@endsection

<html>
  <body style="width: 100%; ">
<div style="margin: 0 0 0 30px !important;padding: 0px !important;" hidden>
  <div style="font-family: monospace;"  id="xReport">
    <style>
      .currency {
        margin-right: 5px;
        float: right;
      }
    </style>
    <div style="width: {{ $paperWidth }};">
      <div >
        <div >
          <div class="hr-line-dashed" style="width: 100%;"></div>
          <div style="font-family: monospace;">
            <image  src={{asset('Milma_Logo.jpg')}} width='200px' height='100px'/>
            <h3 style="text-align: center;"><span style="text-align: center;">MILMA FOODS UK LIMITED</span></h3>
            <div class="d-flex justify-content-center align-items-center">
              <p style="margin: 0; padding: 0;">442, RAILWAY ARCHS</p>
              <p style="margin: 0; padding: 0;">CRAMMER ROAD</p>
              <p style="margin: 0; padding: 0;">LONDON</p>
              <p style="margin: 0; padding: 0;">E7 OJN</p>
              <p style="margin: 0; padding: 0;">TEL: 07469849031</p>
            </div>
            <h4><span style="text-align: center;">Invoice</span></h4>
            <h4><span style="text-align: center;">{{$invoice->customer->company_name}}</span></h4>
            <h5 style="font-size: 14px;">Printed On: {{ \Carbon\Carbon::now()->format('d-m-Y h:i a') }} </h5>
            <hr   style="border: 1px solid black;"/>
            
            <div style=" text-align: left;">
              <hr  style="border: 1px solid black;"/>
            </div>
            <div class="hr-line-dashed"></div>

            <table class="table" style="text-align: left;">
              <tr>
                <td colspan="2">Operator: </td>
                <td colspan="2" style="text-align: end;"><b style="font-size: 14px;">{{Str::ucfirst(Auth::user()->role)}}</b></td>
              </tr>
              <tr>
                <td colspan="2">Receipt No.</td>
                <td colspan="2" style="text-align: end;"><b style="font-size: 14px;">#{{1000+$invoice->id}}</b></td>
              </tr>
              <tr>
                <td colspan="2">Payment</td>
                <td colspan="2" style="text-align: end;"><b style="font-size: 14px;">{{$invoice->payment_type}}</b></td>
              </tr>
              <tr>
                <td colspan="2">Date:</td>
                <td colspan="2"style="text-align: end;"><b style="font-size: 14px;">{{$invoice->created_at->format('d-m-Y')}}</b></td>
              </tr>    
              <tr>
                <td colspan="4"><hr  style="border: 1px solid black;"/><hr  style="border: 1px solid black;"/></td>
              </tr>
              <!--
              <tr>
                <td>To</td>
                <td colspan="3">{{$invoice->customer->company_name}}</td>
              </tr> -->

             <!--  <tr>
                <td colspan="4"><hr/><hr/></td>
              </tr> -->
            
              <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th style="text-align: end;">Amt</th>
              </tr>
              <tr>
                <td colspan="4"><hr  style="border: 1px solid black;"/></td>
              </tr>
              @foreach($sales as $sale)
                <tr>
                  <td >
                    @if($sale->type == "sales")
                    @else
                    <b style="color: red;">R</b>
                    @endif
                    <p style="font-family:'Times New Roman', Times, serif;font-size:16px;" >{{$sale->product->name}}</p>
                  </td>
                  <td>{{ $sale->qty }}{{$sale->product->unit->name}}</td>
                  <td class="text-right text-md-left "  style="white-space: nowrap;"><b>{{$currency}} {{ number_format($sale->price, $decimalLength) }}</b></td>
                  <td class="text-right text-md-left " style="text-align: end;white-space: nowrap;">
                    @if($sale->type == "sales")
                    <b></b>
                    @else
                    <b>(-)</b>
                    @endif
                    <b>{{$currency}} {{ number_format($sale->qty * $sale->price, $decimalLength) }}</b>
                  </td>
                  <div style="display: none">
                    {{$total }}
                  </div>
                </tr>
              @endforeach
              <tr>
                <td colspan="4"><hr style="border: 1px solid black;"/></td>
              </tr>
              <tr>
                <td colspan="2" ><b>Total Amt</b></td>
                <td colspan="2" style="text-align: end;" class="text-right text-md-left"><b>{{$currency}} {{ number_format($amount->total_amount, $decimalLength) }}</b></td>
              </tr>
              <tr >
                <td colspan="2" ><b>Amt Paid</b></td>
                <td colspan="2" style="text-align: end;" class="text-right text-md-left"><b>{{$currency}} {{ number_format($amount->received_amt, $decimalLength) }}</b></td>
              </tr>
              @if(number_format($amount->prev_acc_bal_amt, $decimalLength)  > 0)
              <tr>
                <td colspan="2" ><b>Prev Acc Bal Amt</b></td>
                <td colspan="2" style="text-align: end;" class="text-right text-md-left"><b>{{$currency}} {{ number_format($amount->prev_acc_bal_amt, $decimalLength) }}</b></td>
              </tr>
              @endif
              @if((number_format($amount->acc_bal_amt, $decimalLength) + number_format($currentBalAmt, $decimalLength))  > 0)
              <tr>
                <td colspan="2" ><b>Acc Bal Amt</b></td>
                <td colspan="2" style="text-align: end;" class="text-right text-md-left"><b>{{$currency}} {{number_format( $amount->acc_bal_amt + $currentBalAmt, $decimalLength) }}</b></td>
              </tr>
              @endif

              @if(($amount->received_amt - $totAmt ) > 0)
              <tr>
                <td colspan="2" ><b>Bal Amt</b></td>
                <td colspan="2" style="text-align: end;" class="text-right text-lg-left"><b>{{$currency}} {{ number_format($amount->received_amt - $totAmt , $decimalLength) }}</b></td>
              </tr>
              @endif
              <tr>
                <td colspan="4"><hr  style="border: 1px solid black;"/><hr  style="border: 1px solid black;"/></td>
              </tr>
              <tr>
                <td colspan="4" style="text-align:center;">Thank you for shopping with us!</td>
              </tr>
              <tr>
                <td colspan="4"></td>
              </tr>
              <tr>
                <td colspan="4" style="text-align:center;"><image  src={{asset('Milma_Web_QR_Code.png')}} width='80px' height='80px'/></td>
              </tr>
              
              <tr>
                <td colspan="4"><hr  style="border: 1px solid black;"/></td>
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
</body>
</html>

@push('js')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script>
    // function printInvoice() {
    //   window.print();
    // }
    function printInvoice() {
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
    $(document).ready(function(){

      //Back to Index Page Button
      $('#invoice-close-btn').on('click', function() {
          window.location.href = "{{ route('invoice.index') }}";
        });
        $('.printbutton').on('click', function () {
      console.log("coming1")
      printDiv()
    });

    

    });
  </script>
@endpush





