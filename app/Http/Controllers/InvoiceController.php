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
      $userID = Auth::id();
      $userRole = Auth::user()->role;
      $today = now()->toDateString();
      if ($userRole == 'admin') {
        $invoices = Invoice::whereDate('created_at', $today)->get();
      } else {
        $invoices = Invoice::where('user_id',$userID)->whereDate('created_at', $today)->get();
      }
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
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $customers = Customer::where('status',1)->get();
      $paymentMethods = array('Cash', 'Bank Transfer', 'Credit');
      if ($userRole == 'admin') {
        $products = Product::where('status',1)->get();
      } else {
        $routeData = StockInTransit::select('route_id', 'vehicle_id')->where('user_id',$userID)->whereDate('created_at', $today)->first();

        if ($routeData) {
          $products = Product::where('status',1)->whereIn('id', function ($query) use( $userID, $routeData, $today) {
            $query->select('product_id')->where('user_id',$userID)->where('route_id',$routeData->route_id)->where('vehicle_id',$routeData->vehicle_id)->whereDate('created_at', $today)->from('stock_in_transits');
          })->get();
          $quantities = StockInTransit::where('user_id', $userID) ->where('route_id', $routeData->route_id)->where('vehicle_id', $routeData->vehicle_id)->pluck('quantity', 'product_id');
          foreach ($products as $product) {
            $product->quantity = $quantities->get($product->id, 0);
          }
          //$this->pr($products);
          //exit;         
        } else {
          $products =array();
          $returnProducts = array();
          $routeEmptyError = "Route Number or Vehicle Number Not Found";
          return view('invoice.createforphone', compact('customers','userRole','returnProducts','paymentMethods','routeEmptyError','products'));
        }
      }
      return view('invoice.createforphone', compact('customers','userRole','returnProducts','paymentMethods','products','routeEmptyError','currency','decimalLength'));
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
      $PrevbalAmt = Invoice::select('prev_acc_bal_amt')->where('customer_id', $cusID)->orderBy('created_at', 'desc')->first() ?? 0;
      $returnProducts = Product::select('id','name')->where('status',1)->whereIn('id', function($query) use ($cusID) {
        $query->select('product_id')->from('sales')->whereIn('invoice_id', function($subQuery) use ($cusID) {
          $subQuery->select('id')->from('invoices') ->where('customer_id', $cusID);
        });
      })->get();

      $productPricesAndIDs = ProductPrice::select('product_id','price')->whereIn('rate_id', function($query) use($cusID) {
        $query->select('rate_id')->from('customers')->where('id', $cusID);
      })->get();
      
      $prodIDsAndPrices= $productPricesAndIDs->pluck('price', 'product_id')->toArray();

      $productBasePricesAndIDs = Product::select('id','base_rate')->get();
      $prodIDsAndBasePrices= $productBasePricesAndIDs->pluck('base_rate', 'id')->toArray();

      $productIDs = $returnProducts->pluck('id')->toArray();
      $invoiceIDs = Invoice::where('customer_id', $id)->pluck('id')->toArray();
      $quantityAndPrices = Sales::select('product_id','qty','price')->whereIn('product_id',$productIDs)->whereIn('invoice_id',$invoiceIDs)->get()->toArray();
       //$this->pr($productPricesAndIDs);
      //  $this->pr($prodIDsAndPrices);
      //  exit;

      return response()->json(['returnProducts' => $returnProducts,'quantityAndPrices' => $quantityAndPrices, 'productIdsAndPrices' => $prodIDsAndPrices,'prodIDsAndBasePrices' => $prodIDsAndBasePrices,'balance_amount' => $balAmt, 'prev_acc_bal_amt' => $PrevbalAmt]);
      
    }

    public function fetchProducts(Request $request, $id) {
      $userId = Auth::id();
      $prodID = $id;
      $today = now()->toDateString();
      $productIDsandQuantities = StockInTransit::where('product_id', $prodID)->where('user_id', $userId)->whereDate('created_at', $today)->pluck('quantity', 'product_id')->toArray();      
      return response()->json(['productIDsandQuantitites' => $productIDsandQuantities]);
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
      $today = now()->toDateString();
      $data = $request->all();
      $returnTotal = 0;
      foreach ($data['type'] as $index => $type) {
        if ($type === 'returns') {
          $returnTotal += $data['amount'][$index] ?? 0;
        }
      }

      
      $oldInvoice = Invoice::where('customer_id', $cusID)->orderBy('created_at', 'desc')->first();
      if ($oldInvoice) {
        $oldInvoice->balance_amt = 0;
        $oldInvoice->save();
      }

      $balAmt = ($request->total) + ($request->acc_bal_amt) - ($request->received_amt);
      $returnedAmt =  ($request->received_amt) - (($request->total) + ($request->acc_bal_amt));

      $invoice = new Invoice();
      $invoice->customer_id = $request->customer_id;
      $invoice->user_id = $userId;
      $invoice->payment_type = $request->payment_type;
      $invoice->received_amt = $request->payment_type == "Credit" ? "0" : $request->received_amt;
      $invoice->acc_bal_amt = $request->acc_bal_amt;

      if ($request->payment_type == "Credit") {
        $invoice->balance_amt = $request->acc_bal_amt + $request->received_amt;
      } else if (($request->total + $request->acc_bal_amt) > $request->received_amt) {
        $invoice->balance_amt = $balAmt; 
      } else {
        $invoice->balance_amt = 0.00; 
      }
      $invoice->total_amount = $request->total;
      if($returnedAmt > 0 ) {
        $invoice->returned_amt = $returnedAmt;
      } else {
        $invoice->returned_amt = 0;
      }
      $invoice->prev_acc_bal_amt = $request->acc_bal_amt;
      $invoice->save();

      
      // $this->pr($request->all());
      // exit;
      
      foreach ( $request->product_id as $key => $product_id){
        if ($product_id && isset($request->qty[$key]) && $request->qty[$key]) {
          $sale = new Sale();
          $sale->type = $request->type[$key];
          $sale->reason = $request->reason[$key];
          $sale->user_id = $userId;
          $sale->customer_id = $request->customer_id;
          $sale->qty = $request->qty[$key];
          $sale->price = $request->price[$key];
          $sale->total_amount = $request->amount[$key];
          $sale->product_id = $request->product_id[$key];
          $sale->invoice_id = $invoice->id;
          $sale->save();

          $returns = new Returns();
          if ($request->type[$key] === "returns") {
            $returns->customer_id = $request->customer_id;
            $returns->product_id = $request->product_id[$key];
            $returns->invoice_id = $invoice->id;
            $returns->salesman_id =  $userId;
            $returns->quantity = $request->qty[$key];
            $returns->amount = $request->amount[$key];
            $returns->save();
          }

          $customer = Customer::findOrFail($request->customer_id);
          $customer->previous_balance = $invoice->balance_amt;
          $customer->save();

          $stockintransits = StockInTransit::where('user_id', $userId)->where('product_id',$product_id)->whereDate('created_at', $today)->get();

          foreach ($stockintransits as $stockintransit) {
            if ($request->type[$key] === "sales") {
              $stockintransit->sold_qty += $request->qty[$key];
              $stockintransit->quantity -= $request->qty[$key];
            } else {
              $stockintransit->sold_qty -= $request->qty[$key];
              $stockintransit->quantity += $request->qty[$key];
            }
            $stockintransit->save();
          }
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
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $invoice = Invoice::findOrFail($id);
      $sales = Sale::where('invoice_id', $id)->get();
      $amount = Invoice::select('total_amount','received_amt','prev_acc_bal_amt','acc_bal_amt','balance_amt')->where('id', $id)->first();
      return view('invoice.show', compact('invoice','sales','amount','currency','decimalLength'));
    }

    public function fetchInvoiceByDate(Request $request, $date){
      $userID = Auth::id();
      $userRole = Auth::user()->role;
      $selectedDate = \Carbon\Carbon::parse($date)->format('Y-m-d');
      if ($userRole == 'admin') {
      $invoices = Invoice::with('customer')->whereDate('created_at', $selectedDate)->get();
      } else {
        $invoices = Invoice::with('customer')->where('user_id',$userID)->whereDate('created_at', $selectedDate)->get();
      }
      // $this->pr($invoices);
      // exit;

      return response()->json($invoices);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $userID = Auth::id();
      $userRole = Auth::user()->role;
      $today = now()->toDateString();
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $routeEmptyError = null;
      $paymentMethods = array('Cash', 'Bank Transfer', 'Credit');
      $customers = Customer::where('status',1)->get();
      $invoice = Invoice::findOrFail($id);
      $sales = Sale::where('invoice_id', $id)->get();
      $customerID = $invoice->customer_id;
      $returnProducts = Returns::where('invoice_id', $id)->where('customer_id',$customerID)->get();
      // $this->pr($returnProducts);
      // exit;

      if ($userRole == 'admin') {
        $products = Product::where('status',1)->get();
      } else {
        $routeData = StockInTransit::select('route_id', 'vehicle_id')->where('user_id',$userID)->whereDate('created_at', $today)->first();
        if ($routeData) {
          $products = Product::where('status',1)->whereIn('id', function ($query) use( $userID, $routeData) {
            $query->select('product_id')->where('user_id',$userID)->where('route_id',$routeData->route_id)->where('vehicle_id',$routeData->vehicle_id)->from('stock_in_transits');
          })->get();
          $quantities = StockInTransit::where('user_id', $userID) ->where('route_id', $routeData->route_id)->where('vehicle_id', $routeData->vehicle_id)->pluck('quantity', 'product_id');
          foreach ($products as $product) {
            $product->quantity = $quantities->get($product->id, 0);
          }        
        } else {
          $products=array();
          $routeEmptyError = "Route Number or Vehicle Number Not Found";
          return view('invoice.edit', compact('customers','userRole','returnProducts','invoice','sales','paymentMethods','routeEmptyError','products'));
        }
      }
      
      return view('invoice.edit', compact('customers','userRole','returnProducts','invoice','sales','paymentMethods','routeEmptyError','products','currency','decimalLength'));
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
          'amount' => 'required',
      ]);
      $this->pr($request->all());
      // exit;
      $userId = Auth::id();
      $cusID = $request->customer_id;
      $today = now()->toDateString();

      $balAmt = ($request->total) + ($request->acc_bal_amt) - ($request->received_amt);
      $returnedAmt =  ($request->received_amt) - (($request->total) + ($request->acc_bal_amt));

      $invoice = Invoice::findOrFail($id);
      $invoice->customer_id = $request->customer_id;
      $invoice->user_id = $userId;
      $invoice->payment_type = $request->payment_type;
      $invoice->received_amt = $request->payment_type == "Credit" ? $request->prev_received_amt : ($request->received_amt);
      $invoice->acc_bal_amt = $request->acc_bal_amt;

      if ($request->payment_type == "Credit") {
        $invoice->balance_amt = $request->acc_bal_amt + $request->received_amt;
      } else if (($request->total + $request->acc_bal_amt) > ($request->prev_received_amt + $request->received_amt)) {
        $invoice->balance_amt = $balAmt; 
      } else {
        $invoice->balance_amt = 0.00; 
      }
      // if (($request->total + $request->acc_bal_amt) > $request->received_amt) {
      //   $invoice->balance_amt = $balAmt; 
      // } else {
      //   $invoice->balance_amt = 0.00; 
      // }
      $invoice->total_amount = $request->total;
      if($returnedAmt > 0 ) {
        $invoice->returned_amt = $returnedAmt;
      } else {
        $invoice->returned_amt = 0;
      }
      $invoice->prev_acc_bal_amt = $request->acc_bal_amt;
      $invoice->save();

      Sale::where('invoice_id', $id)->delete();
      Returns::where('invoice_id', $id)->delete();

      foreach ( $request->product_id as $key => $product_id){
        if ($product_id && isset($request->qty[$key]) && $request->qty[$key]) {
          $sale = new Sale();
          $sale->type = $request->type[$key];
          $sale->reason = $request->reason[$key];
          $sale->user_id = $userId;
          $sale->customer_id = $request->customer_id;
          $sale->qty = $request->qty[$key];
          $sale->price = $request->price[$key];
          $sale->total_amount = $request->amount[$key];
          $sale->product_id = $request->product_id[$key];
          $sale->invoice_id = $invoice->id;
          $sale->save();

          $returns = new Returns();
          if ($request->type[$key] === "returns") {
            $returns->customer_id = $request->customer_id;
            $returns->product_id = $request->product_id[$key];
            $returns->invoice_id = $invoice->id;
            $returns->salesman_id =  $userId;
            $returns->quantity = $request->qty[$key];
            $returns->amount = $request->amount[$key];
            $returns->save();
          }

          $customer = Customer::findOrFail($request->customer_id);
          $customer->previous_balance = $invoice->balance_amt;
          $customer->save();

          $stockintransits = StockInTransit::where('user_id', $userId)->where('product_id',$product_id)->whereDate('created_at', $today)->get();
          // $this->pr($request->all());
          // exit;
          foreach ($stockintransits as $stockintransit) {
            if ($request->type[$key] === "sales") {
              
              $stockintransit->sold_qty = $stockintransit->sold_qty + ( $request->qty[$key] - $request->prev_qty[$key] );
              $stockintransit->quantity = $stockintransit->quantity - ( $request->qty[$key] - $request->prev_qty[$key] );
              // $this->pr("sold_qty");$this->pr($stockintransit->sold_qty);
              // $this->pr("quantity");$this->pr($stockintransit->quantity);
              // $this->pr("qty");$this->pr($request->qty[$key]);
              // $this->pr("prev_qty");$this->pr($request->prev_qty[$key]);
              // $this->pr("sold_qty1");$this->pr($stockintransit->sold_qty + ( $request->qty[$key] - $request->prev_qty[$key] ));
              // $this->pr("quantity2");$this->pr($stockintransit->quantity - ( $request->qty[$key] - $request->prev_qty[$key] ));
            } else {
              $stockintransit->sold_qty -= ($request->prev_qty[$key] - $request->qty[$key]);
              $stockintransit->quantity += $request->prev_qty[$key];
            }
            // exit;
            $stockintransit->save();
          }
        }
      }
      return redirect('invoice/'.$invoice->id)->with('message','Invoice Updated Successfully');

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
