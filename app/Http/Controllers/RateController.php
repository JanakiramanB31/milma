<?php

namespace App\Http\Controllers;

use App\Rate;
use Illuminate\Http\Request;

class RateController extends Controller
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

    public function index()
    {
        $rates = Rate::all();
        // echo '<pre>'; print_r($subcategories); echo '</pre>'; exit;
        return view('rate.index', compact('rates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('rate.create');
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
            'name' => 'required|min:3',
            'type' => 'required|min:2|unique:rates|regex:/^[a-zA-Z0-9]+$/',
            'description' => 'required'
        ]);


        $rate = new Rate();
        $rate->name = $request->name;
        $rate->type = $request->type;
        $rate->description = $request->description;
        $rate->save();
        return redirect()->back()->with('message', 'New rate has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function show(Rate $rate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rate = Rate::findOrFail($id);
        return view('rate.edit', compact('rate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required|min:2|unique:rates|regex:/^[a-zA-Z0-9]+$/',
            'description' => 'required'
        ]);

        $rate = Rate::findOrFail($id);
        $rate->name = $request->name;
        $rate->type = $request->type;
        $rate->description = $request->description;
        $rate->save();

        return redirect()->back()->with('message', 'Rate updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rate = Rate::find($id);
        $rate->delete();
        return redirect()->back();
    }
}
