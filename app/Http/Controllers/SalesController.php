<?php


namespace App\Http\Controllers;

use App\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        $fromDate = now()->toDateString();
        $sales = Sales::where('type', 'sales')->whereDate('created_at', $fromDate)->get(); // Include products related to sales
        $currency = config('constants.CURRENCY_SYMBOL');
        $decimalLength = config('constants.DECIMAL_LENGTH');
        $salesWithCompanies = $sales->groupBy(function ($sale) {
            return $sale->Customer->company_name ;
        });

        $salesWithProducts = $sales->groupBy(function ($sale) {
          return $sale->Product->name ;
        });

        // $this->pr($sales);
        // exit;
        
        return view('sales.index', compact('sales','salesWithCompanies','salesWithProducts','currency','decimalLength'));
    }

    public function filterSalesData(Request $request)
    {
      $currency = config('constants.CURRENCY_SYMBOL');
      $decimalLength = config('constants.DECIMAL_LENGTH');

      $data = json_decode($request->input('data'), true);

      $data['fromDate'] = trim($data['fromDate']);
      $data['toDate'] = trim($data['toDate']);
      $data['companyName'] = trim($data['companyName']);
      $data['productName'] = trim($data['productName']);
      $fromDate = $data['fromDate'];
      $toDate = $data['toDate'];
      $companyName = $data['companyName'];
      $productName = $data['productName'];

      if ($fromDate && $toDate) {
        $fromDate = \Carbon\Carbon::parse($fromDate)->startOfDay();
        $toDate = \Carbon\Carbon::parse($toDate)->endOfDay();
      } else {
          $fromDate = now()->toDateString();
          $toDate = now()->toDateString();
      }

      $filteredSales =  Sales::with(['Customer', 'Product'])
        ->where('type', 'sales')
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
        ->when($productName != "", function ($query) use ($productName) {
          $query->whereHas('Product', function ($q) use ($productName) {
              $q->where('name', $productName);
          });
        })
        ->get();

        $data = [
          'currency'=> $currency,
          "decimalLength" => $decimalLength,
          'filteredSales'=> $filteredSales,
        ];

        return response()->json( $data );
    }
    
}
