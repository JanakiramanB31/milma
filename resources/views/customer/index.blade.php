@extends('layouts.master')

@section('title', 'Customer | ')
@section('content')
  @include('partials.header')
  @include('partials.sidebar')

  <main class="app-content">
      <!-- <div class="app-title">
          <div>
              <h1><i class="fa fa-th-list"></i> Manage Customers</h1>
          </div>
          <ul class="app-breadcrumb breadcrumb side">
              <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
              <li class="breadcrumb-item">Customer</li>
              <li class="breadcrumb-item active"><a href="#">Manage Customers</a></li>
          </ul>
      </div> -->
    <div class="">
      <a class="btn btn-primary" href="{{route('customer.create')}}"><i class="fa fa-plus"></i>Customer</a>
    </div>

    <div class="row mt-2">
      <div class="col-md-12">
        <div class="tile">
          <div class="tile-body table-responsive" >
            <table class="table table-hover table-bordered" id="sampleTable" style="width: 100%;overflow-x:auto;">
              <thead>
                <tr>
                  <th>S No.</th>
                  <th>Company Name </th>
                  <th>Contact Person </th>
                  <th>Contact Number </th>
                  <!-- <th>Contact</th> -->
                  <!-- <th>Details</th> -->
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php 
                  $serialNo = 1; 
                @endphp
                @foreach( $customers as $customer)
                <tr>
                  <td>{{$serialNo++}}.</td>
                  <td>{{ $customer->company_name }} </td>
                  <td>
                    @php
                        $name = $customer->contact_person ?? null;
                    @endphp
                    {{ !empty($name) && strlen($name) > 2
                        ? $name[0] . str_repeat('*', strlen($name) - 2) . $name[strlen($name) - 1]
                        : (!empty($name) ? $name : 'N/A')
                    }}
                  </td>
                  <td>    
                    {{ isset($customer->mobile) && strlen($customer->mobile) > 0 ? str_repeat('*', strlen($customer->mobile)) : 'N/A' }}
                  </td>
                  <!-- <td>{{ $customer->mobile }} </td> -->
                  <!-- <td>{{ $customer->details }} </td> -->
                 
                  <td class="d-flex" style="gap: 10px;">
                    <a class="btn btn-primary btn-sm" href="{{route('customer.edit', $customer->id)}}"><i class="fa fa-edit" ></i></a>
                    <button class="btn btn-danger btn-sm waves-effect" type="submit" onclick="deleteTag({{ $customer->id }})">
                      <i class="fa fa-trash"></i>
                    </button>
                    <form id="delete-form-{{ $customer->id }}" action="{{ route('customer.destroy',$customer->id) }}" method="POST" style="display: none;">
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
  <!-- <script type="text/javascript">$('#sampleTable').DataTable();</script> -->
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
            text: "Customer details has been deleted.",
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
        scrollX:true,
        dom: '<"top"f>rt<"bottom"l<"pagination"p><"clear">>',
        pageLength: 10,     
        lengthMenu: [10, 20, 50, 100],
        language: {
          lengthMenu: "Show _MENU_ rows"
        },  
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

    });
  </script>
@endpush
