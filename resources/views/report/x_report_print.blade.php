@php
    $paperWidth = "320px";
@endphp
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row" id="xReport">
    <style>
      .currency {
        margin-right: 5px;
        float: right;
      }
    </style>
    <div class="col-lg-12" style="margin: 10px 10px 10px 20px; width: {{ $paperWidth }}; text-align:center;">
      <div class="ibox float-e-margins">
        <div class="ibox-content">
          <div class="hr-line-dashed"></div>
          <div class="pos-report">
            <h3 style="text-align: center;"><span style="text-align: center;">MILMA FOODS UK LIMITED</span></h3>
            <h4><span style="text-align: center;">X Report</span></h4>
            <h5 style="font-size: 14px;">Taken: {{ \Carbon\Carbon::now()->format('d-m-Y h:i a') }} </h5>
            <hr style="margin: 10px 20px; width: {{ $paperWidth }};"/>
            <div class="d-flex align-items-space-between justify-content-space-between">
              <b style="font-size: 14px;">From: {{ \Carbon\Carbon::parse($salesData['selectedStartDate'])->format('d-m-Y') }}</b>
              <b style="font-size: 14px;">To: {{ \Carbon\Carbon::parse($salesData['selectedEndDate'])->format('d-m-Y') }}</b>
            </div>
            <div style="margin: 10px 20px; width: {{ $paperWidth }}; text-align: left;">
              <hr/>
            </div>
            <div class="hr-line-dashed"></div>
            <table class="table" style="margin: 10px 10px 10px 20px; width: {{ $paperWidth }}; text-align: left;">
              <tr>
                <th>No. of Cash Sales: </th>
                <td class="currency">{{ $salesData['cashSalesCount'] }}</td>
              </tr>
              <tr>
                <th>No. of Bank Transfer Sales:</th>
                <td class="currency">{{ $salesData['bankSalesCount'] }}</td>
              </tr>
              <tr>
                <th>No. of Credit Sales:</th>
                <td class="currency">{{ $salesData['cardSalesCount'] ? $salesData['cardSalesCount']  : "0" }}</td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>

              <tr>
                <th>Total Sales: </th>
                <td class="currency">{{ $salesData['salesCount'] }}</td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>

              <tr>
                <th>Amount of Cash Sales:</th>
                <td class="currency">{{ $currency }} {{ $salesData['cashSalesAmount'] }}</td>
              </tr>
              <tr>
                <th>Amount of Bank Transfer Sales: </th>
                <td class="currency">{{ $currency }} {{ $salesData['bankSalesAmount'] }}</td>
              </tr>
              <tr>
                <th>Amount of Credit Sales:</th>
                <td class="currency">{{ $currency }} {{ $salesData['cardSalesAmount'] }}</td>
              </tr>

              <tr>
                <td colspan="2"><hr/></td>
              </tr>
             
              <tr>
                <th>Total Amount of Sales:</th>
                <td class="currency">{{ $currency }} {{ $salesData['totalAmount'] + $salesData['cardSalesAmount'] }}</td>
              </tr>
              <tr>
                <th>Amount of Credit Sales:</th>
                <td class="currency">(-) {{ $currency }} {{ $salesData['cardSalesAmount'] }}</td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th>Total Amount: </th>
                <td class="currency">
                {{ $currency }} {{ $salesData['totalAmount'] }}
                </td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th>Total Return Orders: </th>
                <td class="currency">{{ $salesData['returnCount'] }}</td>
              </tr>
              <tr>
                <th>Total Amount of Return Orders: </th>
                <td class="currency">(-) {{ $currency }} {{ $salesData['returnAmount'] }}</td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th>Total Net Amount: </th>
                <td class="currency">
                {{ $currency }} {{ $salesData['totalAmount'] }}
                </td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="hidden-print" style="margin-left:10px; text-align: center; width: {{ $paperWidth }};">
  <button class="btn btn-primary printbutton" onClick="printDiv('xReport')">Print</button>
  <a class="btn btn-primary nextbutton"><i class="fa fa-plus"></i> Close</a>
</div>
