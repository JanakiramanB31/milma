<?php

namespace App\Http\Controllers;

use App\Product;
use App\Route;
use App\StockInTransit;
use App\User;
use App\Vehicle;
use Illuminate\Http\Request;

class StockInTransitController extends Controller
{
  public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $today = now()->format('Y-m-d');
      $stockInTransits = StockInTransit::with('Route', 'Product','Vehicle')->whereDate('created_at', $today)->get();
      $groupedStockInTransits = $stockInTransits->groupBy(function($item) {
        return $item->created_at->format('d-m-Y'); 
      })->map(function($group) {
        return $group->groupBy(function($item) {
          return $item->route->route_number . '-' . $item->vehicle->vehicle_number;
        });
      });
      //exit;
      return view('stockintransit.index', compact('groupedStockInTransits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $routes = Route::all();
      $vehicles = Vehicle::all();
      $products = Product::all();
      $roles = User::all();
      $routeDisplay = 'block';
      $productDisplay = 'none';
      $submitURL = route('stockintransit.store');
      return view('stockintransit.create',compact('routes','vehicles','products','roles','submitURL','routeDisplay','productDisplay'));
     }

     public function checkExistence(Request $request)
      {
        $request->validate([
          'route_id' => 'required|exists:routes,id', 
          'vehicle_id' => 'required|exists:vehicles,id'
        ]);
        $today = now()->toDateString();
        $exists = StockInTransit::where('route_id', $request->route_id)->where('vehicle_id', $request->vehicle_id)->whereDate('created_at', $today)->exists();
        if ($exists) {
          $stockInTransitID = StockInTransit::where('route_id', $request->route_id)->where('vehicle_id', $request->vehicle_id)->whereDate('created_at', $today)->first();
          return response()->json(['error' => 'Record for today already exists.', "ID" => $stockInTransitID->id], 409);
        }
        return response()->json(['message' => 'No Data'], 200);
      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate([
        'route_id' => 'required',
        'vehicle_id'=>'required',
      ]);
      //$this->pr($request->all());
      //exit;
      $productIDs = $request->product_id;
      $quantities= $request->quantity;
      //exit;
      foreach ($productIDs as $key => $productID) {
        if (isset($quantities[$key]) && !empty($quantities[$key])) {
          $stockInTransit = new StockInTransit();
          $stockInTransit->user_id = $request->user_id;
          $stockInTransit->route_id = $request->route_id;
          $stockInTransit->vehicle_id = $request->vehicle_id;
          $stockInTransit->product_id = $productID;
          $stockInTransit->quantity = $quantities[$key];
          $stockInTransit->save();
        }
      }
      return redirect()->back()->with('message', 'Stock In Transit Details Added Successfully');
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
      $stockInTransitID = $id; 
      $stockInTransit = StockInTransit::with(['route', 'vehicle', 'product'])->findOrFail($id);
      //$this->pr($stockInTransit);
      $routes = Route::all();
      $vehicles = Vehicle::all();
      $products = Product::all();
      //echo $stockInTransit->route_id;
      //echo $stockInTransit->vehicle_id;
      $productIDsAndQuantities = StockInTransit::select('id', 'quantity', 'product_id')->where('route_id', $stockInTransit->route_id)->where('vehicle_id', $stockInTransit->vehicle_id)->get();    
      $stockInTransitIDs = $productIDsAndQuantities->pluck('id','product_id')->toArray();
      $productIDsAndQuantities = $productIDsAndQuantities->pluck('quantity','product_id')->toArray();
      //  $Quantities = $productIDsAndQuantities->pluck('', 'quantity')->toArray();
      //  $this->pr($stockInTransitIds);
      //  $this->pr($productIds);
      //  $this->pr($Quantities);
      //  $this->pr($productIds);
      // exit;
      //  $productIds = array_keys($productIDsAndQuantities);
      //  $Quantities = array_values($productIDsAndQuantities);
       //$this->pr($productIDsAndQuantities);
      
      // exit;
      $routeDisplay = 'none';
      $productDisplay = 'block';
      $submitURL = route('stockintransit.update',$stockInTransit->id);
      return view('stockintransit.edit',compact('stockInTransit','routes','vehicles','products','submitURL', 'productIDsAndQuantities','routeDisplay','productDisplay','stockInTransitIDs'));
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
      $stockIDs = $request->stock_in_transit_id;
      $productIDs = $request->product_id;
      $quantities = $request->quantity;
      //$this->pr($request->all());
      //exit;      
      foreach ($productIDs as $key => $productID) {
        if ($stockIDs[$key]) {
          $stockInTransit = StockInTransit::find($stockIDs[$key]);
          if ($stockInTransit && isset($quantities[$key]) && !empty($quantities[$key])) {
            $stockInTransit->quantity = $quantities[$key];
            $stockInTransit->save();
          }
        } else {
            if (isset($quantities[$key]) && !empty($quantities[$key])) {
              $stockInTransit = new StockInTransit(); 
              $stockInTransit->route_id = $request->route_id;
              $stockInTransit->vehicle_id = $request->vehicle_id;
              $stockInTransit->product_id = $productID;
              $stockInTransit->quantity = $quantities[$key];
              $stockInTransit->save();
            }
        }
      }
      return redirect()->back()->with('message', 'Stock In Transit Details Updated Successfully');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $stockInTransit = StockInTransit::find($id);
      $stockInTransit->delete();
      return redirect()->back();
    }
}