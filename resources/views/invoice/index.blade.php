

@extends('layouts.master')

@section('title', 'Invoice | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')

    <main class="app-content">
        <!-- <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Invoices Table</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Invoice</li>
                <li class="breadcrumb-item active"><a href="#">Invoice Table</a></li>
            </ul>
        </div> -->
        <div class="">
            <a class="btn btn-primary" href="{{route('invoice.create')}}"><i class="fa fa-plus"></i>Invoice</a>
        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                <div class="tile">
                    <div class="mb-3">
                      <label for="fetchDate">Search By Date :</label>
                      <input id="fetchDate" name="fetchDate" type="date" class="form-control"/>
                    </div>
                    <div class="tile-body table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                          <thead>
                            <tr>
                                <th>Invoice ID </th>
                                <th>Customer Name </th>
                                <!-- <th>Date </th> -->
                                <th>Action</th>
                            </tr>
                            </thead>
                             <tbody>

                             @foreach($invoices as $invoice)
                                 <tr>
                                     <td>{{1000+$invoice->id}}</td>
                                     <td>{{$invoice->customer->name}}</td>
                                     <!-- <td>{{$invoice->created_at->format('d-m-Y')}}</td> -->
                                     <td class="d-flex" style="gap: 10px;">
                                         <a class="btn btn-primary btn-sm" href="{{route('invoice.show', $invoice->id)}}"><i class="fa fa-eye" ></i></a>
                                         <a class="btn btn-info btn-sm" href="{{route('invoice.edit', $invoice->id)}}"><i class="fa fa-edit" ></i></a>

                                         <button class="btn btn-danger btn-sm waves-effect" type="submit" onclick="deleteTag({{ $invoice->id }})">
                                             <i class="fa fa-trash"></i>
                                         </button>
                                         <form id="delete-form-{{ $invoice->id }}" action="{{ route('invoice.destroy',$invoice->id) }}" method="POST" style="display: none;">
                                             @csrf
                                             @method('DELETE')
                                         </form>
                                     </td>
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
    <script type="text/javascript">
        function deleteTag(id) {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: true,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('delete-form-'+id).submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                  swal({
                    title: 'Cancelled',
                    text: 'Your data is safe :)',
                    type: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'Ok',
                    confirmButtonClass: 'btn btn-success',
                    buttonsStyling: true,
                  });
                }
            })
        }

      $(document).ready(function () {
        $('#fetchDate').on('change', function () {
          let selectedDate = $(this).val();
          console.log(selectedDate);
          fetchInvoiceByDate();
        });

        function fetchInvoiceByDate() {
          let selectedDate = $('#fetchDate').val();
          if(selectedDate) {
            $.ajax({
              url: '{{ route("invoice.fetchInvoiceByDate",":date") }}'.replace(':date', selectedDate),
              type: 'POST',
              data: {
                customer_id: selectedDate,
                _token: '{{ csrf_token() }}' 
              },
              success: function(response) {
                try {
                  console.log("Success",response);  
                  $('#sampleTable tbody').empty();
                  if (response.length > 0) {
                    response.forEach(invoice => {
                        $('#sampleTable tbody').append(`
                          <tr>
                            <td>${1000 + invoice.id}</td>
                            <td>${invoice.customer.name}</td>
                            <td class="d-flex" style="gap: 10px;">
                              <a class="btn btn-primary btn-sm" href="/invoices/${invoice.id}"><i class="fa fa-eye"></i></a>
                              <a class="btn btn-info btn-sm" href="/invoices/${invoice.id}/edit"><i class="fa fa-edit"></i></a>
                              <button class="btn btn-danger btn-sm" type="button" onclick="confirmDelete(${invoice.id})">
                                <i class="fa fa-trash"></i>
                              </button>
                              <form id="delete-form-${invoice.id}" action="/invoices/${invoice.id}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                              </form>
                            </td>
                          </tr>
                      `);
                    });
                  } else {
                    $('#sampleTable tbody').append('<tr><td colspan="3" class="text-center">No invoices found for this date.</td></tr>');
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
