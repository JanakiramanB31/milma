<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Invoice;
use Illuminate\Http\Request;

class BankTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $paymentMethods = array('Cash', 'Bank Transfer', 'Credit');
      $formattedDate = now()->toDateString();
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $filteredInvoices = Invoice::with('Customer')->whereDate('created_at', $formattedDate)->where('payment_type', 'Bank Transfer')->get();
      return view('bt_list.index', compact('currency', 'decimalLength', 'filteredInvoices','paymentMethods'));
    }

    public function fetchBTInvoicesByDate(Request $request) {
      $data = json_decode($request->input('data'), true);
      $fromDate = $data['fromDate'];
      $toDate = $data['toDate'];
      $paymentMethod = $data['paymentMethod'];
      if ($fromDate && $toDate) {
        $fromDate = \Carbon\Carbon::parse($fromDate)->startOfDay();
        $toDate = \Carbon\Carbon::parse($toDate)->endOfDay();
      } else {
          $fromDate = now()->startOfDay()->toDateString();
          $toDate = now()->endOfDay()->toDateString();
      }

      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $filteredInvoices = Invoice::with('Customer') 
      ->when($fromDate == $toDate, function ($query) use ($fromDate) {
        return $query->whereDate('created_at', $fromDate);
      })
      ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
        return $query->whereBetween('created_at', [$fromDate, $toDate]);
      }) 
      ->when($paymentMethod != "", function ($query) use ($paymentMethod) {
        $query->where('payment_type', $paymentMethod);
      })
      ->get();
      $data = [
        "filteredInvoices" => $filteredInvoices,
        "currency" =>$currency,
        "decimalLength"=> $decimalLength
      ];
      return response()->json( $data );
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
      $request->validate([
        'invoice_id' => 'required',
        'received_date' => 'required',
    ]);
    $today = now()->format('Y-m-d H:i:s');
    // $this->pr($request->all());
    // exit;
    // $data = $this->pr($request);
    $invoice = Invoice::findOrFail($id);
    // $this->pr($invoice);
    // echo "new";
    if ($invoice->paid_amt == null) {
      $invoice->paid_amt = $invoice->received_amt;
    }
    $invoice->received_amt = $invoice->paid_amt;
    $balance = floatval($invoice->balance_amt) - floatval($invoice->paid_amt);
    $invoice->balance_amt = ($balance > 0) ? $balance : 0;
    $invoice->amt_received_at = $request->received_date;
    $invoice->reference_number = $request->reference_number ?? null;
    $invoice->ref_number_updated_at = $today;
    $invoice->save();

    $cusID = $invoice->customer_id;

    $customer = Customer::findOrFail($cusID);
    $cusPrevBalAmt = floatval($customer->previous_balance) - floatval($invoice->paid_amt);
    $customer->previous_balance = ($cusPrevBalAmt > 0) ? $cusPrevBalAmt : 0;
    $customer->save();
    //$this->pr($invoice);

    
    $oldInvoice = Invoice::where('customer_id', $cusID)->orderBy('created_at', 'desc')->first();

    if ($oldInvoice->id != $invoice->id) {
      $prevBalAmt = floatval($oldInvoice->prev_acc_bal_amt) - floatval($invoice->paid_amt);
      $oldInvoice->prev_acc_bal_amt = ($prevBalAmt > 0) ? $prevBalAmt : 0;
      $accBalAmt = floatval($oldInvoice->acc_bal_amt) - floatval($invoice->paid_amt);
      $oldInvoice->acc_bal_amt = ($accBalAmt > 0) ? $accBalAmt : 0;
      $balance = floatval($oldInvoice->balance_amt) - floatval($invoice->paid_amt);
      $oldInvoice->balance_amt = $balance ? $balance : 0;
      $oldInvoice->save();
      //$this->pr($invoice);
    }
    //exit;
    //$invoice->save();
    // return redirect('invoice/'.$invoice->id)->with('message','Invoice Updated Successfully');
    return response()->json( [
      //'data' => $this->pr($invoice),
      'success' => true,
      'message' => 'Reference number updated successfully.',
  ]); 
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
