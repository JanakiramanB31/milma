@extends('layouts.master')

@section('title', 'Rate | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i>Rate</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Rate</li>
                <li class="breadcrumb-item"><a href="#">Add Rate</a></li>
            </ul>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="">
            <a class="btn btn-primary" href="{{route('rate.index')}}"><i class="fa fa-edit"></i> Manage Rates</a>
        </div>
        <div class="row mt-2">

            <div class="clearix"></div>
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Rate</h3>
                    <div class="tile-body">
                        <form method="POST" action="{{route('rate.store')}}">
                            @csrf
                            <div class="form-group col-md-12">
                                <label class="control-label">Name</label>
                                <input name="name" class="form-control @error('name') is-invalid @enderror" value = "{{old('name')}}" type="text" placeholder="Enter Name of Rate">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror"  placeholder="Rate Description">{{old('description')}}</textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Type</label>
                                <input name="type" class="form-control @error('type') is-invalid @enderror" value = "{{old('type')}}" type="text" placeholder="Enter Rate Type">
                                @error('type')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="form-group col-md-4 align-self-end">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Add Rate Details</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection



