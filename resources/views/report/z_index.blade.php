@extends('layouts.master')

@section('titel', 'Home | ')
@section('content')
  @include('partials.header')
  @include('partials.sidebar')
    <main class="app-content">

      <div class="app-title">
        <div>
          <h1><i class="fa fa-file-text"></i> Reports</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Reports</a></li>
        </ul>
      </div>

      <div class="row">
        <div class="col-md-6 col-lg-6">
          <div class="widget-small primary coloured-icon">
            <i class="icon fa fa-cube fa-3x"></i>
            <div class="info">
              <a style="text-decoration: none; color: black;" class="text-decoration-none"  data-toggle="collapse" href="#multiCollapseExample2"  aria-expanded="false" aria-controls="multiCollapseExample2"> <h4>Total Products</h4></a>
              <p><b>{{$totalQuantity}}</b></p>
            </div>
          </div>
          <div class="collapse multi-collapse" id="multiCollapseExample2">
            <div class="card card-body">
              @foreach ($saleProductsIDsNames as $saleProductsIDsName)
                <div class="widget-small info coloured-icon">
                  <i class="icon fa fa-shopping-cart fa-3x"></i>
                  <div class="info">
                    <h4>{{$saleProductsIDsName['product_name']}}</h4>
                    <p><b>{{$saleProductsIDsName['sales_count']}}</b></p>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-6">
          <div class="widget-small info coloured-icon">
            <i class="icon fa fa-shopping-cart fa-3x"></i>
            <div class="info">
              <a style="text-decoration: none; color: black;" class="text-decoration-none"  data-toggle="collapse" href="#multiCollapseExample1"  aria-expanded="false" aria-controls="multiCollapseExample1"> <h4>Total Sales</h4></a>
              <p><b>{{$cash_invoices + $bank_invoices + $card_invoices}}</b></p>
            </div>
          </div>
          <div class="collapse multi-collapse" id="multiCollapseExample1">
            <div class="card card-body">
              <div class="widget-small info coloured-icon">
                <i class="icon fa fa-shopping-cart fa-3x"></i>
                <div class="info">
                  <h4>Cash Sales</h4>
                  <p><b>{{$cash_invoices}}</b></p>
                </div>
              </div>

              <div class="widget-small info coloured-icon">
                <i class="icon fa fa-shopping-cart fa-3x"></i>
                <div class="info">
                  <h4>Bank Transfer Sales</h4>
                  <p><b>{{$bank_invoices}}</b></p>
                </div>
              </div>

              <div class="widget-small info coloured-icon">
                <i class="icon fa fa-shopping-cart fa-3x"></i>
                <div class="info">
                  <h4>Credit Sales</h4>
                  <p><b>{{$card_invoices}}</b></p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-6">
          <div class="widget-small warning coloured-icon">
            <i class="icon fa fa-truck fa-3x"></i>
            <div class="info">
              <h4>Suppliers</h4>
              <p><b></b></p>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-6">
          <div class="widget-small danger coloured-icon">
            <i class="icon fa fa-file fa-3x"></i>
            <div class="info">
              <h4> Invoices</h4>
              <p><b></b></p>
            </div>
          </div>
        </div>
      </div>

  </main>
@endsection

@push('js')
<script type="text/javascript">
  function toggleDropdown() {
    var dropdown = document.getElementById('salesDropdown');
    if (dropdown.style.display === 'none') {
      dropdown.style.display = 'block';
    } else {
      dropdown.style.display = 'none';
    }
  }
</script>
@endpush