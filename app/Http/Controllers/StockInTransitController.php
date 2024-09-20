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
        return view('stockintransit.index',compact('stockintransits'));

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
       $submitURL = route('stockintransit.store');
        return view('stockintransit.create',compact('stockintransit','routes','vehicles','products','editPage','submitURL'));
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
        'product_id' => 'required',
        'quantity' => 'required'
      ]);
      
      $stockintransit = new StockInTransit(); 
      $stockintransit->route_id = $request->route_id;
      $stockintransit->vehicle_id = $request->vehicle_id;
      $stockintransit->product_id = $request->product_id;
      $stockintransit->quantity = $request->quantity;
      $stockintransit->save();
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
      $stockintransit = StockInTransit::with(['route', 'vehicle', 'product'])->findOrFail($id);
      $routes = Route::all();
       $vehicles = Vehicle::all();
       $products = Product::all();
      $editPage = true;
      $submitURL = route('stockintransit.update',$stockintransit->id);
      return view('stockintransit.edit',compact('stockintransit','routes','vehicles','products','editPage','submitURL'));
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
      $stockintransit = StockInTransit::find($id);
      $stockintransit->delete();
      return redirect()->back();
    }
}
