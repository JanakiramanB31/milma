<?php

namespace App\Http\Controllers;
use App\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
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
        $routes = Route::all();
        return view('route.index',compact('routes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $route = new Route();
      $editPage = false;
      $submitURL = route('route.store');
        return view('route.create',compact('route','editPage','submitURL'));
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
        'name'=>'required|min:5',
        'route_number' => 'required|min:3',
      ]);

      $route = new Route();
      $route->name = $request->name;
      $route->route_number = $request->route_number;
      $route->save();

      return redirect()->back()->with('message', 'Route Details Added Successfully');

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
      $route = Route::findOrFail($id);
      $editPage = true;
      $submitURL = route('route.update',$route->id);
        return view('route.edit',compact('route','editPage','submitURL'));
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
        'name'=>'required|min:5',
        'route_number' => 'required|min:3',
      ]);

      $route = Route::findOrFail($id);
      $route->name = $request->name;
      $route->route_number = $request->route_number;
      $route->save();

      return redirect()->back()->with('message', 'Route Details Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $route = Route::find($id);
      $route->delete();
      return redirect()->back();
    }
}
