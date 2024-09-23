<?php

namespace App\Http\Controllers;

use App\Product;
use App\Route;
use App\StockInTransit;
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
        $stockintransits = StockInTransit::with('Route', 'Product','Vehicle')->get();
        $groupedStockInTransits = $stockintransits->groupBy(function($item) {
          return $item->created_at->format('d-m-Y'); 
        })->map(function($group) {
          return $group->groupBy(function($item) {
            return $item->route->route_number . '-' . $item->vehicle->vehicle_number; // Group by route and vehicle
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
       $stockintransit = new StockInTransit(); 
       $routes = Route::all();
       $vehicles = Vehicle::all();
       $products = Product::all();
       $editPage = false;
       $routeDisplay = 'block';
       $productDisplay = 'none';
       $productIDsAndQuantities = array();
       $submitURL = route('stockintransit.store');
        return view('stockintransit.create',compact('stockintransit','routes','vehicles','products','editPage','submitURL', 'productIDsAndQuantities','routeDisplay','productDisplay'));
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
          return response()->json(['error' => 'Record for today already exists.'], 409);
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
          $stockintransit = new StockInTransit(); 
          $stockintransit->route_id = $request->route_id;
          $stockintransit->vehicle_id = $request->vehicle_id;
          $stockintransit->product_id = $productID;
          $stockintransit->quantity = $quantities[$key];
          $stockintransit->save();
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
      $stockintransitId = $id; 
      $stockintransit = StockInTransit::with(['route', 'vehicle', 'product'])->findOrFail($id);
      //$this->pr($stockintransit);
      $routes = Route::all();
       $vehicles = Vehicle::all();
       $products = Product::all();
       //echo $stockintransit->route_id;
       //echo $stockintransit->vehicle_id;
       $productIDsAndQuantities = StockInTransit::where('route_id', $stockintransit->route_id)->where('vehicle_id', $stockintransit->vehicle_id)->pluck('quantity', 'product_id')->toArray();    
       $productIds = array_keys($productIDsAndQuantities);
       $Quantities = array_values($productIDsAndQuantities);
       //$this->pr($productIDsAndQuantities);
      
      // exit;
      $routeDisplay = 'none';
      $productDisplay = 'block';
      $editPage = true;
      $submitURL = route('stockintransit.update',$stockintransit->id);
      return view('stockintransit.edit',compact('stockintransit','routes','vehicles','products','editPage','submitURL','productIds','Quantities', 'productIDsAndQuantities','routeDisplay','productDisplay'));
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
      $productIDs = $request->product_id;
      $quantities= $request->quantity;
      foreach ($productIDs as $key => $productID) {
        if (isset($quantities[$key]) && !empty($quantities[$key])) {
          $stockintransit =StockInTransit::findOrFail($id);
          $stockintransit->route_id = $request->route_id;
          $stockintransit->vehicle_id = $request->vehicle_id;
          $stockintransit->product_id = $productID;
          $stockintransit->quantity = $quantities[$key];
          $stockintransit->save();
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
      $stockintransit = StockInTransit::find($id);
      $stockintransit->delete();
      return redirect()->back();
    }
}
