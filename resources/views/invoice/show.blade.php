

@php
$paperWidth = "500px";
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
                <h5 class="mb-0" style="white-space: nowrap;">Date:<p class="mx-1 mb-0 d-inline"></p>{{$invoice->created_at->format('d-m-Y')}}</h5>
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
                  <br>Phone: {{$invoice->customer->mobile}}
                  <br>Email:<p class="mx-1 mb-0 d-inline"></p><p style=" white-space: normal;word-wrap: break-word;overflow-wrap: break-word;">{{$invoice->customer->email}}</p>
                </address>
              </div>
              <div class="col-4">
                <b class="d-inline">Receipt ID:<p class="mx-1 mb-0 d-inline"></p> #{{1000+$invoice->id}}</b><br>
                <b class="d-inline">Payment Type:<p class="mx-1 mb-0 d-inline"></p></b> {{$invoice->payment_type}}<br>
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


<div class="wrapper wrapper-content animated fadeInRight" hidden>
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
            <h4><span style="text-align: center;">Invoice</span></h4>
            <h5 style="font-size: 14px;">Taken: {{ \Carbon\Carbon::now()->format('d-m-Y h:i a') }} </h5>
            <hr style="margin: 10px 20px; width: {{ $paperWidth }};"/>
            
            <div style="margin: 10px 20px; width: {{ $paperWidth }}; text-align: left;">
              <hr/>
            </div>
            <div class="hr-line-dashed"></div>

            <table class="table" style="margin: 10px 10px 10px 20px; width: {{ $paperWidth }}; text-align: left;">
              <tr>
                <td colspan="2">ReceiptID: <b style="font-size: 14px;">#{{1000+$invoice->id}}</b></td>
                <td colspan="2" style="text-align: end;">PaymentType: <b style="font-size: 14px;">{{$invoice->payment_type}}</b></td>
              </tr>
              <tr>
                <td >Date: </td>
                <td colspan="3"style="text-align: end;"><b style="font-size: 14px;">{{$invoice->created_at->format('d-m-Y')}}</b></td>
              </tr>    
              <tr>
                <td colspan="4"><hr/><hr/></td>
              </tr>

              <tr>
                <td>To</td>
                <td colspan="3">{{$invoice->customer->company_name}}</td>
              </tr>

              <tr>
                <td colspan="4"><hr/><hr/></td>
              </tr>
            
              <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th style="text-align: end;">Amt</th>
              </tr>
              <tr>
                <td colspan="4"><hr/></td>
              </tr>
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
                  <td class="text-right text-md-left " style="text-align: end;">
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
              <tr>
                <td colspan="4"><hr/></td>
              </tr>
                <tr>
                  <td colspan="2" ><b>Total Amt</b></td>
                  <td colspan="2" style="text-align: end;" class="text-right text-md-left">{{$currency}} {{ number_format($amount->total_amount, $decimalLength) }}</td>
                </tr>
                <tr >
                  <td colspan="2" ><b>Amt Paid</b></td>
                  <td colspan="2" style="text-align: end;" class="text-right text-md-left">{{$currency}} {{ number_format($amount->received_amt, $decimalLength) }}</td>
                </tr>
                @if(number_format($amount->prev_acc_bal_amt, $decimalLength)  > 0)
                <tr>
                  <td colspan="2" ><b>Prev Acc Bal Amt</b></td>
                  <td colspan="2" style="text-align: end;" class="text-right text-md-left">{{$currency}} {{ number_format($amount->prev_acc_bal_amt, $decimalLength) }}</td>
                </tr>
                @endif
                @if((number_format($amount->acc_bal_amt, $decimalLength) + number_format($currentBalAmt, $decimalLength))  > 0)
                <tr>
                  <td colspan="2" ><b>Acc Bal Amt</b></td>
                  <td colspan="2" style="text-align: end;" class="text-right text-md-left">{{$currency}} {{number_format( $amount->acc_bal_amt + $currentBalAmt, $decimalLength) }}</td>
                </tr>
                @endif

                @if(($amount->received_amt - $totAmt ) > 0)
                <tr>
                  <td colspan="2" ><b>Bal Amt</b></td>
                  <td colspan="2" style="text-align: end;" class="text-right text-lg-left">{{$currency}} {{ number_format($amount->received_amt - $totAmt , $decimalLength) }}</td>
                </tr>
                @endif
                <tr>
                <td colspan="4"><hr/><hr/></td>
              </tr>
              <tr>
                <td colspan="4" style="text-align:center;">**** Thank you for shopping with us! ****</td>
              </tr>
              <tr>
                <td colspan="4"><hr/></td>
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





