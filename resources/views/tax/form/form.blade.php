<div class="tile-body">
  <form method="POST" action="{{$submitURL}}">
      @csrf
      @if($editPage)
      @method('PUT')
      @endif
      <div class="form-group col-md-12">
          <label class="control-label">Tax Amount</label>
          <input name="name" class="form-control @error('name') is-invalid @enderror" value = "{{old('name', $tax->name)}}" type="text" placeholder="Enter Tax Amount">
          @error('name')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>

      <div class="form-group col-md-4 align-self-end">
          <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Add Now</button>
      </div>
  </form>
</div>