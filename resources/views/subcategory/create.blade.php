@extends('layouts.master')

@section('title', 'Subcategory | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Add SubCategories</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Subcategory</li>
                <li class="breadcrumb-item"><a href="#">Create SubCategories</a></li>
            </ul>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="">
            <a class="btn btn-primary" href="{{route('subcategory.index')}}"><i class="fa fa-cogs"></i> Manage SubCategories</a>
        </div>
        <div class="row mt-2">

            <div class="clearix"></div>
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Subcategory</h3>
                    @include('subcategory.form.form')
                    <!-- <div class="tile-body">
                        <form method="POST" action="{{route('subcategory.store')}}">
                            @csrf
                            <div class="form-group col-md-12">
                                <label class="control-label">Category Name</label>
                                <select name="parent_id" class="form-control categoryname" >
                                    <option>Select Category</option>
                                    @foreach($categories as $category)
                                    <option name="parent_id" value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Subcategory Name</label>
                                <input name="name" class="form-control @error('name') is-invalid @enderror" type="text" placeholder="Enter Subcategory Name">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4 align-self-end">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-plus"></i>Create</button>
                            </div>
                        </form>
                    </div> -->
                </div>
            </div>
        </div>
    </main>
@endsection



