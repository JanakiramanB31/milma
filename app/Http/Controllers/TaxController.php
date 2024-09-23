<?php

namespace App\Http\Controllers;

use App\Category;
use App\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $taxes = Tax::all();
        
        return view('tax.index', compact('taxes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $tax = new Tax();
      $submitURL = route('tax.store');
      $editPage = false;
      return view('tax.create',compact('tax','submitURL','editPage'));
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
            'name' => 'required|unique:taxes|numeric',
        ]);

        $tax = new Tax();
        $tax->name = $request->name;
        $tax->slug = str_slug($request->name);
        $tax->status = 1;
        $tax->save();

        return redirect()->back()->with('message', 'Tax Created Successfully');
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
        $tax = Tax::findOrFail($id);
        $submitURL =route('tax.update', $tax->id) ;
        $editPage = true;
        return view('tax.edit', compact('tax','submitURL','editPage'));
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
            'name' => 'required|numeric',
        ]);

        $tax = Tax::findOrFail($id);
        $tax->name = $request->name;
        $tax->slug = str_slug($request->name);
        $tax->save();

        return redirect()->back()->with('message', 'Tax Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tax = Tax::find($id);
        $tax->delete();
        return redirect()->back();

    }
}
