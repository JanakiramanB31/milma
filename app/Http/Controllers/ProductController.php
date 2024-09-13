<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\ProductSupplier;
use App\Supplier;
use App\Tax;
use App\Unit;
use App\Rate;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $products = Product::all();
        $additional = ProductSupplier::all();
        return view('product.index', compact('products','additional'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // phpinfo();
        // exit;
        $suppliers =Supplier::all();
        $categories = Category::all();
        $taxes = Tax::all();
        $units = Unit::all();
        $rates = Rate::all();
        $stockTypes = config('constants.STOCK_TYPES');
        //  echo '<pre>'; print_r($stockTypes); echo '</pre>'; exit;
        return view('product.create', compact('categories','taxes','units','suppliers', 'rates', 'stockTypes'));
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
            'name' => 'required|min:3|unique:products|regex:/^[a-zA-Z ]+$/',
            'brand_name' => 'required',
            'sku_code' => 'required',
            'barcode' => 'required',
            'model' => 'required|min:3',
            'category_id' => 'required',
            'sales_price' => 'required',
            'unit_id' => 'required',
            'rate_id' => 'required',
            'stock_type' => 'required',
            'status' => 'required',
            'sit_status' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tax_id' => 'required',
            'moq_number' => 'required|numeric|min:1',
        ]);


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
        $product->sales_price = $request->sales_price;
        $product->unit_id = $request->unit_id;
        $product->rate_id = $request->rate_id;
        $product->tax_id = $request->tax_id;
        $product->moq_number = $request->moq_numberzswej7;


        // if ($request->hasFile('image')){
        //     $imageName =request()->image->getClientOriginalName();
        //     request()->image->move(public_path('images/product/'), $imageName);
        //     $product->image = $imageName;
        // }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();        
            $image->move(public_path('images/product/'), $imageName);
            $product->image = $imageName;
        }



        $product->save();

        foreach($request->supplier_id as $key => $supplier_id){
            $supplier = new ProductSupplier();
            $supplier->product_id = $product->id;
            $supplier->supplier_id = $request->supplier_id[$key];
            $supplier->price = $request->supplier_price[$key];
            $supplier->save();
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
        $productId = $id; // The specific product ID you want to retrieve associated ProductSupplier record for
        $additional = ProductSupplier::where('product_id', $productId)->first();

        $product =Product::findOrFail($id);
        // echo '<pre>'; print_r($product); echo '</pre>'; exit;
        $suppliers =Supplier::all();
        $categories = Category::all();
        $taxes = Tax::all();
        $units = Unit::all();
        $rates = Rate::all();
        $stockTypes = config('constants.STOCK_TYPES');
        return view('product.edit', compact('additional','suppliers','categories','taxes','units','product', 'rates', 'stockTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'name' => 'required|min:3|unique:products|regex:/^[a-zA-Z ]+$/',
    //         'serial_number' => 'required',
    //         'model' => 'required|min:3',
    //         'category_id' => 'required',
    //         'sales_price' => 'required',
    //         'unit_id' => 'required',
    //         'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'tax_id' => 'required',

    //     ]);


    //     $product = new Product();
    //     $product->name = $request->name;
    //     $product->serial_number = $request->serial_number;
    //     $product->model = $request->model;
    //     $product->category_id = $request->category_id;
    //     $product->sales_price = $request->sales_price;
    //     $product->unit_id = $request->unit_id;
    //     $product->tax_id = $request->tax_id;


    //     if ($request->hasFile('image')){
    //         $image_path ="images/product/".$product->image;
    //         if (file_exists($image_path)){
    //             unlink($image_path);
    //         }
    //         $imageName =request()->image->getClientOriginalName();
    //         request()->image->move(public_path('images/product/'), $imageName);
    //         $product->image = $imageName;
    //     }



    //     $product->save();

    //     foreach($request->supplier_id as $key => $supplier_id){
    //         $supplier = new ProductSupplier();
    //         $supplier->product_id = $product->id;
    //         $supplier->supplier_id = $request->supplier_id[$key];
    //         $supplier->price = $request->supplier_price[$key];
    //         $supplier->save();
    //     }
    //     return redirect()->back()->with('message', 'Product Updated Successfully');
    // }

    public function update(Request $request, $id)
    {
    // $request->validate([
    //     'name' => 'required|min:3|unique:products,name,' . $id . '|regex:/^[a-zA-Z ]+$/',
    //     'serial_number' => 'required',
    //     'model' => 'required|min:3',
    //     'category_id' => 'required',
    //     'sales_price' => 'required',
    //     'unit_id' => 'required',
    //     'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     'tax_id' => 'required',
    //     'supplier_id.*' => 'required|exists:suppliers,id',
    //     'supplier_price.*' => 'required|numeric|min:0',
    // ]);
    // echo '<pre>'; print_r($request->all()); '</pre>'; exit;

        $request->validate([
            'name' => 'required|min:3|unique:products,name,' . $id . '|regex:/^[a-zA-Z ]+$/',
            'brand_name' => 'required',
            'sku_code' => 'required',
            'barcode' => 'required',
            'model' => 'required|min:3',
            'category_id' => 'required',
            'sales_price' => 'required',
            'unit_id' => 'required',
            'rate_id' => 'required',
            'stock_type' => 'required',
            'status' => 'required',
            'sit_status' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tax_id' => 'required',
            'supplier_id.*' => 'required|exists:suppliers,id',
            'supplier_price.*' => 'required|numeric|min:0',
            'moq_number' => 'required|numeric|min:1',
        ]);
        
        $product = Product::find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }

        // $product->name = $request->name;
        // $product->serial_number = $request->serial_number;
        // $product->model = $request->model;
        // $product->category_id = $request->category_id;
        // $product->sales_price = $request->sales_price;
        // $product->unit_id = $request->unit_id;
        // $product->tax_id = $request->tax_id;

        $product->name = $request->name;
        $product->brand_name = $request->brand_name;
        $product->sku_code = $request->sku_code;
        $product->barcode = $request->barcode;
        $product->stock_type = $request->stock_type;
        $product->status = $request->status;
        $product->sit_status = $request->sit_status;
        $product->model = $request->model;
        $product->category_id = $request->category_id;
        $product->sales_price = $request->sales_price;
        $product->unit_id = $request->unit_id;
        $product->rate_id = $request->rate_id;
        $product->tax_id = $request->tax_id;
        $product->moq_number = $request->moq_number;

    // if ($request->hasFile('image')) {
    //     $existingImagePath = public_path("images/product/{$product->image}");
    //     if (file_exists($existingImagePath) && is_file($existingImagePath)) {
    //         unlink($existingImagePath); // Delete the existing image file
    //     }

    //     $imageName = $request->image->getClientOriginalName();
    //     $request->image->move(public_path('images/product/'), $imageName);
    //     $product->image = $imageName;
    // }

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

            // Update or create product suppliers
    foreach ($request->supplier_id as $key => $supplier_id) {
        $supplier = ProductSupplier::where('product_id', $product->id)
            ->where('supplier_id', $supplier_id)
            ->first();

        if (!$supplier) {
            $supplier = new ProductSupplier();
            $supplier->product_id = $product->id;
            $supplier->supplier_id = $supplier_id;
        }

        $supplier->price = $request->supplier_price[$key];
        $supplier->save();
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
        $product = Product::find($id);
        $product->delete();
        return redirect()->back();

    }
}
