@extends('layouts.master')

@section('title', 'Stock in Transit | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i>Add Stock in Transit Entry</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Stock in Transit</li>
                <li class="breadcrumb-item"><a href="#">Add Stock in Transit Entry</a></li>
            </ul>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="">
            <a class="btn btn-primary" href="{{route('stockintransit.index')}}"><i class="fa fa-edit"> </i>Manage Stock in Transit</a>
        </div>
        <div class="row mt-2">

            <div class="clearix"></div>
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Stock in Transit Entry</h3>
                    @include('stockintransit.form.form')
                </div>
            </div>
        </div>
    </main>
@endsection



