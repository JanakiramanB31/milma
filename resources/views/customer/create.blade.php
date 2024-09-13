@extends('layouts.master')

@section('title', 'Customer | ')
@section('content')
    @include('partials.header')
    @include('partials.sidebar')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i>Add Customer</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Customer</li>
                <li class="breadcrumb-item"><a href="#">Add Customer</a></li>
            </ul>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="">
            <a class="btn btn-primary" href="{{route('customer.index')}}"><i class="fa fa-edit"> </i>Manage Customers</a>
        </div>
        <div class="row mt-2">

            <div class="clearix"></div>
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Customer</h3>
                    @include('customer.form.form')
                    <!-- <div class="tile-body">
                        <form method="POST" action="{{route('customer.store')}}">
                            @csrf
                            <div class="form-group col-md-12">
                                <label class="control-label">Customer Name</label>
                                <input name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}" type="text" placeholder="Enter Customer's Name">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Contact</label>
                                <input name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{old('mobile')}}" type="text" placeholder="Enter Contact Number">
                                @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror"  placeholder="Enter Your Address">{{old('address')}}</textarea>
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}" placeholder="Enter Your Email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Details</label>
                                <textarea name="details" class="form-control @error('details') is-invalid @enderror" >{{old('details')}}</textarea>
                                @error('details')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label">Previous Credit Balance</label>
                                <input name="previous_balance" class="form-control @error('previous_balance') is-invalid @enderror" value="{{old('previous_balance')}}" type="text" placeholder="Example: 111">
                                @error('previous_balance')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                 
                            <div class="form-group col-md-12">
                                <label class="control-label">Company Name</label>
                                <input name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}" type="text" placeholder="Enter Your Company Name">
                                @error('company_name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label">Contact Person</label>
                                <input name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{old('contact_person')}}" type="text" placeholder="Enter Other Contact Person">
                                @error('contact_person')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label">Postcode</label>
                                <input name="post_code" class="form-control @error('post_code') is-invalid @enderror" value="{{old('post_code') }}" type="text" placeholder="Enter Your Post Code">
                                @error('post_code')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label">Mobile No.</label>
                                <input name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" value="{{old('mobile_number') }}" type="text" placeholder="Enter Your Mobile Number">
                                @error('mobile_number')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label">Customer Type</label>
                                <select name="cutomer_type_parent_id" id = 'cutomer_type_parent_id' class="form-control @error('cutomer_type_parent_id') is-invalid @enderror" >
                                    <option value = ''>Select Customer Type</option>
                                    @foreach($customerTypes as $customerType)
                                    <option name="cutomer_type_parent_id" value="{{$customerType['id']}}">{{$customerType['name']}}</option>
                                    @endforeach
                                </select>
                                @error('cutomer_type_parent_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label">Sale Type</label>
                                <select name="sale_type_parent_id" id='sale_type_parent_id' class="form-control @error('sale_type_parent_id') is-invalid @enderror" >
                                    <option value=''>Select Sale Type</option>
                                    @foreach($saleTypes as $saleType)
                                    <option name="sale_type_parent_id" value="{{$saleType['id']}}">{{$saleType['name']}}</option>
                                    @endforeach
                                </select>
                                @error('sale_type_parent_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                    <label class="control-label">Rate</label>
                                    <select name="rate_id" class="form-control @error('rate_id') is-invalid @enderror">
                                        <option value =''>---Select rate---</option>
                                        @foreach($rates as $rate)
                                            <option value="{{$rate->id}}">{{$rate->type}}</option>
                                        @endforeach
                                    </select>
                                    @error('rate_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            <div class="form-group col-md-12">
                                <label class="radio control-label">Status</label>
                                    <div class="controls">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="status1" name="status" class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="status1" >Active</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="status2" name="status" class="custom-control-input" value="0">
                                            <label class="custom-control-label" for="status2">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                     


                            <div class="form-group col-md-4 align-self-end">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Add Customer Details</button>
                            </div>
                        </form>
                    </div> -->
                </div>
            </div>
        </div>
    </main>
@endsection



