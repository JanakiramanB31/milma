<?php

namespace App\Http\Controllers;

use App\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
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
      $prices = Price::all();
      $priceTypes = [
        ['id' => 1, 'name' => 'Cash'],
        ['id' => 2, 'name' => 'Card'],
        ['id' => 3, 'name' => 'Net Banking'],
      ];
      $priceCodes = [
        ['id' => 1, 'name' => '11'],
        ['id' => 2, 'name' => '22'],
      ];
      return view('price.index',compact('prices','priceTypes','priceCodes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $price = new Price();
      $priceTypes = [
        ['id' => 1, 'name' => 'Cash'],
        ['id' => 2, 'name' => 'Card'],
        ['id' => 3, 'name' => 'Net Banking'],
      ];
      $priceCodes = [
        ['id' => 1, 'name' => '11'],
        ['id' => 2, 'name' => '22'],
      ];
      $editPage = false;
      $submitURL = route('price.store');
        return view('price.create',compact('price','priceTypes','priceCodes','editPage','submitURL'));
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
        'price_type_parent_id'=>'required',
        'price_code_parent_id' => 'required',
      ]);

      $price = new Price();
      $price->price_type_parent_id = $request->price_type_parent_id;
      $price->price_code_parent_id = $request->price_code_parent_id;
      $price->save();

      return redirect()->back()->with('message', 'Price Details Added Successfully');

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
      $price = Price::findOrFail($id);
      $priceTypes = [
        ['id' => 1, 'name' => 'Cash'],
        ['id' => 2, 'name' => 'Card'],
        ['id' => 3, 'name' => 'Net Banking'],
      ];
      $priceCodes = [
        ['id' => 1, 'name' => '11'],
        ['id' => 2, 'name' => '22'],
      ];
      $editPage = true;
      $submitURL = route('price.update',$price->id);
        return view('price.edit',compact('price','priceTypes','priceCodes','editPage','submitURL'));
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
        'price_type_parent_id'=>'required',
        'price_code_parent_id' => 'required',
      ]);

      $price = Price::findOrFail($id);
      $price->price_type_parent_id = $request->price_type_parent_id;
      $price->price_code_parent_id = $request->price_code_parent_id;
      $price->save();

      return redirect()->back()->with('message', 'Price Details Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $price = Price::find($id);
      $price->delete();
      return redirect()->back();
    }
}
