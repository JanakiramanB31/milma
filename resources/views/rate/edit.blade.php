@extends('layouts.master')

@section('title', 'Rate | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Edit Rate</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Rate</li>
                <li class="breadcrumb-item"><a href="#">Edit Rate</a></li>
            </ul>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="row">
            <div class="clearix"></div>
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Edit Rate Form</h3>
                    <div class="tile-body">
                        <form class="row" method="POST" action="{{route('rate.update', $rate->id)}}">
                            @csrf
                            @method('PUT')
                            <div class="form-group col-md-12">
                                <label class="control-label">Name</label>
                                <input value="{{ $rate->name }}" name="name" class="form-control @error('name') is-invalid @enderror" type="text" placeholder="Enter Unit Name">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ $rate->description }}</textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label">Type</label>
                                <input value="{{ $rate->type }}" name="type" class="form-control @error('type') is-invalid @enderror" type="text" placeholder="Enter Unit Name">
                                @error('type')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4 align-self-end">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Rate</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection



