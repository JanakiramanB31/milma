<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Supplier;
use App\Rate;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomerType;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $customers = Customer::where('status',1)->get();
        $customerTypes = [
          ['id' => 1, 'name' => 'WholeSale'],
          ['id' => 2, 'name' => 'Retailer'],
        ];
        $saleTypes = [
          ['id' => 1, 'name' => 'Regular'],
          ['id' => 2, 'name' => 'Discount'],
          ['id' => 3, 'name' => 'Special Price'],
        ];
        $rates = Rate::all();
        
        return view('customer.index', compact('customers','customerTypes','saleTypes','saleTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $customerTypes = [
        ['id' => 1, 'name' => 'WholeSale'],
        ['id' => 2, 'name' => 'Retailer'],
      ];
      $saleTypes = [
        ['id' => 1, 'name' => 'Regular'],
        ['id' => 2, 'name' => 'Discount'],
        ['id' => 3, 'name' => 'Special Price'],
      ];
      $rates = Rate::all();

      $customer = new Customer();
      $submitURL = route('customer.store');
      $createPage = true;
      $editPage =false;
        return view('customer.create',compact('customerTypes','saleTypes','rates','customer','submitURL','createPage'));
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
            'name' => 'required|min:3|unique:customers|regex:/^[a-zA-Z ]+$/',
            'address' => 'required|min:3',
            'mobile' => 'required|min:3|digits:11',
            'address' => 'required|min:3',
            'email' => 'required|email|unique:customers',
            'details' => 'required|min:3',
            'previous_balance' => 'required',
            'company_name' => 'required|min:3',
            'contact_person' => 'required|min:3',
            'post_code' => 'required|min:3|max:6',
            'customer_type_parent_id' =>'required',
            'sale_type_parent_id' =>'required',
            'rate_id' => 'required',
            'status' => 'required',

        ]);

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->mobile = $request->mobile;
        $customer->email = $request->email;
        $customer->details = $request->details;
        $customer->previous_balance = $request->previous_balance;
        $customer->company_name = $request->company_name;
        $customer->contact_person = $request->contact_person;
        $customer->post_code = $request->post_code;
        $customer->customer_type_parent_id = $request->customer_type_parent_id;
        $customer->sale_type_parent_id = $request->sale_type_parent_id;
        $customer->rate_id = $request->rate_id;
        $customer->status = $request->status;
        $customer->save();

        return redirect()->back()->with('message', 'Customer added successfully');
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
      $customerTypes = [
        ['id' => 1, 'name' => 'WholeSale'],
        ['id' => 2, 'name' => 'Retailer'],
      ];
      $saleTypes = [
        ['id' => 1, 'name' => 'Regular'],
        ['id' => 2, 'name' => 'Discount'],
        ['id' => 3, 'name' => 'Special Price'],
      ];
      $rates = Rate::all();
        $customer = Customer::findOrFail($id);
        $submitURL = route('customer.update', $customer->id);
        $createPage = false;
        
        return view('customer.edit', compact('customer','customerTypes','saleTypes','rates','submitURL','createPage'));
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
            'name' => 'required|min:3|regex:/^[a-zA-Z ]+$/',
            'address' => 'required|min:3',
            'mobile' => 'required|min:3|digits:11',
            'details' => 'required|min:3',
            'previous_balance' => 'min:3',
            'company_name' => 'required|min:3',
            'contact_person' => 'required|min:3',
            'post_code' => 'required|min:3|max:6',
            'customer_type_parent_id' =>'required',
            'sale_type_parent_id' =>'required',
            'rate_id' => 'required',
            'status' => 'required',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->mobile = $request->mobile;
        $customer->details = $request->details;
        $customer->previous_balance = $request->previous_balance;
        $customer->company_name = $request->company_name;
        $customer->contact_person = $request->contact_person;
        $customer->post_code = $request->post_code;
        $customer->customer_type_parent_id = $request->customer_type_parent_id;
        $customer->sale_type_parent_id = $request->sale_type_parent_id;
        $customer->rate_id = $request->rate_id;
        $customer->status = $request->status;
        $customer->save();

        return redirect()->back()->withInput()->with('message', 'Customer Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        $customer->delete();
        return redirect()->back();

    }
}
