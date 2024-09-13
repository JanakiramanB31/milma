<?php

namespace App\Http\Controllers;

use App\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
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
      $vehicles = Vehicle::all();
      $vehicleTypes = [
        ['id' => 1, 'name' => 'Van'],
        ['id' => 2, 'name' => 'Car'],
      ];
      $fuelTypes = [
        ['id' => 1, 'name' => 'Petrol'],
        ['id' => 2, 'name' => 'Diesel'],
        ['id' => 3, 'name' => 'Electric'],
      ];
      $types = [
        ['id' => 1, 'name' => 'Vito'],
        ['id' => 2, 'name' => 'Panel'],
        ['id' => 3, 'name' => 'Van'],
      ];
        return view('vehicle.index',compact('vehicles','vehicleTypes','fuelTypes','types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $vehicle = new Vehicle();
      $vehicleTypes = [
        ['id' => 1, 'name' => 'Van'],
        ['id' => 2, 'name' => 'Car'],
      ];
      $FuelTypes = [
        ['id' => 1, 'name' => 'Petrol'],
        ['id' => 2, 'name' => 'Diesel'],
        ['id' => 3, 'name' => 'Electric'],
      ];
      $Types = [
        ['id' => 1, 'name' => 'Vito'],
        ['id' => 2, 'name' => 'Panel'],
        ['id' => 3, 'name' => 'Van'],
      ];
      $editPage = false;
      $submitURL = route('vehicle.store');
        return view('vehicle.create',compact('vehicle','vehicleTypes','FuelTypes','Types','editPage','submitURL'));
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
          'vehicle_number'=>'required|min:5|unique:vehicles',
          'make' => 'required|min:3',
          'model' => 'required|min:3',
          'vehicle_type_parent_id' => 'required',
          'fuel_type_parent_id' => 'required',
          'type_parent_id' => 'required',
        ]);

        $vehicle = new Vehicle();
        $vehicle->vehicle_number = $request->vehicle_number;
        $vehicle->make = $request->make;
        $vehicle->model = $request->model;
        $vehicle->vehicle_type_parent_id = $request->vehicle_type_parent_id;
        $vehicle->fuel_type_parent_id = $request->fuel_type_parent_id;
        $vehicle->type_parent_id = $request->type_parent_id;
        $vehicle->save();

        return redirect()->back()->with('message', 'Vehicle Details Added Successfully');

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
      $vehicle = Vehicle::findOrFail($id);
      $vehicleTypes = [
        ['id' => 1, 'name' => 'Van'],
        ['id' => 2, 'name' => 'Car'],
      ];
      $FuelTypes = [
        ['id' => 1, 'name' => 'Petrol'],
        ['id' => 2, 'name' => 'Diesel'],
        ['id' => 3, 'name' => 'Electric'],
      ];
      $Types = [
        ['id' => 1, 'name' => 'Vito'],
        ['id' => 2, 'name' => 'Panel'],
        ['id' => 3, 'name' => 'Van'],
      ];
      $editPage = true;
      $submitURL = route('vehicle.update',$vehicle->id);
        return view('vehicle.edit',compact('vehicle','vehicleTypes','FuelTypes','Types','editPage','submitURL'));
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
        'vehicle_number'=>'required|min:5|unique:vehicles',
        'make' => 'required|min:3',
        'model' => 'required|min:3',
        'vehicle_type_parent_id' => 'required',
        'fuel_type_parent_id' => 'required',
        'type_parent_id' => 'required',
      ]);

      $vehicle = Vehicle::findOrFail($id);
      $vehicle->vehicle_number = $request->vehicle_number;
      $vehicle->make = $request->make;
      $vehicle->model = $request->model;
      $vehicle->vehicle_type_parent_id = $request->vehicle_type_parent_id;
      $vehicle->fuel_type_parent_id = $request->fuel_type_parent_id;
      $vehicle->type_parent_id = $request->type_parent_id;
      $vehicle->save();

      return redirect()->back()->with('message', 'Vehicle Details Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $vehicle = Vehicle::find($id);
      $vehicle->delete();
      return redirect()->back();

    }
}
