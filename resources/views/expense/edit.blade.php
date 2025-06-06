@extends('layouts.master')

@section('title', 'Expense | ')
@section('content')
@include('partials.header')
@include('partials.sidebar')
  <main class="app-content">
    <!-- <div class="app-title">
      <div>
        <h1><i class="fa fa-edit"></i> Edit Customer</h1>
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item">Customer</li>
        <li class="breadcrumb-item"><a href="#">Edit Customer</a></li>
      </ul>
    </div> -->

    @if(session()->has('message'))
      <div class="alert alert-success">
        {{ session()->get('message') }}
      </div>
    @endif

    <div class="row">
      <div class="clearix"></div>
      <div class="col-md-12">
        <div class="tile">
          <h3 class="tile-title">Edit Expense</h3>
          @include('expense.form.form')
        </div>
      </div>
    </div>
  </main>
@endsection



