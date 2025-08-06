<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Invoice;
use App\Product;
use App\Returns;
use App\Route;
use App\Sale;
use App\Sales;
use App\StockInTransit;
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
      $userID = Auth::id();
      $userRole = Auth::user()->role;
      $paymentMethods = config('constants.PAYMENT_METHODS');
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $routes = Route::all();

      if ($userRole == 'admin') {
        $saleTypesandTotalAmounts = Sale::select('type', 'qty', 'total_amount')->whereDate('created_at', $selectedDate)->get();
      } else {
        $saleTypesandTotalAmounts = Sale::select('type', 'qty', 'total_amount')->where('user_id', $userID)->whereDate('created_at', $selectedDate)->get();
      } 
      //  echo '<pre>'; print_r($saleTypesandTotalAmounts); echo '</pre>';exit;

      $saleProductsTypesandAmounts = $saleTypesandTotalAmounts->groupBy('type')->map(function ($group) {
        return [
          'total_amt' => $group->sum('total_amount'),
          'qty_count'  => $group->count(),
        ];
      });

      $saleType = $saleProductsTypesandAmounts->get('sales', ['total_amt' => 0, 'qty_count' => 0]);
      $returnType = $saleProductsTypesandAmounts->get('returns', ['total_amt' => 0, 'qty_count' => 0]);

      if ($userRole == 'admin') {
        $paymentTypesandTotalAmounts = Invoice::select('payment_type', 'total_amount', 'paid_amt', 'received_amt','balance_amt','returned_amt')->whereDate('created_at', $selectedDate)->get();
      } else {
        $paymentTypesandTotalAmounts = Invoice::select('payment_type', 'total_amount', 'paid_amt', 'received_amt','balance_amt','returned_amt')->where('user_id', $userID)->whereDate('created_at', $selectedDate)->get();
      } 


      $saleProductspaymentTypesandAmounts = $paymentTypesandTotalAmounts->groupBy('payment_type')->map(function ($group) {
        return [
          'total_received_amt' => $group->sum(function ($item) {
            return $item->received_amt - $item->returned_amt;
        }),
          'transaction_count'  => $group->count(),
        ];
      });

      // $this->pr($saleProductspaymentTypesandAmounts);
      // exit;

      $creditPayments = $paymentTypesandTotalAmounts->filter(function ($item) use ($paymentMethods) {
        // return $item->balance_amt > 0;  
        return $item->payment_type == $paymentMethods[2];  
      });
      // echo $creditPayments;
      // exit;

      $expenses = Expense::select('expense_amt')->whereDate('expense_date', $selectedDate)->get()->toArray();
      $totalExpAmt = collect($expenses)->sum('expense_amt');      
      
      $creditTotPayments   = $creditPayments->sum('total_amount'); 
      // echo $totalCreditAmount;
      // exit;
      $creditTransactionCount = $creditPayments->count();

      $cashPayments = $saleProductspaymentTypesandAmounts->get($paymentMethods[0], ['total_received_amt' => 0, 'transaction_count' => 0]);
      $bankPayments = $saleProductspaymentTypesandAmounts->get($paymentMethods[1], ['total_received_amt' => 0, 'transaction_count' => 0]);

      //$this->pr($bankPayments);
      //exit;
      $cashTotPayments = $cashPayments['total_received_amt'];
      $bankTotPayments = $bankPayments['total_received_amt'];
      $totAmtOfSales = $cashTotPayments + $bankTotPayments + $creditTotPayments;
      $totAmt = $cashTotPayments + $bankTotPayments - $creditTotPayments;
      $totReturnsAmt = $returnType['total_amt'];
      $totNetAmt = $cashTotPayments + $bankTotPayments - $creditTotPayments - $returnType['total_amt'] - $totalExpAmt;

      $totCashSales = $cashPayments['transaction_count'];
      $totBankSales = $bankPayments['transaction_count'];
      $totcreditSales = $creditTransactionCount;
      $totSales = $totCashSales + $totBankSales + $creditTransactionCount;
      $totReturns = $returnType['qty_count'];
      // echo '<pre>'; print_r($totalAmt); echo '</pre>';exit;
     
      return view('report.x_index', compact(
        'totCashSales', 
        'cashTotPayments',
        'totBankSales',
        'bankTotPayments', 
        'totcreditSales',
        'creditTotPayments', 
        'totSales',
        'totAmtOfSales', 
        'totAmt', 
        'totalExpAmt', 
        'totReturns',
        'totReturnsAmt',
        'totNetAmt', 
        'routes', 'currency','decimalLength','paymentMethods'));
    }

    public function fetchByDate(Request $request, $date)
    {
      $userID = Auth::id();
      $userRole = Auth::user()->role;
      $queryCondition = $userRole == 'admin' ? [] : ['user_id', $userID];
      $paymentMethods = config('constants.PAYMENT_METHODS');
      $data = $request->input('date'); 
      $fromDate = $data['fromDate'];
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $toDate = $data['toDate'];
      if ($fromDate && $toDate) {
        $fromDate = \Carbon\Carbon::parse($fromDate)->startOfDay();
        $toDate = \Carbon\Carbon::parse($toDate)->endOfDay();
      } else {
          $fromDate = now()->toDateString();
          $toDate = now()->toDateString();
      }

      $expenses = Expense::select('expense_amt')->when($fromDate == $toDate, function ($query) use ($fromDate) {
        return $query->whereDate('expense_date', $fromDate);
      })
      ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
        return $query->whereBetween('expense_date', [$fromDate, $toDate]);
      })->get()->toArray();

      $totExpenses = collect($expenses)->sum('expense_amt');

      // $this->pr($totExpenses);
      // $this->pr($expenses);
      // exit;
      

      $saleTypesandTotalAmounts = Sale::select('type', 'qty', 'total_amount')
      ->when($queryCondition, function ($query) use ($queryCondition) {
        return $query->where($queryCondition[0], $queryCondition[1]);
      })
      ->when($fromDate == $toDate, function ($query) use ($fromDate) {
        return $query->whereDate('created_at', $fromDate);
      })
      ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
        return $query->whereBetween('created_at', [$fromDate, $toDate]);
      })
      ->get();

    
      
      //  echo '<pre>'; print_r($saleTypesandTotalAmounts); echo '</pre>';exit;

      $saleProductsTypesandAmounts = $saleTypesandTotalAmounts->groupBy('type')->map(function ($group) {
        return [
          'total_amt' => $group->sum('total_amount'),
          'qty_count'  => $group->count(),
        ];
      });

      // $this->pr($saleProductsTypesandAmounts);
      // exit;

      $saleType = $saleProductsTypesandAmounts->get('sales', ['total_amt' => 0, 'qty_count' => 0]);
      $returnType = $saleProductsTypesandAmounts->get('returns', ['total_amt' => 0, 'qty_count' => 0]);


      $saleTypesandTotalAmounts = Sale::select('type', 'qty', 'total_amount')
        ->when($queryCondition, function ($query) use ($queryCondition) {
          return $query->where($queryCondition[0], $queryCondition[1]);
        })
        ->when($fromDate == $toDate, function ($query) use ($fromDate) {
          return $query->whereDate('created_at', $fromDate);
        })
        ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
          return $query->whereBetween('created_at', [$fromDate, $toDate]);
        })
        ->get();

      $paymentTypesandTotalAmounts = Invoice::select('payment_type', 'total_amount', 'paid_amt', 'received_amt', 'balance_amt', 'returned_amt')
        ->when($queryCondition, function ($query) use ($queryCondition) {
          return $query->where($queryCondition[0], $queryCondition[1]);
        })
        ->when($fromDate == $toDate, function ($query) use ($fromDate) {
          return $query->whereDate('created_at', $fromDate);
        })
        ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
          return $query->whereBetween('created_at', [$fromDate, $toDate]);
        })
        ->get();

      $saleProductspaymentTypesandAmounts = $paymentTypesandTotalAmounts->groupBy('payment_type')->map(function ($group) {
        return [
          'total_paid_amt' => $group->sum('paid_amt'),
          'total_amt' => $group->sum('total_amount'),
          'total_received_amt' => $group->sum(function ($item) {
            return $item->received_amt - $item->returned_amt;
          }),
          'transaction_count'  => $group->count(),
        ];
      });

      // $this->pr($saleProductspaymentTypesandAmounts);
      // exit;

      $expenses = Expense::select('expense_amt')
          ->whereBetween('expense_date', [$fromDate, $toDate])
          ->get()
          ->toArray();
      $totalExpAmt = collect($expenses)->sum('expense_amt');      
      

      $creditPayments = $paymentTypesandTotalAmounts->filter(function ($item) use ($paymentMethods) {
        return $item->payment_type == $paymentMethods[2];  
      });

      $creditTotPayments = $creditPayments->sum('total_amount'); 
      $creditTransactionCount = $creditPayments->count();

      $cashPayments = $saleProductspaymentTypesandAmounts->get($paymentMethods[0], ['total_received_amt' => 0, 'transaction_count' => 0]);
      $bankPayments = $saleProductspaymentTypesandAmounts->get($paymentMethods[1], ['total_received_amt' => 0, 'transaction_count' => 0]);
      
      $cashTotPayments = $cashPayments['total_received_amt'];
      $bankTotPayments = $bankPayments['total_received_amt'];
      $totAmtOfSales = $cashTotPayments + $bankTotPayments + $creditTotPayments;
      $totAmt = $cashTotPayments + $bankTotPayments - $creditTotPayments;
      $totReturnsAmt = $returnType['total_amt'];
      $totNetAmt = $cashTotPayments + $bankTotPayments - $creditTotPayments - $returnType['total_amt'] - $totalExpAmt;

      $totCashSales = $cashPayments['transaction_count'];
      $totBankSales = $bankPayments['transaction_count'];
      $totcreditSales = $creditTransactionCount;
      $totSales = $totCashSales + $totBankSales + $creditTransactionCount;
      $totReturns = $returnType['qty_count'];
     
      $filteredData = [
        'currency'         => $currency, 
        'decimalLength'    => $decimalLength, 
        'totCashSales'     => $totCashSales,
        'cashTotPayments'  => $cashTotPayments,
        'totBankSales'     => $totBankSales,
        'bankTotPayments'  => $bankTotPayments,
        'totcreditSales'   => $totcreditSales,
        'creditTotPayments'=> $creditTotPayments,
        'totSales'         => $totSales,
        'totAmtOfSales'    => $totAmtOfSales,
        'totAmt'           => $totAmt,
        'totalExpAmt'      => $totalExpAmt,
        'totReturns'       => $totReturns,
        'totReturnsAmt'    => $totReturnsAmt,
        'totNetAmt'        => $totNetAmt
      ];
      // $this->pr($filteredData);
      // exit;
      return response()->json( $filteredData );
    }

    public function x_report_print(Request $request) 
    {
      $userID = Auth::id();
      $userRole = Auth::user()->role;
      $queryCondition = $userRole == 'admin' ? [] : ['user_id', $userID];
      $paymentMethods = config('constants.PAYMENT_METHODS');
      $dateData = $request->input('date'); 
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      
      if ($dateData && isset($dateData['fromDate']) && isset($dateData['toDate'])) {
        $fromDate = \Carbon\Carbon::parse($dateData['fromDate'])->startOfDay();
        $toDate = \Carbon\Carbon::parse($dateData['toDate'])->endOfDay();
      } else {
        $fromDate = now()->startOfDay();
        $toDate = now()->endOfDay();
      }

      $expenses = Expense::select('expense_amt')->when($fromDate == $toDate, function ($query) use ($fromDate) {
        return $query->whereDate('expense_date', $fromDate);
      })
      ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
        return $query->whereBetween('expense_date', [$fromDate, $toDate]);
      })->get()->toArray();

      $totExpenses = collect($expenses)->sum('expense_amt');

      // $this->pr($totExpenses);
      // $this->pr($expenses);
      // exit;
      

      $saleTypesandTotalAmounts = Sale::select('type', 'qty', 'total_amount')
      ->when($queryCondition, function ($query) use ($queryCondition) {
        return $query->where($queryCondition[0], $queryCondition[1]);
      })
      ->when($fromDate == $toDate, function ($query) use ($fromDate) {
        return $query->whereDate('created_at', $fromDate);
      })
      ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
        return $query->whereBetween('created_at', [$fromDate, $toDate]);
      })
      ->get();

    
      
      //  echo '<pre>'; print_r($saleTypesandTotalAmounts); echo '</pre>';exit;

      $saleProductsTypesandAmounts = $saleTypesandTotalAmounts->groupBy('type')->map(function ($group) {
        return [
          'total_amt' => $group->sum('total_amount'),
          'qty_count'  => $group->count(),
        ];
      });

      // $this->pr($saleProductsTypesandAmounts);
      // exit;

      $saleType = $saleProductsTypesandAmounts->get('sales', ['total_amt' => 0, 'qty_count' => 0]);
      $returnType = $saleProductsTypesandAmounts->get('returns', ['total_amt' => 0, 'qty_count' => 0]);


      $saleTypesandTotalAmounts = Sale::select('type', 'qty', 'total_amount')
        ->when($queryCondition, function ($query) use ($queryCondition) {
          return $query->where($queryCondition[0], $queryCondition[1]);
        })
        ->when($fromDate == $toDate, function ($query) use ($fromDate) {
          return $query->whereDate('created_at', $fromDate);
        })
        ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
          return $query->whereBetween('created_at', [$fromDate, $toDate]);
        })
        ->get();

      $paymentTypesandTotalAmounts = Invoice::select('payment_type', 'total_amount', 'paid_amt', 'received_amt', 'balance_amt', 'returned_amt')
        ->when($queryCondition, function ($query) use ($queryCondition) {
          return $query->where($queryCondition[0], $queryCondition[1]);
        })
        ->when($fromDate == $toDate, function ($query) use ($fromDate) {
          return $query->whereDate('created_at', $fromDate);
        })
        ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
          return $query->whereBetween('created_at', [$fromDate, $toDate]);
        })
        ->get();

      $saleProductspaymentTypesandAmounts = $paymentTypesandTotalAmounts->groupBy('payment_type')->map(function ($group) {
        return [
          'total_paid_amt' => $group->sum('paid_amt'),
          'total_amt' => $group->sum('total_amount'),
          'total_received_amt' => $group->sum(function ($item) {
            return $item->received_amt - $item->returned_amt;
          }),
          'transaction_count'  => $group->count(),
        ];
      });

      // $this->pr($saleProductspaymentTypesandAmounts);
      // exit;

      $expenses = Expense::select('expense_amt')
          ->whereBetween('expense_date', [$fromDate, $toDate])
          ->get()
          ->toArray();
      $totalExpAmt = collect($expenses)->sum('expense_amt');      
      

      $creditPayments = $paymentTypesandTotalAmounts->filter(function ($item) use ($paymentMethods) {
        return $item->payment_type == $paymentMethods[2];  
      });

      $creditTotPayments = $creditPayments->sum('total_amount'); 
      $creditTransactionCount = $creditPayments->count();

      $cashPayments = $saleProductspaymentTypesandAmounts->get($paymentMethods[0], ['total_received_amt' => 0, 'transaction_count' => 0]);
      $bankPayments = $saleProductspaymentTypesandAmounts->get($paymentMethods[1], ['total_received_amt' => 0, 'transaction_count' => 0]);

      $cashTotPayments = $cashPayments['total_received_amt'];
      $bankTotPayments = $bankPayments['total_received_amt'];
      $totAmtOfSales = $cashTotPayments + $bankTotPayments + $creditTotPayments;
      $totAmt = $cashTotPayments + $bankTotPayments - $creditTotPayments;
      $totReturnsAmt = $returnType['total_amt'];
      $totNetAmt = $cashTotPayments + $bankTotPayments - $creditTotPayments - $returnType['total_amt'] - $totalExpAmt;

      $totCashSales = $cashPayments['transaction_count'];
      $totBankSales = $bankPayments['transaction_count'];
      $totcreditSales = $creditTransactionCount;
      $totSales = $totCashSales + $totBankSales + $creditTransactionCount;
      $totReturns = $returnType['qty_count'];
     
      return view('report.x_report_print', compact(
        'totCashSales', 
        'cashTotPayments',
        'totBankSales',
        'bankTotPayments', 
        'totcreditSales',
        'creditTotPayments', 
        'totSales',
        'totAmtOfSales', 
        'totAmt', 
        'totalExpAmt', 
        'totReturns',
        'totReturnsAmt',
        'totNetAmt', 
        'currency','decimalLength','paymentMethods', 'fromDate', 'toDate'
      ));
    }
    public function y_index()
    {
      $today = now()->toDateString();
      $paymentMethods = config('constants.PAYMENT_METHODS');
      $cash_invoices = Invoice::where('payment_type', $paymentMethods[0])->whereDate('created_at', $today)->count();
      $bank_invoices = Invoice::where('payment_type', $paymentMethods[1])->whereDate('created_at', $today)->count();
      $card_invoices = Invoice::where('payment_type', $paymentMethods[2])->whereDate('created_at', $today)->count();
      
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

    public function overall_report()
    {
      $paymentMethods = config('constants.PAYMENT_METHODS');
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $formattedDate = now()->toDateString();
      $routes = Route::all();
      $invoiceWithCustomer = Invoice::with('Customer')->get(); 

      $customerInfo = $invoiceWithCustomer->map(function ($invoice) {
          return $invoice->Customer->company_name ?? '--'; 
      });
      $groupedInvoices = $invoiceWithCustomer->groupBy(function ($invoice) {
        return $invoice->Customer ? $invoice->Customer->company_name : 'Unknown Company';
      });

      // Now map the grouped invoices to just include the invoice data
      $invoiceData = $groupedInvoices->map(function ($invoices, $companyName) {
        return $invoices->map(function ($invoice) {
            // Return only the desired fields for each invoice
            return [
              $invoice
            ];
        });
        
      });
      $filteredInvoices = Invoice::with('Customer')->whereDate('created_at', $formattedDate)->get();
   
      return view('report.overall_report', compact('routes', 'filteredInvoices','groupedInvoices','currency','decimalLength','paymentMethods'));
    }

    public function fetchCompanyInvoices(Request $request)
    {
      $paymentMethods = config('constants.PAYMENT_METHODS');
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $data = json_decode($request->input('data'), true);
      $data['companyName'] = trim($data['companyName']);
      $data['routeID'] = trim($data['routeID']);
      $data['paymentMethod'] = trim($data['paymentMethod']);

      if (empty($data['selectedDate'])) {
        $data['selectedDate'] = date('Y-m-d');
      }
      $selectedDate = $data['selectedDate'];
      $companyName = $data['companyName'];
      $routeID = $data['routeID'];
      $paymentMethod = $data['paymentMethod'];
      if ($data) {
        $formattedDate = \Carbon\Carbon::parse($selectedDate)->format('Y-m-d');
      } else {
        $formattedDate = now()->toDateString();
      }
      $routes = Route::all();
      $invoiceWithCustomer = Invoice::with('Customer')->get(); 

      $routeIDData = StockInTransit::select('user_id')->where('route_id', $routeID)->whereDate('created_at', $formattedDate)->get()->pluck('user_id');

      $filteredInvoices = Invoice::with('Customer')->whereDate('created_at', $formattedDate)
        ->when($companyName != "", function ($query) use ($companyName) {
            $query->whereHas('Customer', function ($q) use ($companyName) {
                $q->where('company_name', $companyName);
            });
        })
        ->when($routeID != "", function ($query) use ($routeIDData) {
            $query->whereIn('user_id', $routeIDData);
        })
        ->when($paymentMethod != "", function ($query) use ($paymentMethod) {
          $query->where('payment_type', $paymentMethod);
        })
        ->get();

      // foreach ($filteredInvoices as $invoice) {
      //   if ($invoice->routeID == $routeID) {
      //     $userID = $invoice->user_id;
      //     $stockInTransit = StockInTransit::where('user_id', $userID)->where('route_id', $routeID)->first();
  
      //     if ($stockInTransit) {
      //       return response()->json( $stockInTransit);
      //     } else {
      //       return response()->json(['message' => 'No stock in transit found for this route and user.'], 404);
      //     }
      //   }
      // }

      $data = [
        "filteredInvoices" => $filteredInvoices,
        "currency" =>$currency,
        "decimalLength"=> $decimalLength
      ];
   
      return response()->json( $data );
    }


    public function z_index() {
      $date = now()->startOfDay()->toDateString();
      $userID = Auth::id();
      $userRole = Auth::user()->role;

      $paymentMethods = config('constants.PAYMENT_METHODS');
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $formattedDate = now()->toDateString();
      $routes = Route::all();
      $invoiceWithCustomer = Invoice::with('Customer')->get(); 
      // echo '<pre>'; print_r($invoiceWithCustomer); echo '</pre>';
     
      $customerInfo = $invoiceWithCustomer->map(function ($invoice) {
          // return $invoice->Customer->company_name; 
          return $invoice->Customer->company_name ?? '--';
      });
      $groupedInvoices = $invoiceWithCustomer->groupBy(function ($invoice) {
        return $invoice->Customer ? $invoice->Customer->company_name : 'Unknown Company';
      });

      // Now map the grouped invoices to just include the invoice data
      $invoiceData = $groupedInvoices->map(function ($invoices, $companyName) {
        return $invoices->map(function ($invoice) {
            // Return only the desired fields for each invoice
            return [
              $invoice
            ];
        });
        
      });

      $filteredInvoices = Invoice::with('Customer', 'Sales.product')->where('print_status', '0')->whereDate('created_at', $formattedDate)->get();
    // $this->pr($filteredInvoices);exit;
   
      return view('report.z_index', compact( 'filteredInvoices','groupedInvoices','currency','decimalLength','paymentMethods'));

    }

    public function m_index() {
      $date = now()->startOfDay()->toDateString();
      $userID = Auth::id();
      $userRole = Auth::user()->role;

      $paymentMethods = config('constants.PAYMENT_METHODS');
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $formattedDate = now()->toDateString();
      $routes = Route::all();
      $invoiceWithCustomer = Invoice::with('Customer')->get(); 
     
      $customerInfo = $invoiceWithCustomer->map(function ($invoice) {
        return $invoice->Customer->company_name ?? '--';
      });
      $groupedInvoices = $invoiceWithCustomer->groupBy(function ($invoice) {
        return $invoice->Customer ? $invoice->Customer->company_name : 'Unknown Company';
      });

      // Now map the grouped invoices to just include the invoice data
      $invoiceData = $groupedInvoices->map(function ($invoices, $companyName) {
        return $invoices->map(function ($invoice) {
          // Return only the desired fields for each invoice
          return [
            $invoice
          ];
        });
        
      });

      $filteredInvoices = Invoice::with('Customer', 'Sales.product')->whereDate('created_at', $formattedDate)->get();
   
      return view('report.m_report_index', compact( 'filteredInvoices','groupedInvoices','currency','decimalLength','paymentMethods'));

    }

    public function zReportCompanyInvoices(Request $request)
    {
      $paymentMethods = config('constants.PAYMENT_METHODS');
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $data = json_decode($request->input('data'), true);
      $data['fromDate'] = trim($data['fromDate']);
      $data['toDate'] = trim($data['toDate']);
      $data['companyName'] = trim($data['companyName']);

      $fromDate = $data['fromDate'];
      $toDate = $data['toDate'];
      $companyName = $data['companyName'];

      if ($fromDate && $toDate) {
        $fromDate = \Carbon\Carbon::parse($fromDate)->startOfDay();
        $toDate = \Carbon\Carbon::parse($toDate)->endOfDay();
      } else {
        $fromDate = now()->toDateString();
        $toDate = now()->toDateString();
      }

      $filteredInvoices = Invoice::with('Customer', 'Sales.product')
        ->where('print_status', '0')
        ->when($fromDate == $toDate, function ($query) use ($fromDate) {
          return $query->whereDate('created_at', $fromDate);
        })
        ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
          return $query->whereBetween('created_at', [$fromDate, $toDate]);
        })
        ->when($companyName != "", function ($query) use ($companyName) {
            $query->whereHas('Customer', function ($q) use ($companyName) {
                $q->where('company_name', $companyName);
            });
        })
       
        ->get();

      // foreach ($filteredInvoices as $invoice) {
      //   if ($invoice->routeID == $routeID) {
      //     $userID = $invoice->user_id;
      //     $stockInTransit = StockInTransit::where('user_id', $userID)->where('route_id', $routeID)->first();
  
      //     if ($stockInTransit) {
      //       return response()->json( $stockInTransit);
      //     } else {
      //       return response()->json(['message' => 'No stock in transit found for this route and user.'], 404);
      //     }
      //   }
      // }

      $data = [
        "filteredInvoices" => $filteredInvoices,
        "currency" =>$currency,
        "decimalLength"=> $decimalLength
      ];
   
      return response()->json( $data );
    }

    public function mReportCompanyInvoices(Request $request)
    {
      $paymentMethods = config('constants.PAYMENT_METHODS');
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $data = json_decode($request->input('data'), true);
      $data['fromDate'] = trim($data['fromDate']);
      $data['toDate'] = trim($data['toDate']);
      $data['companyName'] = trim($data['companyName']);

      $fromDate = $data['fromDate'];
      $toDate = $data['toDate'];
      $companyName = $data['companyName'];

      if ($fromDate && $toDate) {
        $fromDate = \Carbon\Carbon::parse($fromDate)->startOfDay();
        $toDate = \Carbon\Carbon::parse($toDate)->endOfDay();
      } else {
        $fromDate = now()->toDateString();
        $toDate = now()->toDateString();
      }

      $filteredInvoices = Invoice::with('Customer', 'Sales.product')
        ->when($fromDate == $toDate, function ($query) use ($fromDate) {
          return $query->whereDate('created_at', $fromDate);
        })
        ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
          return $query->whereBetween('created_at', [$fromDate, $toDate]);
        })
        ->when($companyName != "", function ($query) use ($companyName) {
            $query->whereHas('Customer', function ($q) use ($companyName) {
                $q->where('company_name', $companyName);
            });
        })
       
        ->get();

      // foreach ($filteredInvoices as $invoice) {
      //   if ($invoice->routeID == $routeID) {
      //     $userID = $invoice->user_id;
      //     $stockInTransit = StockInTransit::where('user_id', $userID)->where('route_id', $routeID)->first();
  
      //     if ($stockInTransit) {
      //       return response()->json( $stockInTransit);
      //     } else {
      //       return response()->json(['message' => 'No stock in transit found for this route and user.'], 404);
      //     }
      //   }
      // }

      $data = [
        "filteredInvoices" => $filteredInvoices,
        "currency" =>$currency,
        "decimalLength"=> $decimalLength
      ];
   
      return response()->json( $data );
    }

    public function zReportPrintCompanyInvoices(Request $request)
    {
      $userID = Auth::id();
      $userRole = Auth::user()->role;
      $paymentMethods = config('constants.PAYMENT_METHODS');
      //print_r( $paymentMethods);
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $reportData = $request->input('reportData');
      $today = now()->toDateString();

      if ($reportData) {
        $fromDate = trim($reportData['fromDate']);
        $toDate = trim($reportData['toDate']);
        $companyName = trim($reportData['companyName']);
      } else {
        $fromDate = '';
        $toDate = '';
        $companyName = '';
      }

      if ($fromDate && $toDate) {
        $fromDate = \Carbon\Carbon::parse($fromDate)->startOfDay();
        $toDate = \Carbon\Carbon::parse($toDate)->endOfDay();
      } else {
        $fromDate = now()->startOfDay()->toDateString();
        $toDate = now()->endOfDay()->toDateString();
      }

      $expenseTypes = config('constants.EXPENSE_TYPES');
      

      $filteredInvoices = Invoice::with('Customer', 'Sales.product')
        ->where('print_status', '0')
        ->when($fromDate == $toDate, function ($query) use ($fromDate) {
          return $query->whereDate('created_at', $fromDate);
        })
        ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
          return $query->whereBetween('created_at', [$fromDate, $toDate]);
        })
        ->when($companyName != "", function ($query) use ($companyName) {
            $query->whereHas('Customer', function ($q) use ($companyName) {
                $q->where('company_name', $companyName);
            });
        })
       
        ->get();

        $invoiceIDList = $filteredInvoices->pluck('id');
        // $this->pr($filteredInvoices->pluck('payment_type', 'received_amt'));
        // $this->pr($filteredInvoices->pluck('payment_type', 'returned_amt'));

        $totalCashAmount = $filteredInvoices->where('payment_type', $paymentMethods[0])->sum(function ($invoice) {
          return $invoice->received_amt - $invoice->returned_amt;
        });
      
        $totalTransferAmount = $filteredInvoices->where('payment_type', $paymentMethods[1])->sum(function ($invoice) {
            return $invoice->received_amt - $invoice->returned_amt;
        });

        $totalCreditAmount = $filteredInvoices->where('payment_type', $paymentMethods[2])->sum(function ($invoice) {
            return $invoice->paid_amt - $invoice->returned_amt;
        });
        // echo $totalCashAmount;
        // echo $totalTransferAmount;
        // echo $totalCreditAmount;

        //echo $totalCreditAmount;

        if ($userRole == 'admin') {
          $loadedProducts = StockInTransit::select('product_id','start_quantity','quantity')
            ->when($fromDate == $toDate, function ($query) use ($fromDate) {
            return $query->whereDate('created_at', $fromDate);
            })
            ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
              return $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->get();

          $salesReturns = Returns::with('Product')
            ->when($fromDate == $toDate, function ($query) use ($fromDate) {
              return $query->whereDate('created_at', $fromDate);
            })
            ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
              return $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->get();
            // $this->pr($loadedProducts);exit;

            $expenses = Expense::when($fromDate == $toDate, function ($query) use ($fromDate) {
              return $query->whereDate('created_at', $fromDate);
            })
            ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
              return $query->whereBetween('created_at', [$fromDate, $toDate]);
            })->get();

        } else {
          $loadedProducts = StockInTransit::select('product_id','start_quantity','quantity')->where('user_id', $userID)->whereDate('created_at',$today)->get();
          $salesReturns = Returns::with('Product')->where('salesman_id', $userID)->whereDate('created_at',$today)->get();

          $expenses = Expense::where('user_id', $userID)->when($fromDate == $toDate, function ($query) use ($fromDate) {
            return $query->whereDate('created_at', $fromDate);
          })
          ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
            return $query->whereBetween('created_at', [$fromDate, $toDate]);
          })->get();
        }
   
      return view('report.z_report_print', compact('filteredInvoices', 'paymentMethods', 'expenseTypes','expenses','invoiceIDList','fromDate','toDate','currency','decimalLength','totalCashAmount','totalTransferAmount', 'totalCreditAmount','loadedProducts','salesReturns'));
    }

    public function mReportPrintCompanyInvoices(Request $request)
    {
      $userID = Auth::id();
      $userRole = Auth::user()->role;
      $paymentMethods = config('constants.PAYMENT_METHODS');
      //print_r( $paymentMethods);
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');
      $reportData = $request->input('reportData');
      $today = now()->toDateString();

      if ($reportData) {
        $fromDate = trim($reportData['fromDate']);
        $toDate = trim($reportData['toDate']);
        $companyName = trim($reportData['companyName']);
      } else {
        $fromDate = '';
        $toDate = '';
        $companyName = '';
      }

      if ($fromDate && $toDate) {
        $fromDate = \Carbon\Carbon::parse($fromDate)->startOfDay();
        $toDate = \Carbon\Carbon::parse($toDate)->endOfDay();
      } else {
        $fromDate = now()->startOfDay()->toDateString();
        $toDate = now()->endOfDay()->toDateString();
      }

      $expenseTypes = config('constants.EXPENSE_TYPES');
      

      $filteredInvoices = Invoice::with('Customer', 'Sales.product')
        ->when($fromDate == $toDate, function ($query) use ($fromDate) {
          return $query->whereDate('invoices.created_at', $fromDate);
        })
        ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
          return $query->whereBetween('invoices.created_at', [$fromDate, $toDate]);
        })
        ->when($companyName != "", function ($query) use ($companyName) {
            $query->whereHas('Customer', function ($q) use ($companyName) {
                $q->where('company_name', $companyName);
            });
        })
        ->join('customers', 'invoices.customer_id', '=', 'customers.id')
        ->orderBy('customers.company_name')
        ->orderBy('invoices.created_at')
        ->select('invoices.*')
        ->get();
        // $this->pr($filteredInvoices);
        // exit;

        $invoiceIDList = $filteredInvoices->pluck('id');

        // $totalCashAmount = $filteredInvoices->where('payment_type', $paymentMethods[0])->sum(function ($invoice) {
        //   return $invoice->received_amt - $invoice->returned_amt;
        // });

        $totalCashAmount = $filteredInvoices
          ->where('payment_type', $paymentMethods[0])
          ->flatMap(function ($invoice) {
              return $invoice->sales;
          })
          ->where('type', 'sales')
          ->sum('total_amount');
      
        // $totalTransferAmount = $filteredInvoices->where('payment_type', $paymentMethods[1])->sum(function ($invoice) {
        //     return $invoice->received_amt - $invoice->returned_amt;
        // });

        $totalTransferAmount = $filteredInvoices
          ->where('payment_type', $paymentMethods[1])
          ->flatMap(function ($invoice) {
              return $invoice->sales;
          })
          ->where('type', 'sales')
          ->sum('total_amount');

        // $totalCreditAmount = $filteredInvoices->where('payment_type', $paymentMethods[2])->sum(function ($invoice) {
        //     return $invoice->received_amt - $invoice->returned_amt;
        // });

        $totalCreditAmount = $filteredInvoices
          ->where('payment_type', $paymentMethods[2])
          ->flatMap(function ($invoice) {
              return $invoice->sales;
          })
          ->where('type', 'sales')
          ->sum('total_amount');

        $totalSaleQty = $filteredInvoices
          ->flatMap(function ($invoice) {
              return $invoice->sales;
          })
          ->where('type', 'sales')
          ->sum('qty');
        $totalReturnsQty = $filteredInvoices
          ->flatMap(function ($invoice) {
              return $invoice->sales;
          })
          ->where('type', 'returns')
          ->sum('qty');

        //echo $totalCreditAmount;

        if ($userRole == 'admin') {
          $loadedProducts = StockInTransit::select('product_id','start_quantity','quantity')
            ->when($fromDate == $toDate, function ($query) use ($fromDate) {
            return $query->whereDate('created_at', $fromDate);
            })
            ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
              return $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->get();

          $salesReturns = Returns::with('Product')
            ->when($fromDate == $toDate, function ($query) use ($fromDate) {
              return $query->whereDate('created_at', $fromDate);
            })
            ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
              return $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->get();
            // $this->pr($loadedProducts);exit;

            $expenses = Expense::when($fromDate == $toDate, function ($query) use ($fromDate) {
              return $query->whereDate('created_at', $fromDate);
            })
            ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
              return $query->whereBetween('created_at', [$fromDate, $toDate]);
            })->get();

        } else {
          $loadedProducts = StockInTransit::select('product_id','start_quantity','quantity')->where('user_id', $userID)->whereDate('created_at',$today)->get();
          $salesReturns = Returns::with('Product')->where('salesman_id', $userID)->whereDate('created_at',$today)->get();

          $expenses = Expense::where('user_id', $userID)->when($fromDate == $toDate, function ($query) use ($fromDate) {
            return $query->whereDate('created_at', $fromDate);
          })
          ->when($fromDate != $toDate, function ($query) use ($fromDate, $toDate) {
            return $query->whereBetween('created_at', [$fromDate, $toDate]);
          })->get();
        }
   
      return view('report.m_report_print', compact('filteredInvoices', 'totalSaleQty', 'totalReturnsQty', 'paymentMethods', 'expenseTypes','expenses','invoiceIDList','fromDate','toDate','currency','decimalLength','totalCashAmount','totalTransferAmount', 'totalCreditAmount','loadedProducts','salesReturns'));
    }

    public function zReportInvoiceUpdate(Request $request)
    {
      $invoiceIDList = $request->input('data');
      foreach ($invoiceIDList as $invoiceID) {
        $invoice = Invoice::findOrFail($invoiceID);
        $invoice->print_status = '1';
        $invoice->save();
      }
      return response()->json( [
        "message" => "success",
      ] );
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
