@extends('layouts.master')

@section('title', 'Profile | ')
@section('content')
  @include('partials.header')
  @include('partials.sidebar')

    <main class="app-content">
      <div class="app-title">
          <div>
              <h1><i class="fa fa-edit"></i> Add Profile </h1>
          </div>
          <ul class="app-breadcrumb breadcrumb">
              <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
              <li class="breadcrumb-item">Forms</li>
              <li class="breadcrumb-item"><a href="#"> Add Profile  </a></li>
          </ul>
      </div>
      @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger">
          {{ session('error') }}
        </div>
      @endif
      <div  class="col-md-6 offset-md-3">
        <div class="tile">
          <div class="col-lg-12">
                
            <form action="{{route('store_profile')}}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label for="Inputfname">First Name</label>
                <input name="f_name" class="form-control @error('f_name') is-invalid @enderror" id="Inputfname" type="text" aria-describedby="emailHelp" placeholder="Enter email"><small class="form-text text-muted" id="emailHelp"></small>
                @error('f_name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            
              <div class="form-group">
                <label for="Inputlname">Last Name </label>
                <input name="l_name" class="form-control @error('l_name') is-invalid @enderror" id="Inputlname" type="text" aria-describedby="emailHelp" placeholder="Enter email"><small class="form-text text-muted" id="emailHelp"></small>
                @error('l_name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="InputEmail1">Email address</label>
                <input name="email" class="form-control @error('email') is-invalid @enderror" id="InputEmail1" type="email" aria-describedby="emailHelp" placeholder="Enter email"><small class="form-text text-muted" id="emailHelp"></small>
                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="form-group">
                <label >User Role </label>
                <select name="user_role" id = 'role' class="form-control @error('user_role') is-invalid @enderror" >
                  <option value = ''>Select the Role</option>
                  @foreach($userRoles as $userRole)
                  <option value="{{ $userRole['id'] }}">
                    {{ $userRole['name'] }}
                  </option>
                  @endforeach
                </select>
                @error('user_role')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>

              <hr>
              <h4>Password</h4>
              <div class="form-group">
                <label for="InputNewPassword">Password</label>
                <input name="password" class="form-control @error('password') is-invalid @enderror" id="InputPassword" type="password" placeholder="Enter password">
                @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="InputConfirmPassword">Confirm Password</label>
                <input name="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror" id="InputConfirmPassword" type="password" placeholder="Confirm password">
                @error('confirm_password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>

              <div class="form-group">
                <label  >Profile Picture</label>
                <input class="form-control @error('image') is-invalid @enderror" name="image"   type="file" >
                @error('image')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <button class="btn btn-primary" type="submit">Add</button>
            </form>
          </div>
          <div class="tile-footer"></div>
        </div>
      </div>
    </main>

 @endsection