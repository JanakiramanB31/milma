@extends('layouts.master')

@section('titel', 'Home | ')
@section('content')
  @include('partials.header')
  @include('partials.sidebar')
    <main class="app-content">

      <div class="app-title">
        <div>
          <h1><i class="fa fa-file-text"></i> Reports</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Reports</a></li>
        </ul>
      </div>


      <div class="row mt-2">
        <div class="col-md-12">
          <div class="tile">
            <!-- Alert Error Section -->
          <div id="alert-message" class="alert alert-danger" role="alert" hidden></div>
            
            <div class="tile-body">
              <div class="row">
                <div class="form-group col-md-6 mb-2">
                  <label for="fetchDate">Search By Date :</label>
                  <input id="fetchDate" name="fetchDate" type="date" class="form-control " value="{{ date('Y-m-d') }}"/>
                </div>
                <div class="form-group col-md-6">
                  <label class="control-label">Route</label>
                  <select name="route_id" id='route_id' class="form-control @error('route_id') is-invalid @enderror">
                    <option value=''>Select Route</option>
                    @foreach($routes as $route)
                    <option value="{{ $route['id'] }}" {{ old('route_id') == $route['id'] ? 'selected' : '' }}>
                      {{ $route['route_number'] }}
                    </option>
                    @endforeach
                  </select>
                  @error('route_id')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label class="control-label">Company</label>
                  <select name="company_name" id='company_name' class="form-control @error('company_name') is-invalid @enderror">
                    <option value=''>All Companies</option>
                    @foreach ($groupedInvoices as $companyName => $invoices)
                    <option value="{{ $companyName }}" {{ old('company_name') == $companyName ? 'selected' : '' }}>
                      {{ $companyName }}
                    </option>
                    @endforeach
                  </select>
                  @error('route_id')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <div class="form-group col-md-6">
                  <label class="form-label">Payment Type</label>
                  <select id="payment_type" name="payment_type" class="form-control">
                    <option value = ''>Select Payment Type</option>
                    @foreach($paymentMethods as $paymentMethod)
                    <option name="payment_type"  value="{{$paymentMethod}}">{{$paymentMethod}}</option>
                    @endforeach
                  </select>
                  <div id="payment-type-error" class="text-danger"></div>
                </div>
              </div>
             
              <div class="form-group  ">

              <div class="tile-body table-responsive">
                <table class="table table-hover table-bordered" id="sampleTable">
                  <thead>
                    <tr>
                      <th class="text-center">Invoice ID </th>
                      <th class="text-center">Date</th>
                      <th class="text-center">Customer Name </th>
                      <th class="text-center">Payment Type </th>
                      <th class="text-center">Total Amt</th>
                      <th class="text-center">Received Amt</th>
                      <th class="text-center">Acc Bal Amt</th>
                      <th class="text-center">Bal Amt</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($filteredInvoices as $companyName => $invoice) 
                    @php
                        $totalAmount = $filteredInvoices->sum('total_amount');
                    @endphp
                    <tr>
                      <td class="text-center">{{1000+$invoice->id}}</td>
                      <td class="text-center">{{$invoice->created_at->format('d-m-Y')}}</td>
                      <td class="text-center">{{$invoice->customer->name}}</td>
                      <td class="text-center">{{$invoice->payment_type}}</td>
                      <td class="text-center"><span>{{$currency}} </span>{{ number_format($invoice->total_amount,  $decimalLength )}}</td>
                      <td class="text-center"><span>{{$currency}} </span>{{number_format($invoice->received_amt,  $decimalLength )}}</td>
                      <td class="text-center"><span>{{$currency}} </span>{{number_format($invoice->acc_bal_amt,  $decimalLength )}}</td>
                      <td class="text-center"><span>{{$currency}} </span>{{number_format($invoice->balance_amt,  $decimalLength )}}</td>
                      <td class="d-flex justify-content-center" >
                        <a class="btn btn-info btn-sm" href="{{ route('invoice.show', '') }}/${invoice.id}"><i class="fa fa-eye" ></i></a>
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
                      <td id="tot-amt" class="text-center"><span>{{$currency}} </span>{{$totalAmount}}</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <!-- @foreach ($groupedInvoices as $companyName => $invoices) 
              <div class="col-md-12 col-lg-12 my-3">
                <div>
                  <a class="btn btn-primary w-100 p-2 text-left" data-toggle="collapse" href="#collapse-{{ Str::slug($companyName) }}" role="button" aria-expanded="false" aria-controls="collapse-{{ Str::slug($companyName) }}">
                    {{$companyName}}
                  </a>
                </div>
                
                <div class="collapse" id="collapse-{{ Str::slug($companyName) }}">
                  <div class="row">
                    <div class="col">
                      <div class="card card-body">
                        <div class="mb-2">
                          <label for="fetchDate">Search By Date :</label>
                          <input data-company-name="{{$companyName}}" name="fetchDate" type="date" class="form-control fetchDate"/>
                        </div>
                        <div class="tile-body table-responsive">
                          <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                              <tr>
                                <th class="text-center">Invoice ID </th>
                                <th class="text-center">Customer Name </th>
                                <th class="text-center">Payment Type </th>
                                <th class="text-center">Total Amt</th>
                                <th class="text-center">Received Amt</th>
                                <th class="text-center">Acc Bal Amt</th>
                                <th class="text-center">Bal Amt</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($invoices as $invoice)
                              <tr>
                                <td class="text-center">{{$invoice->id}}</td>
                                <td class="text-center">{{$invoice->customer->name}}</td>
                                <td class="text-center">{{$invoice->payment_type}}</td>
                                <td class="text-center">{{$invoice->total_amount}}</td>
                                <td class="text-center">{{$invoice->received_amt}}</td>
                                <td class="text-center">{{$invoice->acc_bal_amt}}</td>
                                <td class="text-center">{{$invoice->balance_amt}}</td>
                                <td class="text-center">{{$invoice->created_at->format('d-m-Y')}}</td>
                                <td class="d-flex justify-content-center" style="gap: 10px;">
                                  <a class="btn btn-info btn-sm" href="{{route('invoice.show', $invoice->id)}}"><i class="fa fa-eye" ></i></a>
                                </td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                </div>
              @endforeach -->

                
                
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
   

    function generateSlug(str) {
      return str
        .toLowerCase()                 
        .trim()                         
        .replace(/[^a-z0-9 -]/g, '')  
        .replace(/\s+/g, '-')        
        .replace(/-+/g, '-');         
    }

    // function fetchInvoiceByDate(data) {
    //   const data1Json = JSON.stringify(data);
    //   if(data) {
    //     $.ajax({
    //       url: '{{ route("fetchCompanyInvoices",":data") }}'.replace(':data', data1Json),
    //       type: 'POST',
    //       data: {
    //         data: data1Json,
    //         _token: '{{ csrf_token() }}' 
    //       },
    //       success: function(response) {
    //         try {
    //           const collapseId = "#collapse-" + generateSlug(data.companyName); 
    //           console.log("Success",response);  
    //           $(collapseId).find('table tbody').empty();
    //           if (response.length > 0) {
    //             response.forEach(invoice => {
    //               $(collapseId).find('table tbody').append(`
    //                   <tr>
    //                     <td class="text-center">${invoice.id}</td>
    //                     <td class="text-center">${invoice.customer.name}</td>
    //                     <td class="text-center">${invoice.payment_type}</td>
    //                     <td class="text-center">${invoice.total_amount}</td>
    //                     <td class="text-center">${invoice.received_amt}</td>
    //                     <td class="text-center">${invoice.acc_bal_amt}</td>
    //                     <td class="text-center">${invoice.balance_amt}</td>
    //                     <td class="text-center">${new Date(invoice.created_at).toLocaleDateString('en-GB')}</td>
    //                     <td class="d-flex justify-content-center" style="gap: 10px;">
    //                       <a class="btn btn-info btn-sm" href="{{ route('invoice.show', '') }}/${invoice.id}"><i class="fa fa-eye" ></i></a>
    //                     </td>
    //                   </tr>
    //               `);
    //             });
    //           } else {
    //             $(collapseId).find('table tbody').append('<tr><td colspan="9" class="text-center">No invoices found for this date.</td></tr>');
    //           }               
    //         } catch(error) {
    //           console.log("Failed",error)
    //         }
    //       },
    //       error: function(xhr) {
    //         var errorMessage =  'An error occurred. Please try again.';
    //         $('#error-message').html(errorMessage).show();
    //       }
    //     });
    //   } else {
    //     console.log("Failed")
    //   }
    // }

    $('#route_id,#company_name,#fetchDate,#payment_type').on('change', function () {
      var routeID = $('#route_id').find('option:selected').val();
      var companyName = $('#company_name').find('option:selected').val();
      var paymentMethod = $('#payment_type').find('option:selected').val();
      let date = $('#fetchDate').val();
      let selectedDate = $('#fetchDate').val();
      console.log(selectedDate,companyName, routeID, paymentMethod);
      const data = {
        "routeID": routeID,
        "selectedDate": selectedDate,
        "companyName": companyName,
        "paymentMethod": paymentMethod
      }
      fetchInvoices(data);
    });

    function fetchInvoices(data) {
      const data1Json = JSON.stringify(data);
      if(data) {
        $.ajax({
          url: '{{ route("fetchCompanyInvoices",":data") }}'.replace(':data', data1Json),
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
                        <td class="text-center">${invoice.customer.name}</td>
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

  });
</script>
@endpush