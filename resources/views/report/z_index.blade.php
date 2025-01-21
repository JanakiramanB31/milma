@extends('layouts.master')

@section('titel', 'Home | ')
@section('content')
  @include('partials.header')
  @include('partials.sidebar')
    <main class="app-content">

      <div class="app-title">
        <div>
          <h1><i class="fa fa-file-text"></i> Z-Report</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Z-Report</a></li>
        </ul>
      </div>

      <div class="row mt-2">
        <div class="col-md-12">
          <div class="tile">
            <!-- Alert Error Section -->
          <div id="alert-message" class="alert alert-danger" role="alert" hidden></div>
            
            <div class="tile-body">
              <div>
                <label  for="startDate">Date :</label>
                <div class="row">
                  <div class="col-6  mb-2">
                    <input id="startDate" name="startDate" type="date" class="form-control" value="{{ date('Y-m-d') }}" @if(Auth::user()->role != 'admin') readonly @endif/>
                  </div>
                  <div class=" d-flex justify-content-center align-items-center">
                    <p>To</p>
                  </div>
                  <div class="col-5 mb-2">
                    <input id="endDate" name="endDate" type="date" class="form-control" value="{{ date('Y-m-d') }}"@if(Auth::user()->role != 'admin') readonly @endif/>
                  </div>

                  <div class="ml-2">
                  <button class="btn btn-success" id="print">Print</button>
                </div>
                 
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                  <label class="control-label">Company</label>
                  <select name="company_name" id='company_name' class="form-control @error('company_name') is-invalid @enderror">
                    <option value=''>All Companies</option>
                    @foreach ($groupedInvoices as $companyName => $invoices)
                    <option value="{{ $companyName }}" {{ old('company_name') == $companyName ? 'selected' : '' }}>
                      {{ $companyName }}
                    </option>
                    @endforeach
                  </select>
                </div>
                </div>

             
              <div class="form-group">
                <div class="tile-body table-responsive">
                  <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                      <tr>
                        <th class="text-center">Invoice ID </th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Company Name </th>
                        <th class="text-center">Product Name</th>
                        <th class="text-center">Payment Type </th>
                        <th class="text-center">Total Amt</th>
                        <th class="text-center">Received Amt</th>
                        <th class="text-center">Acc Bal Amt</th>
                        <th class="text-center">Bal Amt</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    @php
                          $totalAmount = $filteredInvoices->sum('total_amount') ?? '0';
                      @endphp
                    <tbody>
                    @foreach ($filteredInvoices as $companyName => $invoice) 
                      
                      <tr>
                        <td class="text-center">{{1000+$invoice->id}}</td>
                        <td class="text-center">{{$invoice->created_at->format('d-m-Y')}}</td>
                        <td class="text-center">{{$invoice->customer->company_name}}</td>
                        <td>
                          @foreach($invoice->sales as $key => $sale)
                            {{ $sale->product->name }}
                            @if($invoice->sales->count() > 1 && $key < $invoice->sales->count() - 1)
                              ,
                            @endif
                          @endforeach
                        </td>
                        <td class="text-center">{{$invoice->payment_type}}</td>
                        <td class="text-center"><span>{{$currency}} </span>{{ number_format($invoice->total_amount,  $decimalLength )}}</td>
                        <td class="text-center"><span>{{$currency}} </span>{{number_format($invoice->received_amt,  $decimalLength )}}</td>
                        <td class="text-center"><span>{{$currency}} </span>{{number_format($invoice->acc_bal_amt,  $decimalLength )}}</td>
                        <td class="text-center"><span>{{$currency}} </span>{{number_format($invoice->balance_amt,  $decimalLength )}}</td>
                        <td class="d-flex justify-content-center" >
                          <a class="btn btn-info btn-sm" href="{{ route('invoice.show', '') }}/{{$invoice->id}}"><i class="fa fa-eye" ></i></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right"><b>Total</b></td>
                        <td id="tot-amt" class="text-center"><span>{{$currency}} </span>{{number_format($totalAmount,  $decimalLength )}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


  </main>
@endsection

@push('js')
<script type="text/javascript">
  $(document).ready(function () {
    $('#company_name,#startDate,#endDate').on('change', function () {
      var companyName = $('#company_name').find('option:selected').val();
      let fromDate = $('#startDate').val();
      let toDate = $('#endDate').val();
      console.log(fromDate,companyName, toDate);
      const data = {
        "fromDate": fromDate,
        "toDate": toDate,
        "companyName": companyName,
      }
      fetchInvoices(data);
    });

    function fetchInvoices(data) {
      const data1Json = JSON.stringify(data);
      if(data) {
        $.ajax({
          url: '{{ route("zReportCompanyInvoices",":data") }}'.replace(':data', data1Json),
          type: 'POST',
          data: {
            data: data1Json,
            _token: '{{ csrf_token() }}' 
          },
          success: function(response) {
            try {
              console.log("Success",response);  
              $('#sampleTable tbody').empty();
              const currency = response.currency + " ";
              const decimalLength = response.decimalLength;
              const invoiceData = response.filteredInvoices;
              const totalAmt = invoiceData.reduce((sum, invoice) => sum + parseFloat(invoice.total_amount), 0);
              $('#tot-amt').text(currency + parseFloat(totalAmt).toFixed(decimalLength))
              if (invoiceData.length > 0) {
                invoiceData.forEach(invoice => {
                  $('#sampleTable tbody').append(`
                      <tr>
                        <td class="text-center">${1000+(invoice.id)}</td>
                        <td class="text-center">${new Date(invoice.created_at).toLocaleDateString('en-GB')}</td>
                        <td class="text-center">${invoice.customer.company_name}</td>
                        <td class="text-center">${invoice.sales.map(item => item.product.name).join(', ')}</td>
                        <td class="text-center">${invoice.payment_type}</td>
                        <td class="text-center">${currency +parseFloat(invoice.total_amount).toFixed(decimalLength)}</td>
                        <td class="text-center">${currency +parseFloat(invoice.received_amt).toFixed(decimalLength)}</td>
                        <td class="text-center">${currency +parseFloat(invoice.acc_bal_amt).toFixed(decimalLength)}</td>
                        <td class="text-center">${currency +parseFloat(invoice.balance_amt).toFixed(decimalLength)}</td>
                        <td class="d-flex justify-content-center" style="gap: 10px;">
                          <a class="btn btn-info btn-sm" href="{{ route('invoice.show', '') }}/${parseInt(invoice.id)}"><i class="fa fa-eye" ></i></a>
                        </td>
                      </tr>
                  `);
                });
              } else {
                $('#sampleTable tbody').append('<tr><td colspan="9" class="text-center">No invoices found for this date.</td></tr>');
              }               
            } catch(error) {
              console.log("Failed",error)
            }
          },
          error: function(xhr) {
            var errorMessage =  'An error occurred. Please try again.';
            $('#error-message').html(errorMessage).show();
          }
        });
      } else {
        console.log("Failed")
      }
    }

    $('#print').on('click', function () {
      var companyName = $('#company_name').find('option:selected').val();
      let fromDate = $('#startDate').val();
      let toDate = $('#endDate').val();
      console.log(fromDate,companyName, toDate);
      const data = {
        "fromDate": fromDate,
        "toDate": toDate,
        "companyName": companyName,
      }
      console.log(data)
      printData(data);
    });

    function printData(data) {
      const data1Json = encodeURIComponent(JSON.stringify(data));
      var prinURL = '{{ route("zReportPrintCompanyInvoices",":data") }}'.replace(':data', data1Json);
      window.location.href = prinURL;
      // if(data) {
      //   $.ajax({
      //     url: '{{ route("x_report_print",":data") }}'.replace(':data', data1),
      //     type: 'POST',
      //     data: {
      //       data: data,
      //       _token: '{{ csrf_token() }}' 
      //     },
      //     success: function(response) {
      //       try {
      //         console.log("Success", response);  
                  
      //       } catch(error) {
      //         console.log("Failed",error);
              
      //       }
      //     },
      //     error: function(xhr) {
      //       var errorMessage =  'An error occurred. Please try again.';
      //       $('#alert-message').html(errorMessage).show();
      //     }
      //   });
      // } else {
      //   console.log("Failed")
      // }
    }
  });
</script>
@endpush