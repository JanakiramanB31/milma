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
        $customers = Customer::all();
        $products = Product::whereIn('id', function ($query) {
          $query->select('product_id')->from('stock_in_transits');
        })->get();
        return view('invoice.create', compact('customers','products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProducts(Request $request, $id) {
     // $this->pr($request->all());
      //return response()->json(['message' => 'No Data'],200);
      $products = Product::select('id','name')->whereIn('id', function($query) use ($id) {
        $query->select('product_id')->from('sales')->whereIn('invoice_id', function($subQuery) use ($id) {
          $subQuery->select('id')->from('invoices') ->where('customer_id', $id);
        });
      })->get();
      $productPricesAndIDs = ProductPrice::select('product_id','price')->whereIn('rate_id', function($query) use($id) {
        $query->select('rate_id')->from('customers')->where('id', $id);
      })->get();
      
      $prodIDsAndPrices= $productPricesAndIDs->pluck('price', 'product_id')->toArray();
      $this->pr($prodIDsAndPrices);
      //exit;
      $productIDs = $products->pluck('id')->toArray();
      $invoiceIDs = Invoice::where('customer_id', $id)->pluck('id')->toArray();
      
      $quantityAndPrices = Sales::select('product_id','qty','price')->whereIn('product_id',$productIDs)->whereIn('invoice_id',$invoiceIDs)->get()->toArray();
      //$this->pr($productIDs);
      //$this->pr($invoiceIDs);
      //$this->pr($quantityAndPrices);
      //exit;

      return response()->json(['products' => $products,'quantityAndPrices' => $quantityAndPrices, 'productIdsAndPrices'=>$prodIDsAndPrices]);
     
     
    }
    public function store(Request $request)
    {
      $this->pr($request->all());
      //exit;
        $request->validate([
            'customer_id' => 'required|integer',
            'product_id' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'amount' => 'required',
        ]);
        $userId = Auth::id();

        $invoice = new Invoice();
        $invoice->customer_id = $request->customer_id;
        $invoice->user_id = $userId;
        $invoice->total = 1000;
        $invoice->save();

        foreach ( $request->product_id as $key => $product_id){
          if(isset($product_id[$key]) && !empty($product_id[$key])){
            $sale = new Sale();
            $sale->type = $request->type[$key];
            $sale->reason = $request->reason[$key];
            $sale->user_id = $userId;
            $sale->qty = $request->qty[$key];
            $sale->price = $request->price[$key];
            $sale->received_amt = $request->received_amt;
            $sale->balance_amt = ($request->total)- ($request->received_amt);
            $sale->amount = $request->amount[$key];
            $sale->product_id = $request->product_id[$key];
            $sale->invoice_id = $invoice->id;
            $sale->save();

          }
         }

         return redirect('invoice/'.$invoice->id)->with('message','Invoice created Successfully');




    }

    public function storeReturns(Request $request)
    {
      $this->pr($request->all());
      exit;
      foreach ( $request->product_id as $key => $product_id){
        $return = new Returns();
        $return->product_id = $request->return_product_id[$key];
        $return->quantity = $request->return_qty[$key];
        $return->price = $request->return_amount[$key];
        $return->save();


    }
    }

    public function findPrice(Request $request){
        $data = DB::table('products')->select('sales_price')->where('id', $request->id)->first();
        return response()->json($data);
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
        $Amount = Sale::select('received_amt','balance_amt')->where('invoice_id', $id)->first();
        //$this->pr($Amount);
        return view('invoice.show', compact('invoice','sales','Amount'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customers = Customer::all();
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
