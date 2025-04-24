<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Supplier;
use App\Rate;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomerType;
use App\Product;
use App\ProductPrice;
use App\Route;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }


    public function index()
    {
      $userID = Auth::id();
      $userRole = Auth::user()->role;
      $today = now()->toDateString();
      if ($userRole == 'admin') {
        $customers = Customer::where('status',1)->get();
      } else {
        $customers = Customer::where('user_id', $userID)->where('status',1)->get();
      }
      foreach ($customers as $customer) {
        $customer->created_today = $customer->created_at->isToday();
      }
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

      return view('customer.index', compact('customers','userRole','customerTypes','saleTypes','saleTypes'));
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
      $routes = Route::all();
      $customer = new Customer();
      $products = Product::where('status', 1)->get();
      $submitURL = route('customer.store');
      $editPage =false;
      return view('customer.create',compact('customerTypes','products','routes','saleTypes','rates','customer','submitURL','editPage'));
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
        'name' => 'required|regex:/^[a-zA-Z ]+$/',

        'address' => 'required',

        // 'mobile' => 'required|unique:customers|digits:11',
        // 'email' => 'required|email|unique:customers',

        'company_name' => 'required',
        // 'contact_person' => 'required|min:3',
        'post_code' => 'required',

        'customer_type_parent_id' =>'required',
        'rate_id' => 'required',
        // 'route_id' => 'required',
        // 'status' => 'required',
      ]);
        
      $userID = Auth::id();

      $customer = new Customer();
      $customer->name = $request->name;
      $customer->address = $request->address;
      $customer->mobile = $request->mobile;
      $customer->email = $request->email;
      $customer->previous_balance = $request->previous_balance ?? 0;
      $customer->company_name = $request->company_name;
      $customer->contact_person = $request->contact_person;
      $customer->post_code = $request->post_code;
      $customer->customer_type_parent_id = $request->customer_type_parent_id;
      $customer->rate_id = $request->rate_id;
      $customer->route_id = $request->route_id;
      $customer->user_id = $userID;
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

    public function fetchProductRateIDs(Request $request, $id)
    {
      $productPrices = ProductPrice::with('rate')->where('product_id', $id)->get();

      $rateDetails = [];
      foreach ($productPrices as $productPrice) {
        if ($productPrice->rate) {
          $rateDetails[] = [
            'id' => $productPrice->rate->id,
            'name' => $productPrice->rate->name,
            'price' => $productPrice->price,
            'type' => $productPrice->rate->type,
          ];
        }
      }

      return response()->json($rateDetails);
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
      $routes = Route::all();
      $customer = Customer::findOrFail($id);
      $submitURL = route('customer.update', $customer->id);
      $products = Product::where('status', 1)->get();
      $editPage =true;
      
      return view('customer.edit', compact('customer','routes', 'products','customerTypes','saleTypes','rates','submitURL','editPage'));
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
        'name' => 'required|regex:/^[a-zA-Z ]+$/',
        'address' => 'required',
        // 'mobile' => 'required|min:3|unique:customers,name,' . $id . '|digits:11',
        'company_name' => 'required',
        // 'contact_person' => 'required|min:3',
        'post_code' => 'required',
        'customer_type_parent_id' =>'required',
        'rate_id' => 'required',
        // 'route_id' => 'required',
        // 'status' => 'required',
      ]);

      $userID = Auth::id();

      $customer = Customer::findOrFail($id);
      $customer->name = $request->name;
      $customer->address = $request->address;
      $customer->mobile = $request->mobile;
      $customer->previous_balance = $request->previous_balance;
      $customer->company_name = $request->company_name;
      $customer->contact_person = $request->contact_person;
      $customer->post_code = $request->post_code;
      $customer->customer_type_parent_id = $request->customer_type_parent_id;
      $customer->rate_id = $request->rate_id;
      $customer->route_id = $request->route_id;
      $customer->user_id = $userID;
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
