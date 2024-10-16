<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Product;
use App\Sale;
use App\Sales;
use App\Supplier;
use App\Invoice;
use App\ProductPrice;
use App\Returns;
use App\StockInTransit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
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


    public function index()
    {
      $invoices = Invoice::all();
      return view('invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $userID = Auth::id();
      $userRole = Auth::user()->role;
      $today = now()->toDateString();
      $routeEmptyError = null;
      $returnProducts = array();
      $customers = Customer::where('status',1)->get();
      if ($userRole == 'admin') {
        $products = Product::where('status',1)->get();
      } else {
        $routeData = StockInTransit::select('route_id', 'vehicle_id')->where('user_id',$userID)->whereDate('created_at', $today)->first();

        if ($routeData) {
          $products = Product::where('status',1)->whereIn('id', function ($query) use( $userID, $routeData) {
            $query->select('product_id')->where('user_id',$userID)->where('route_id',$routeData->route_id)->where('vehicle_id',$routeData->vehicle_id)->from('stock_in_transits');
          })->get();         
        } else {
          $products =array();
          $returnProducts = array();
          $routeEmptyError = "Route Number or Vehicle Number Not Found";
          return view('invoice.createforphone', compact('customers','returnProducts','routeEmptyError','products'));
        }
      }
      return view('invoice.createforphone', compact('customers','returnProducts','products','routeEmptyError'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProducts(Request $request, $id) {
      $userID = Auth::id();
      $cusID = $id;
      $balAmt = Invoice::select('balance_amt')->where('customer_id', $cusID)->orderBy('created_at', 'desc')->first() ?? 0;
      $returnProducts = Product::select('id','name')->where('status',1)->whereIn('id', function($query) use ($cusID) {
        $query->select('product_id')->from('sales')->whereIn('invoice_id', function($subQuery) use ($cusID) {
          $subQuery->select('id')->from('invoices') ->where('customer_id', $cusID);
        });
      })->get();
      $productPricesAndIDs = ProductPrice::select('product_id','price')->whereIn('rate_id', function($query) use($cusID) {
        $query->select('rate_id')->from('customers')->where('id', $cusID);
      })->get();
      
      $prodIDsAndPrices= $productPricesAndIDs->pluck('price', 'product_id')->toArray();
      $productIDs = $returnProducts->pluck('id')->toArray();
      $invoiceIDs = Invoice::where('customer_id', $id)->pluck('id')->toArray();
      $quantityAndPrices = Sales::select('product_id','qty','price')->whereIn('product_id',$productIDs)->whereIn('invoice_id',$invoiceIDs)->get()->toArray();

      return response()->json(['returnProducts' => $returnProducts,'quantityAndPrices' => $quantityAndPrices, 'productIdsAndPrices' => $prodIDsAndPrices,'balance_amount' => $balAmt]);
      
    }
    public function store(Request $request)
    {
      $request->validate([
        'customer_id' => 'required|integer',
        'product_id' => 'required',
        'qty' => 'required',
        'price' => 'required',
        'amount' => 'required',
      ]);
      
      $userId = Auth::id();
      $cusID = $request->customer_id;
      
      $oldInvoice = Invoice::where('customer_id', $cusID)->orderBy('created_at', 'desc')->first();
      if ($oldInvoice) {
        $oldInvoice->balance_amt = 0;
        $oldInvoice->save();
      }
      $invoice = new Invoice();
      $invoice->customer_id = $request->customer_id;
      $invoice->user_id = $userId;
      $invoice->received_amt = $request->received_amt;
      $invoice->prev_balance_amt = $request->prev_balance_amt;
      $invoice->balance_amt = (($request->total) + ($request->prev_balance_amt))- ($request->received_amt);
      $invoice->total_amount = $request->total;
      $invoice->save();

      foreach ( $request->product_id as $key => $product_id){
        if ($product_id && isset($request->qty[$key]) && $request->qty[$key]) {
          $sale = new Sale();
          $sale->type = $request->type[$key];
          $sale->reason = $request->reason[$key];
          $sale->user_id = $userId;
          $sale->qty = $request->qty[$key];
          $sale->price = $request->price[$key];
          $sale->total_amount = $request->total;
          $sale->product_id = $request->product_id[$key];
          $sale->invoice_id = $invoice->id;
          $sale->save();
        }
      }

      return redirect('invoice/'.$invoice->id)->with('message','Invoice created Successfully');

    }

    public function storeReturns(Request $request)
    {
      foreach ( $request->product_id as $key => $product_id){
        $return = new Returns();
        $return->product_id = $request->return_product_id[$key];
        $return->quantity = $request->return_qty[$key];
        $return->price = $request->return_amount[$key];
        $return->save();
      }
    }

    public function findPrice(Request $request, $id){
      $productPricesAndIDs = ProductPrice::select('product_id','price')->whereIn('rate_id', function($query) use($id) {
        $query->select('rate_id')->from('customers')->where('id', $id);
      })->get();
      $prodIDsAndPrices= $productPricesAndIDs->pluck('price', 'product_id')->toArray();
      return response()->json($prodIDsAndPrices);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $invoice = Invoice::findOrFail($id);
      $sales = Sale::where('invoice_id', $id)->get();
      $amount = Invoice::select('total_amount','received_amt','prev_balance_amt','balance_amt')->where('id', $id)->first();
      return view('invoice.show', compact('invoice','sales','amount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $customers = Customer::where('status',1)->get();
      $products = Product::orderBy('id', 'DESC')->get();
      $invoice = Invoice::findOrFail($id);
      $sales = Sale::where('invoice_id', $id)->get();
      return view('invoice.edit', compact('customers','products','invoice','sales'));
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
      $request->validate([
          'customer_id' => 'required',
          'product_id' => 'required',
          'qty' => 'required',
          'price' => 'required',
          'dis' => 'required',
          'amount' => 'required',
      ]);

      $invoice = Invoice::findOrFail($id);
      $invoice->customer_id = $request->customer_id;
      $invoice->total = 1000;
      $invoice->save();

      Sale::where('invoice_id', $id)->delete();

      foreach ( $request->product_id as $key => $product_id){
        $sale = new Sale();
        $sale->qty = $request->qty[$key];
        $sale->price = $request->price[$key];
        $sale->dis = $request->dis[$key];
        $sale->amount = $request->amount[$key];
        $sale->product_id = $request->product_id[$key];
        $sale->invoice_id = $invoice->id;
        $sale->save();
      }
      return redirect('invoice/'.$invoice->id)->with('message','invoice created Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
      Sales::where('invoice_id', $id)->delete();
      $invoice = Invoice::findOrFail($id);
      $invoice->delete();
      return redirect()->back();
    }
}
