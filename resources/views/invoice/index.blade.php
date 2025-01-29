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
    <a class="btn btn-primary" href="{{route('invoice.create')}}"><i class="fa fa-plus"></i>Receipt</a>
  </div>

  <div class="row mt-2">
    <div class="col-md-12">
      <div class="tile">
        <div class="mb-2">
          <label for="fetchDate">Search By Date :</label>
          <input id="fetchDate" name="fetchDate" type="date" class="form-control" value="{{ date('Y-m-d') }}"/>
        </div>
        <div class="tile-body table-responsive">
          <table class="table table-hover table-bordered" id="sampleTable">
            <thead>
              <tr>
                <th>Receipt ID </th>
                <th>Company Name </th>
                <th>Date </th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($invoices as $invoice)
              <tr>
                <td>{{1000+$invoice->id}}</td>
                <td>{{$invoice->customer->company_name}}</td>
                <td>{{$invoice->created_at->format('d-m-Y')}}</td>
                <td class="d-flex" style="gap: 10px;">
                  <a class="btn btn-info btn-sm" href="{{route('invoice.show', $invoice->id)}}"><i class="fa fa-eye" ></i></a>
                  <a class="btn btn-primary btn-sm" href="{{route('invoice.edit', $invoice->id)}}"><i class="fa fa-edit" ></i></a>
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
  <!--  <script type="text/javascript">$('#sampleTable').DataTable();</script> -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript">
    function deleteTag(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success mx-2",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });
      swalWithBootstrapButtons.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545',
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          event.preventDefault();
          document.getElementById('delete-form-'+id).submit();
          swalWithBootstrapButtons.fire({
            title: "Deleted!",
            text: "Your Invoice has been deleted.",
            icon: "success"
          });
        } else if (result.dismiss === swal.DismissReason.cancel) {
          swalWithBootstrapButtons.fire({
            title: 'Cancelled',
            text: 'Your data is safe :)',
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Ok',
            buttonsStyling: true,
          });
        }
      });
    }

    $(document).ready(function () {

      var table = $('#sampleTable').DataTable({
        dom: '<"top"f>rt<"bottom"l<"pagination"p><"clear">>',
        language: {
          emptyTable: "No invoices found for this date.",
          lengthMenu: "Show _MENU_ rows"
        },
        pageLength: 10,     
        lengthMenu: [10, 20, 50, 100],
      });

      //Pagination Select Styles
      $('.dataTables_length').find('select').css({
        'width': '50px',
        'padding':'0',
        'font-size': '12px'
      });

      //Bottom Section Margin
      $('.bottom').css('margin-top','10px');

      // Make the label a block element
      $('.dataTables_filter label').css({
        'display': 'block',  
      });

      // Make the input take 100% width
      $('.dataTables_filter input').css({
        'width': '100%' ,
        'margin':'0',
        'display': 'block',   
        'margin-bottom': '10px'   
      });

      //Disabled the Count of Entries
      $('#sampleTable_info').css('display','none');

      // Align the search box to the right
      $('.dataTables_filter').css({
          'text-align': 'right'
      });

      // Align entries dropdown to the left and pagination buttons to the right
      $('.dataTables_length').css({
        'float': 'left',
        'margin-top': '5px'
      });

      $('.pagination').css({
        'float': 'right'
      });

      // Clear float on wrapper
      $('.dataTables_wrapper').css({
        'overflow': 'hidden'
      });

      $('.dataTables_scrollHeadInner').css('width','100%');

      function toggleScroll() {
        if ($(window).width() <= 768) {
          $('.dataTables_wrapper').css('overflow-x', 'auto');
        } else {
          $('.dataTables_wrapper').css('overflow-x', 'hidden');
        }
      }

      // Initial check
      toggleScroll();

      // Check on resize
      $(window).on('resize', function() {
          toggleScroll();
      });

      $('#fetchDate').on('change', function () {
        let selectedDate = $(this).val();
        console.log(selectedDate);
        fetchInvoiceByDate();
      });

      function fetchInvoiceByDate() {
        let selectedDate = $('#fetchDate').val();
        console.log(selectedDate);
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
                          <td>${invoice.customer.company_name}</td>
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
