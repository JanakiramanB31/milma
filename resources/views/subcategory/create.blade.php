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
                </div>
            </div>
        </div>
    </main>
@endsection



