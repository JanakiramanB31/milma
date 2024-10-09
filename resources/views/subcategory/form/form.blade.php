<div class="tile-body">
  <form method="POST" action="{{$submitURL}}">
      @csrf
      @if($editPage)
      @method('PUT')
      @endif
      <div class="form-group col-md-12">
          <label class="control-label">Category Name</label>
          <select name="parent_id" class="form-control categoryname @error('parent_id') is-invalid @enderror" >
              <option value="">Select Category</option>
              @foreach($categories as $category)
              <option name="parent_id"  value="{{$category->id}}"
              {{ old('parent_id', $subcategory->parent_id) == $category->id ? 'selected' : '' }} >{{$category->name}}</option>
              @endforeach
          </select>
          @error('parent_id')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>
      <div class="form-group col-md-12">
          <label class="control-label">Subcategory Name</label>
          <input name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name', $subcategory->name)}}" type="text" placeholder="Enter Subcategory Name">
          @error('name')
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>

      <div class="form-group col-md-4 align-self-end">
          <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-plus"></i>{{$editPage ? "Update" : "Add"}} Details</button>
      </div>
  </form>
</div>