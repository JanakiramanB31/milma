<div class="tile-body">
  <form method="POST" action="{{$submitURL}}">
      @csrf
      @if($editPage)
      @method('PUT')
      @endif
      <div class="form-group col-md-12">
          <label class="control-label">Route Name</label>
          <input name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name', $route->name)}}" type="text" placeholder="Enter Route Name">
          @error('name')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>
      <div class="form-group col-md-12">
          <label class="control-label">Route Number</label>
          <input name="route_number" class="form-control @error('route_number') is-invalid @enderror" value="{{old('route_number', $route->route_number)}}" type="text" placeholder="Enter Route Number">
          @error('route_number')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>
     


      <div class="form-group col-md-4 align-self-end">
          <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Add Route Details</button>
      </div>
  </form>
</div>