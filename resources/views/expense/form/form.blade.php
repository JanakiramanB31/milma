<div class="tile-body">
  <form method="POST" action="{{$submitURL}}">
    @csrf
    @if($editPage)
    @method('PUT')
    @endif

    <!-- Customer Type -->
    <div class="form-group col-md-12">
      <label class="control-label">Expense Type</label>
      <select name="expense_type_id" id = 'expense_type_id' class="form-control @error('expense_type_id') is-invalid @enderror" >
        <option value = ''>Select Expense Type</option>
        @foreach($expenseTypes as $expenseType)
        <option value="{{ $expenseType['id'] }}" {{ old('expense_type_id', $expense->expense_type_id) == $expenseType['id'] ? 'selected' : '' }}>
          {{ $expenseType['name'] }}
        </option>
        @endforeach
        <option value="0" {{ old('expense_type_id', $expense->expense_type_id) == "Others" ? 'selected' : '' }}>
        Others
        </option>
      </select>
      @error('expense_type_id')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    @php 
      $selectedExpenseType = old('expense_type_id', $expense->expense_type_id);
    @endphp

    <div id="other_expense_type" class="form-group col-md-12" @if($selectedExpenseType != "0") hidden @endif>
      <input name="other_expense_details" class="form-control @error('other_expense_details') is-invalid @enderror" value="{{old('other_expense_details', $expense->other_expense_details)}}" type="text" placeholder="Enter the Expense Type">
      @error('other_expense_details')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    <!-- Expense Amount -->
    <div class="form-group col-md-12">
      <label class="control-label">Amount</label>
      <input id="expense_amt" name="expense_amt" class="form-control @error('expense_amt') is-invalid @enderror" value="{{ isset($editPage) ? old('expense_amt', number_format($expense->expense_amt, $decimalLength)) : old('expense_amt') }}" type="text" placeholder="Enter the Expense Amount">
      @error('expense_amt')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    @php
      $date = $expense->expense_date ? date('Y-m-d', strtotime($expense->expense_date)) : null;
    @endphp
    <!-- Expense Date -->
    <div class="form-group col-md-12">
      <label class="control-label">Date</label>
      <input name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{old('expense_date', $date ?? \Carbon\Carbon::now()->toDateString())}}" type="date" placeholder="Select the Expense Date">
      @error('expense_date')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    <!-- Description -->
    <div class="form-group col-md-12">
      <label class="control-label">Description</label>
      <textarea name="description" class="form-control @error('description') is-invalid @enderror" value="{{old('description', $expense->description)}}" placeholder="Enter the Description"></textarea>
      @error('description')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    <!--  Close Button and Submit Button  -->
    <div class="d-flex justify-content-center" style="gap: 10px;">
      <!-- Back to Index Page Button -->
      <div>
        <button id="customer-close-btn" type="button" class="btn btn-danger">Close</button>
      </div>

      <!-- Submiiting Form Data Button -->
      <div >
        <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>{{$editPage ? "Update" : "Add"}}</button>
      </div>
    </div>
  </form> 
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {

    $('#expense_type_id').on('change', function () {
      const selectedType = $(this).find('option:selected').val();
      console.log("selectedType", selectedType)
      if (selectedType == 0) {
        console.log("coming")
        $('#other_expense_type').attr("hidden", false);
      } else {
        $('#other_expense_type').attr("hidden", true);
      }
    });

    $('#customer-close-btn').on('click' , function () {
      window.location.href =  '{{ route("expense.index") }}'
    });

      //Accept Only Float Number Price In Quantity Field 
      $(document).on('input', '#expense_amt', function() {
        this.value = this.value.replace(/[^0-9.]/g, '');
        
        const parts = this.value.split('.');
        if (parts.length > 2) {
          this.value = parts[0] + '.' + parts.slice(1).join('');
        }
      });
  });
</script>