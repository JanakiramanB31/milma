<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
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
       $companies = Company::all();
       return view('company.index',compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $company = new Company();
      $editPage = false;
      $submitURL = route('company.store');
      return view('company.create',compact('company','editPage','submitURL'));
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
        'company_name'=>'required|min:5',
        'address_1' => 'required|min:5',
        'address_2' => 'required|min:5',
        'city' => 'required|min:3',
        'post_code' => 'required|min:3|max:6'
      ]);

      $company = new Company();
      $company->company_name = $request->company_name;
      $company->address_1 = $request->address_1;
      $company->address_2 = $request->address_2;
      $company->city = $request->city;
      $company->post_code = $request->post_code;
      $company->save();

      return redirect()->back()->with('message', 'Company Details Added Successfully');
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
      $company = Company::findOrFail($id);
      $editPage = true;
      $submitURL = route('company.update',$company->id);
        return view('company.edit',compact('company','editPage','submitURL'));
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
        'company_name'=>'required|min:5',
        'address_1' => 'required|min:5',
        'address_2' => 'required|min:5',
        'city' => 'required|min:3',
        'post_code' => 'required|min:3|max:6'
      ]);

      $company =  Company::findOrFail($id);
      $company->company_name = $request->company_name;
      $company->address_1 = $request->address_1;
      $company->address_2 = $request->address_2;
      $company->city = $request->city;
      $company->post_code = $request->post_code;
      $company->save();

      return redirect()->back()->with('message', 'Company Details Updated Successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $company =  Company::findOrFail($id);
      $company->delete();
      return redirect()->back();
    }
}
