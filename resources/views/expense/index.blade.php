@extends('layouts.master')

@section('title', 'Expense | ')
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
      <a class="btn btn-primary" href="{{route('expense.create')}}"><i class="fa fa-plus"></i>Expense</a>
    </div>

    <div class="row mt-2">
      <div class="col-md-12">
        <div class="tile">
          <div class="tile-body table-responsive" >
            <table class="table table-hover table-bordered" id="sampleTable" style="width: 100%;overflow-x:auto;">
              <thead>
                <tr>
                  <th>S No.</th>
                  <th>Expense Type </th>
                  <th>Amt </th>
                  <th>Date </th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php 
                  $serialNo = 1; 
                @endphp
                @foreach( $expenses as $expense)
                <tr>
                  <td>{{$serialNo++}}.</td>
                  @php
                    $typeName = '';
                    if ($expense->expense_type_id == 0) {
                      $typeName = $expense->other_expense_details;
                    } else {
                      foreach ($expenseTypes as $type) {
                        if ($type['id'] === $expense->expense_type_id) {
                          $typeName = $type['name'];
                          break;
                        }
                      }
                    }
                  @endphp
                  <td>{{ $typeName }} </td>
                  <td><span>{{$currency}} </span>{{ number_format($expense->expense_amt,  $decimalLength) }} </td>
                  <td>{{ date('d-m-Y', strtotime($expense->expense_date)) }} </td>
                 
                  <td class="d-flex" style="gap: 10px;">
                    <a class="btn btn-primary btn-sm" href="{{route('expense.edit', $expense->id)}}"><i class="fa fa-edit" ></i></a>
                   
                    @if(Auth::user()->role == 'admin' || $expense->created_at->isToday())
                    <button class="btn btn-danger btn-sm waves-effect" type="submit" onclick="deleteTag({{ $expense->id }})">
                      <i class="fa fa-trash"></i>
                    </button>
                    <form id="delete-form-{{ $expense->id }}" action="{{ route('expense.destroy',$expense->id) }}" method="POST" style="display: none;">
                      @csrf
                      @method('DELETE')
                    </form>
                    @endif
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
