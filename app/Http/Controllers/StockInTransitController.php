<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductSupplier;
use App\Route;
use App\StockInTransit;
use App\User;
use App\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
      $userID = Auth::id();
      $userRole = Auth::user()->role;
      $recordExists = StockInTransit::where('user_id',$userID)->whereDate('created_at', $today)->exists();
      if ($userRole == 'admin') {
        $stockInTransits = StockInTransit::with('Route', 'Product','Vehicle')->whereDate('created_at', $today)->get();
      } else {
        $stockInTransits = StockInTransit::with('Route', 'Product','Vehicle')->where('user_id',$userID)->whereDate('created_at', $today)->get();
      }
      $groupedStockInTransits = $stockInTransits->groupBy(function($item) {
        return $item->created_at->format('d-m-Y'); 
      })->map(function($group) {
        return $group->groupBy(function($item) {
          return $item->route->route_number . '-' . $item->vehicle->vehicle_number;
        });
      });
      return view('stockintransit.index', compact('groupedStockInTransits', 'recordExists','stockInTransits'));
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
      $users = User::where('role', 'sales')->get();
      $routes = Route::all();
      $vehicles = Vehicle::all();
      $products = Product::where('sit_status',1)->where('status',1)->get();
      $supplierProdQuantities = [];
      foreach ($products as $product) {
        $quantity = ProductSupplier::where('product_id', $product->id)->value('quantity');
        $supplierProdQuantities[$product->id] = $quantity;
      }
      $roles = User::all();
      $routeDisplay = 'block';
      $productDisplay = 'none';
      $submitURL = route('stockintransit.store');
      return view('stockintransit.create',compact('userID','userRole','users','routes','supplierProdQuantities','vehicles','products','roles','submitURL','routeDisplay','productDisplay'));
     }

     public function checkExistence(Request $request)
      {
        $request->validate([
          'route_id' => 'required|exists:routes,id', 
          'vehicle_id' => 'required|exists:vehicles,id'
        ]);
        $userID = Auth::id(); 
        $today = now()->toDateString();
        $exists = StockInTransit::where('route_id', $request->route_id)->where('vehicle_id', $request->vehicle_id)->where('user_id',$userID)->whereDate('created_at', $today)->exists();
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
      $userId = Auth::id();
      $userRole = Auth::user()->role;
      $productIDs = $request->product_id;
      $quantities= $request->quantity;
      foreach ($productIDs as $key => $productID) {
        $supplier = ProductSupplier::where('product_id', $productID)->first();
        if($supplier) {
          $existingQuantity = $supplier->quantity;

          if (isset($quantities[$key]) && !empty($quantities[$key])) {
            $stockInTransit = new StockInTransit();
            $stockInTransit->user_id = ($userRole == 'admin' ? $request->user_id : $userId);
            $stockInTransit->route_id = $request->route_id;
            $stockInTransit->vehicle_id = $request->vehicle_id;
            $stockInTransit->product_id = $productID;
            $stockInTransit->start_quantity = $quantities[$key];
            $stockInTransit->quantity = $quantities[$key];
            $stockInTransit->save();

            $supplier->quantity = $supplier->quantity - $quantities[$key];
            $supplier->save();
          }

        }
      }
      return redirect()->route('invoice.create')->with('message', 'Stock In Transit Details Added Successfully');

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
      $routes = Route::all();
      $vehicles = Vehicle::all();
      $products = Product::where('sit_status',1)->where('status',1)->get();
      $userID = Auth::id();
      $userRole = Auth::user()->role;
      $existUserID = $stockInTransit->user_id;
      $users = User::where('role', 'sales')->get(); 
      $today = now()->format('Y-m-d');
      $productIDsAndQuantities = StockInTransit::select('id', 'quantity', 'product_id')->where('route_id', $stockInTransit->route_id)->where('vehicle_id', $stockInTransit->vehicle_id)->where('user_id', $existUserID)->whereDate('created_at', $today)->get();    
      $stockInTransitIDs = $productIDsAndQuantities->pluck('id','product_id')->toArray();
      $productIDsAndQuantities = $productIDsAndQuantities->pluck('quantity','product_id')->toArray();
      $supplierProdQuantities = [];
      foreach ($products as $product) {
        $quantity = ProductSupplier::where('product_id', $product->id)->value('quantity');
        $supplierProdQuantities[$product->id] = $quantity;
      }
      if ($userRole == 'admin') {
        $routeDisplay = 'block';
        $productDisplay = 'none';
      } else {
        $routeDisplay = 'none';
        $productDisplay = 'block';
      }
      $submitURL = route('stockintransit.update',$stockInTransit->id);
      return view('stockintransit.edit',compact('stockInTransit','supplierProdQuantities','userID','users','routes','vehicles','products','submitURL', 'productIDsAndQuantities','routeDisplay','productDisplay','stockInTransitIDs'));
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
      $oldQuantities = $request->quantity;
      $newQuantities = $request->new_quantity;
     
      foreach ($productIDs as $key => $productID) {
        $supplier = ProductSupplier::where('product_id', $productID)->first();
        if ($stockIDs[$key]) {
          $stockInTransit = StockInTransit::find($stockIDs[$key]);
          $stockInTransit->user_id = $request->user_id;
          $quantity = $oldQuantities[$key] + $newQuantities[$key];
          if ($stockInTransit && isset($newQuantities[$key]) && !empty($newQuantities[$key])) {
            $stockInTransit->route_id = $request->route_id;
            $stockInTransit->vehicle_id = $request->vehicle_id;
            $stockInTransit->product_id = $productID;
            $stockInTransit->start_quantity =$quantity;
            $stockInTransit->quantity = $quantity;
            $stockInTransit->save();

            $supplier->quantity -= $newQuantities[$key];
            $supplier->save();
          }
        } else {
            if (isset($newQuantities[$key]) && !empty($newQuantities[$key]) ) {
              $stockInTransit = new StockInTransit(); 
              $stockInTransit->route_id = $request->route_id;
              $stockInTransit->vehicle_id = $request->vehicle_id;
              $stockInTransit->product_id = $productID;
              $stockInTransit->start_quantity = $oldQuantities[$key] + $newQuantities[$key];
              $stockInTransit->quantity = $oldQuantities[$key] + $newQuantities[$key];
              $stockInTransit->save();

              $supplier->quantity -= $newQuantities[$key];
              $supplier->save();
            }
        }
      }
      return redirect()->route('stockintransit.index')->with('message', 'Stock In Transit Details Updated Successfully');

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $today = now()->format('Y-m-d');
      $stockInTransit = StockInTransit::where('vehicle_id', $id)->whereDate('created_at', $today);
      $stockInTransit->delete();
      return redirect()->back();
    }
}
