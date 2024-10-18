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
            <div class="row mb-4">
              <div class="col-6">
                <h2 class="page-header"><i class="fa fa-file"></i> MILMA</h2>
              </div>
              <div class="col-6">
                <h5 class="text-right">Date: {{$invoice->created_at->format('d-m-Y')}}</h5>
              </div>
            </div>
            <div class="row invoice-info">
              <div class="col-4">From
                <address><strong>Milma</strong><br>Address<br>milma.com</address>
              </div>
              <div class="col-4">To
                <address>
                  <strong>{{$invoice->customer->name}}</strong>
                  <br>{{$invoice->customer->address}}
                  <br>Phone: {{$invoice->customer->mobile}}
                  <br>Email: <p style=" white-space: normal;word-wrap: break-word;overflow-wrap: break-word;">{{$invoice->customer->email}}</p>
                </address>
              </div>
              <div class="col-4">
                <b class="d-inline">Invoice ID: #{{1000+$invoice->id}}</b><br>
                <b class="d-inline">Payment Type:</b> {{$invoice->payment_type}}<br>
                <!-- <b class="d-inline">Order ID:</b> 4F3S8J<br> -->
                <b class="d-inline">Payment Due:</b> {{$invoice->created_at->format('d-m-Y')}}<br>
                <!-- <b class="d-inline">Account:</b> 000-12345 -->
              </div>
            </div>
            <div class="row d-flex justify-content-center">
              <div class="col-12 table-responsive">
                <table class="table table-striped">
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
                      <td>{{ number_format($sale->qty, 2) }}</td>
                      <td>£{{ number_format($sale->price, 2) }}</td>
                      <td class="text-left">
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
                      <td class="text-left"><b class="total"  >£{{ number_format($amount->total_amount, 2) }}</b></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td style="text-align: end;"><b>Prev Bal Amt</b></td>
                      <td class="text-left"><b class="total"  >£{{ number_format($amount->prev_balance_amt, 2) }}</b></td>
                    </tr>
                    <tr >
                      <td></td>
                      <td></td>
                      
                      <td style="text-align: end;"><b>Amt Paid</b></td>
                      <td class="text-left"><b class="total">£{{ number_format($amount->received_amt, 2) }}</b></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td style="text-align: end;"><b>Bal Amt</b></td>
                      <td class="text-left"><b class="total">£{{ number_format($amount->balance_amt, 2) }}</b></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="row d-print-none mt-2">
              <div class="col-12 text-right"><a class="btn btn-primary" href="javascript:void(0);" onclick="printInvoice();"><i class="fa fa-print"></i> Print</a></div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </main>

  <script>
    function printInvoice() {
      window.print();
    }
  </script>

@endsection
@push('js')
@endpush





