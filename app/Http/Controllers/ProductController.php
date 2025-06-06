<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Tax;
use App\Unit;
use App\Rate;
use App\ProductPrice;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $products = Product::where('status',1)->get();
        $currency = config('constants.CURRENCY_SYMBOL');
        $decimalLength = config('constants.DECIMAL_LENGTH');
        // $this->pr($additional->toArray());
        // // $this->pr($additional->products->toArray());
        // exit;
        $rates = Rate::all();
        return view('product.index', compact('products', 'rates','currency','decimalLength'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        // $suppliers =Supplier::all();
        $categories = Category::all();
        $currency = config('constants.CURRENCY_SYMBOL');
        $decimalLength = config('constants.DECIMAL_LENGTH');
        $taxes = Tax::all();
        $units = Unit::all();
        $rates = Rate::all();
        $stockTypes = config('constants.STOCK_TYPES');
        $product = new Product();
        $editPage = false;
        $submitURL = route('product.store');
        
        $productRateIds = array('0');
        $productPrices =array('0');
        //  echo '<pre>'; print_r($stockTypes); echo '</pre>'; exit;
        return view('product.create', compact('product','decimalLength', 'currency','categories','taxes','units', 'rates', 'stockTypes','editPage','submitURL', 'productRateIds', 'productPrices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      // $this->pr($request->all());
      // exit;
         $request->validate([
            'name' => 'required|min:3|unique:products',
            // 'brand_name' => 'required',
            // 'sku_code' => 'required',
            // 'barcode' => 'required',
            // 'model' => 'required|min:3',
            'category_id' => 'required',
            
            'unit_id' => 'required',
            
            'stock_type' => 'required',
            'status' => 'required',
            'sit_status' => 'required',
            'image' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tax_id' => 'required',
            // 'moq_number' => 'required|numeric|min:1',
            'base_rate' => 'required',
            'rate_id' => 'required',
            'sit_status'=> 'required',
            'status' => 'required'
         ]);

        $rateIds = [];
        foreach($request->rate_id as $key => $rate_id){
          // $this->pr($request->all());
          // exit;
          if (in_array($rate_id, $rateIds)) {
            return redirect()->back()->withErrors(['rate_id' => 'This rate type has been added multiple times.'])->withInput();
          }
            $rateIds[] = $rate_id;

        }


        $product = new Product();
        $product->name = $request->name;
        $product->brand_name = $request->brand_name;
        $product->sku_code = $request->sku_code;
        $product->barcode = $request->barcode;
        $product->stock_type = $request->stock_type;
        $product->status = $request->status;
        $product->sit_status = $request->sit_status;
        $product->model = $request->model;
        $product->category_id = $request->category_id;
        $product->unit_id = $request->unit_id;
        $product->tax_id = $request->tax_id;
        $product->moq_number = $request->moq_number;
        $product->base_rate = $request->base_rate;


        // if ($request->hasFile('image')){
        //     $imageName =request()->image->getClientOriginalName();
        //     request()->image->move(public_path('images/product/'), $imageName);
        //     $product->image = $imageName;
        // }

        if ($request->hasFile('image')) {
          // echo "working";
          // exit;
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();        
            $image->move(public_path('images/product/'), $imageName);
            $product->image = $imageName;
        }



        $product->save();
        
        foreach($rateIds as $key => $rate_id){
          if ($rate_id) {
            $product_price = new ProductPrice();
            $product_price->product_id = $product->id;
            $product_price->rate_id = $request->rate_id[$key];
            $product_price->price = $request->product_price[$key];
            $product_price->save();
          }
      }


        return redirect()->back()->with('message', 'New product has been added successfully');
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
        $productId = $id; // The specific product ID you want to retrieve
        $currency = config('constants.CURRENCY_SYMBOL');
        $decimalLength = config('constants.DECIMAL_LENGTH');
        $product =Product::findOrFail($id);
        // echo '<pre>'; print_r($product); echo '</pre>'; exit
        $categories = Category::all();
        $taxes = Tax::all();
        $units = Unit::all();
        $rates = Rate::all();
        $comRateIdAndPrices = ProductPrice::where('product_id', $productId)->pluck('price','rate_id')->toArray();
        $productRateIds = array_keys($comRateIdAndPrices);
        $productPrices = array_values($comRateIdAndPrices);
        // $this->pr($comRateIdAndPrices);
        // $this->pr($productRateIds);
        // $this->pr($productPrices);
        // exit;
        $stockTypes = config('constants.STOCK_TYPES');
        $editPage = true;
        $submitURL = route('product.update',$product->id);
        return view('product.edit', compact('decimalLength', 'currency','categories','taxes','units','product', 'rates', 'stockTypes','editPage','submitURL', 'productRateIds', 'productPrices' ));
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
      // $this->pr($request->all());
      //exit;
      $request->validate([
        'name' => 'required|min:3|unique:products,name,' . $id,
        // 'brand_name' => 'required',
        // 'sku_code' => 'required',
        // 'barcode' => 'required',
        // 'model' => 'required|min:3',
        'category_id' => 'required',
        'unit_id' => 'required',
        'rate_id' => 'required',
        'stock_type' => 'required',
        'status' => 'required',
        'sit_status' => 'required',
        'image' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'tax_id' => 'required',
        // 'moq_number' => 'required|numeric|min:1',
        'base_rate' => 'required'
      ]);
      // $this->pr($request->all());
      // exit;
      $product = Product::find($id);
      // $this->pr($product);
      // exit;
      $product->name = $request->name;
      $product->brand_name = $request->brand_name;
      $product->sku_code = $request->sku_code;
      $product->barcode = $request->barcode;
      $product->stock_type = $request->stock_type;
      $product->status = $request->status;
      $product->sit_status = $request->sit_status;
      $product->model = $request->model;
      $product->category_id = $request->category_id;
      $product->unit_id = $request->unit_id;
      $product->tax_id = $request->tax_id;
      $product->moq_number = $request->moq_number;
      $product->base_rate = $request->base_rate;
      // $this->pr($product);
      // exit;
      if ($request->hasFile('image')) {
        // Delete the existing image file if it exists
        $existingImagePath = public_path("images/product/{$product->image}");
        if (file_exists($existingImagePath) && is_file($existingImagePath)) {
          unlink($existingImagePath);
        }
    
        $imageName = time() . '_' . uniqid() . '.' . $request->image->getClientOriginalExtension();    
        $request->image->move(public_path('images/product/'), $imageName);
        $product->image = $imageName;
      }
      $product->save();

      $rateIds =$request->rate_id;
      // $this->pr($rateIds);
      // $this->pr($request->product_price);
      // exit;

      foreach($rateIds as $key => $rate_id){
        if ($rate_id && $request->product_price[$key]) {
          $product_price = ProductPrice::where('product_id', $id)->where('rate_id', $rate_id)->first();
          // $this->pr($product_price);
          // exit;
          if (!$product_price) {
            // Create a new ProductPrice entry if it doesn't exist
            $product_price = new ProductPrice();
            $product_price->product_id = $id;
            $product_price->rate_id = $rate_id;
        }
          $product_price->price = $request->product_price[$key];
          $product_price->save();

          $existingRateIds = ProductPrice::where('product_id', $id)->pluck('rate_id')->toArray();
          $rateIdsToDelete = array_diff($existingRateIds, array_filter($rateIds));
          ProductPrice::where('product_id', $id)->whereIn('rate_id', $rateIdsToDelete)->delete();
        }
      }


      return redirect()->back()->with('message', 'Product has been updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

      $product = Product::findOrFail($id);

      if ($product) {
        $product->delete();
      }
      return redirect()->back();
    }
}