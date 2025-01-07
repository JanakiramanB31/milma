<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Product;
use App\Route;
use App\Sale;
use App\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function x_index($date = null)
    {
      if ($date) {
        $selectedDate = \Carbon\Carbon::parse($date)->format('Y-m-d');
      } else {
        $selectedDate = now()->toDateString();
      }
     
      $routes = Route::all();
      $saleTypesandTotalAmounts = Sale::select('type', 'qty', 'total_amount')->whereDate('created_at', $selectedDate)->get();
      //  echo '<pre>'; print_r($saleTypesandTotalAmounts); echo '</pre>';exit;

      $saleProductsTypesandAmounts = $saleTypesandTotalAmounts->groupBy('type')->map(function ($group) {
        return [
          'total_amt' => $group->sum('total_amount'),
          'qty_count'  => $group->count(),
        ];
      });

      $saleType = $saleProductsTypesandAmounts->get('sales', ['total_amt' => 0, 'qty_count' => 0]);
      $returnType = $saleProductsTypesandAmounts->get('returns', ['total_amt' => 0, 'qty_count' => 0]);


      $paymentTypesandTotalAmounts = Invoice::select('payment_type', 'received_amt','balance_amt')->whereDate('created_at', $selectedDate)->get();

      $saleProductspaymentTypesandAmounts = $paymentTypesandTotalAmounts->groupBy('payment_type')->map(function ($group) {
        return [
          'total_received_amt' => $group->sum('received_amt'),
          'transaction_count'  => $group->count(),
        ];
      });

      $creditPayments = $paymentTypesandTotalAmounts->filter(function ($item) {
        return $item->balance_amt > 0;  
      });

      
      
      $totalCreditAmount = $creditPayments->sum('balance_amt'); 
      $creditTransactionCount = $creditPayments->count();

      $cashPayments = $saleProductspaymentTypesandAmounts->get('Cash', ['total_received_amt' => 0, 'transaction_count' => 0]);
      $bankPayments = $saleProductspaymentTypesandAmounts->get('Bank Transfer', ['total_received_amt' => 0, 'transaction_count' => 0]);

      // echo '<pre>'; print_r($totalAmt); echo '</pre>';exit;
     
      return view('report.x_index', compact('cashPayments', 'bankPayments', 'saleType', 'returnType','routes','totalCreditAmount', 'creditTransactionCount'));
    }

    public function fetchByDate(Request $request, $date){
      $data = $request->input('date'); 
      $fromDate = $data['fromDate'];
      $toDate = $data['toDate'];
      if ($fromDate && $toDate) {
        $fromDate = \Carbon\Carbon::parse($fromDate)->format('Y-m-d');
        $toDate = \Carbon\Carbon::parse($toDate)->format('Y-m-d');
      } else {
          $fromDate = now()->toDateString();
          $toDate = now()->toDateString();
      }
      

      $saleTypesandTotalAmounts = Sale::select('type', 'qty', 'total_amount')->whereBetween('created_at', [$fromDate, $toDate])->get();
      //  echo '<pre>'; print_r($saleTypesandTotalAmounts); echo '</pre>';exit;

      $saleProductsTypesandAmounts = $saleTypesandTotalAmounts->groupBy('type')->map(function ($group) {
        return [
          'total_amt' => $group->sum('total_amount'),
          'qty_count'  => $group->count(),
        ];
      });

      $saleType = $saleProductsTypesandAmounts->get('sales', ['total_amt' => 0, 'qty_count' => 0]);
      $returnType = $saleProductsTypesandAmounts->get('returns', ['total_amt' => 0, 'qty_count' => 0]);


      $paymentTypesandTotalAmounts = Invoice::select('payment_type', 'received_amt','balance_amt')->whereBetween('created_at', [$fromDate, $toDate])->get();

      $saleProductspaymentTypesandAmounts = $paymentTypesandTotalAmounts->groupBy('payment_type')->map(function ($group) {
        return [
          'total_received_amt' => $group->sum('received_amt'),
          'transaction_count'  => $group->count(),
        ];
      });

      $creditPayments = $paymentTypesandTotalAmounts->filter(function ($item) {
        return $item->balance_amt > 0;  
      });
      
      $totalCreditAmount = $creditPayments->sum('balance_amt'); 
      $creditTransactionCount = $creditPayments->count();

      $cashPayments = $saleProductspaymentTypesandAmounts->get('Cash', ['total_received_amt' => 0, 'transaction_count' => 0]);
      $bankPayments = $saleProductspaymentTypesandAmounts->get('Bank Transfer', ['total_received_amt' => 0, 'transaction_count' => 0]);

      $filteredData = [
        "saleType" => $saleType,
        "returnType" => $returnType,
        "cashPayments" => $cashPayments,
        'totalCreditAmount' => $totalCreditAmount, 
        'creditTransactionCount' => $creditTransactionCount, 
        "bankPayments" => $bankPayments,
    ];

      return response()->json( $filteredData );
    }

    public function x_report_print(Request $request) {
      $data = $request->input('data'); 
      print_r($data);
      return view('report.x_report_print', $data);
    }

    public function y_index()
    {
      $today = now()->toDateString();
      $cash_invoices = Invoice::where('payment_type', 'Cash')->whereDate('created_at', $today)->count();
      $bank_invoices = Invoice::where('payment_type', 'Bank Transfer')->whereDate('created_at', $today)->count();
      $card_invoices = Invoice::where('payment_type', 'Credit Card')->whereDate('created_at', $today)->count();
      
      $saleProducts = Sale::select('product_id', 'qty')->get();

      $saleProductCounts = $saleProducts->groupBy('product_id')->map(function ($group) {
          return $group->sum('qty');
      });

      $totalQuantity = $saleProducts->sum('qty');

      $saleProductsUnique = $saleProducts->unique('product_id');

      $saleProductsWithNames = $saleProductsUnique->map(function($sale) use ($saleProductCounts) {
          $product = Product::find($sale->product_id);
          $sale->product_name = $product ? $product->name : 'Product not found';

          $sale->sales_count = $saleProductCounts->get($sale->product_id, 0);
          
          return $sale;
      });

      $saleProductsIDsNames = $saleProductsWithNames->map(function ($sale) {
          return [
              'product_id'   => $sale->product_id,
              'product_name' => $sale->product_name,
              'sales_count'  => $sale->sales_count,
          ];
      });

      // echo '<pre>'; print_r($saleProductsIDsNames); echo '</pre>';exit;
     
      return view('report.y_index', compact('cash_invoices', 'bank_invoices', 'card_invoices', 'saleProductsIDsNames','totalQuantity'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
