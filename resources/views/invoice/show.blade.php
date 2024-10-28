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
                  @php
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
                  @endphp
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
                      <td class="text-right text-md-left ">£{{ number_format($sale->price, 2) }}</td>
                      <td class="text-right text-md-left ">
                        @if($sale->type == "sales")
                        <b></b>
                        @else
                        <b>(-)</b>
                        @endif
                        £{{ number_format($sale->qty * $sale->price, 2) }}
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
                      <td class="text-right text-md-left"><b class="total"  >£{{ number_format($amount->total_amount, 2) }}</b></td>
                    </tr>
                    <tr >
                      <td></td>
                      <td></td>
                      <td style="text-align: end;"><b>Amt Paid</b></td>
                      <td class="text-right text-md-left"><b class="total">£{{ number_format($amount->received_amt, 2) }}</b></td>
                    </tr>
                    @if(number_format($amount->prev_acc_bal_amt)  > 0)
                    <tr>
                      <td></td>
                      <td></td>
                      <td style="text-align: end;"><b>Prev Acc Bal Amt</b></td>
                      <td class="text-right text-md-left"><b class="total"  >£{{ number_format($amount->prev_acc_bal_amt, 2) }}</b></td>
                    </tr>
                    @endif
                    @php
                        $currentBalAmt = $amount->total_amount - $amount->received_amt;
                        $amount->acc_bal_amt + number_format($currentBalAmt);
                    @endphp
                    @if((number_format($amount->acc_bal_amt,2) + number_format($currentBalAmt, 2))  > 0)
                    <tr>
                      <td></td>
                      <td></td>
                      <td style="text-align: end;"><b>Acc Bal Amt</b></td>
                      <td class="text-right text-md-left"><b class="total"  >£{{number_format( $amount->acc_bal_amt + $currentBalAmt,2) }}</b></td>
                    </tr>
                    @endif
                    @php
                       $totAmt = $amount->total_amount + $amount->acc_bal_amt;
                    @endphp
                    @if(($amount->received_amt - $totAmt ) > 0)
                    <tr>
                      <td></td>
                      <td></td>
                      <td style="text-align: end;"><b>Bal Amt</b></td>
                      <td class="text-right text-lg-left"><b class="total">£{{ number_format($amount->received_amt - $totAmt , 2) }}</b></td>
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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script>
    function printInvoice() {
      window.print();
    }
    $(document).ready(function(){

      //Back to Index Page Button
      $('#invoice-close-btn').on('click', function() {
          window.location.href = "{{ route('invoice.index') }}";
        });
    });
  </script>

@endsection
@push('js')
@endpush





