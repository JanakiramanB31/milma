

@extends('layouts.master')

@section('title', 'Sales | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Sales Table</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Sales</li>
                <li class="breadcrumb-item active"><a href="#">Sales Table</a></li>
            </ul>
        </div>
        <div class="">
            <a class="btn btn-primary" href="{{route('invoice.create')}}"><i class="fa fa-plus"></i>New Invoice</a>
        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                    <div>
                      <label  for="startDate">Date :</label>
                      <div class="row">
                        <div class="col-6  mb-2">
                          <input id="startDate" name="startDate" type="date" class="form-control" value="{{ date('Y-m-d') }}"/>
                        </div>
                        <div class=" d-flex justify-content-center align-items-center">
                          <p>To</p>
                        </div>
                        <div class="col-5 mb-2">
                          <input id="endDate" name="endDate" type="date" class="form-control" value="{{ date('Y-m-d') }}"/>
                        </div>
                        
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-6">
                        <label class="control-label">Company</label>
                        <select name="company_name" id='company_name' class="form-control">
                          <option value=''>All Companies</option>
                          @foreach ($customers as $customer)
                          <option value="{{ $customer->company_name }}" >
                            {{ $customer->company_name }}
                          </option>
                          @endforeach
                          </option>
                          
                        </select>
                      </div>

                      <div class="form-group col-md-6">
                        <label class="form-label">Product</label>
                        <select id="prod_name" name="prod_name" class="form-control">
                          <option value = ''>All Products</option>
                          @foreach ($products as $product)
                          <option value="{{ $product->name }}" >
                            {{ $product->name }}
                          </option>
                          @endforeach
                        </select>
                        <div id="payment-type-error" class="text-danger"></div>
                      </div>
                    </div>
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>                                
                                <th>Date </th>
                                <th>Company Name </th>
                                <th>Product </th>
                                <th>Qty </th>
                                <th>Price</th>
                                <th>Payment Type</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sales as $sale)
                              <tr>  
                                <td>{{ $sale->created_at->format('d-m-Y') }}</td>
                                <td>{{ $sale->customer->company_name }}</td>
                                <td>{{ $sale->product->name }}</td>
                                <td>{{ $sale->qty }}</td>
                                <td ><span>{{$currency}} </span>{{number_format($sale->price,  $decimalLength )}}</td>
                                <td>{{ $sale->invoice->payment_type }}</td>
                                <td ><span>{{$currency}} </span>{{number_format($sale->total_amount,  $decimalLength )}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>



@endsection

@push('js')
    <script type="text/javascript" src="{{asset('/')}}js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{asset('/')}}js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
    <script src="https://unpkg.com/sweetalert2@7.19.1/dist/sweetalert2.all.js"></script>
    <script>
    $(document).ready(function () {

      $('#startDate,#company_name,#endDate,#prod_name').on('change', function () {
        var fromDate = $('#startDate').val();
        var companyName = $('#company_name').find('option:selected').val();
        var productName = $('#prod_name').find('option:selected').val();
        let toDate = $('#endDate').val();
        console.log(fromDate,companyName, toDate, productName);
        const data = {
          "fromDate": fromDate,
          "toDate": toDate,
          "companyName": companyName,
          "productName": productName
        }
        fetchInvoices(data);
      });

      function fetchInvoices(data) {
      const data1Json = JSON.stringify(data);
      if(data) {
        $.ajax({
          url: '{{ route("filterSalesData",":data") }}'.replace(':data', data1Json),
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
              const salesData = response.filteredSales;
              console.log(salesData)
              if (salesData.length > 0) {
                salesData.forEach(sale => {
                  $('#sampleTable tbody').append(`
                      <tr>
                        <td >${new Date(sale.created_at).toLocaleDateString('en-GB')}</td>
                        <td >${sale.customer.company_name}</td>
                        <td >${sale.product.name}</td>
                        <td >${sale.qty}</td>
                        <td >${currency +parseFloat(sale.price).toFixed(decimalLength)}</td>
                        <td >${sale.invoice.payment_type}</td>
                        <td >${currency +parseFloat(sale.total_amount).toFixed(decimalLength)}</td>
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
