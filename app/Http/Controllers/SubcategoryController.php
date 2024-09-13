<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
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
        $subcategories = Category::whereNotNull('parent_id')->get();
        // echo '<pre>'; print_r($subcategories); echo '</pre>'; exit;
        return view('subcategory.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $subcategory = new Category();
        $submitURL = route('subcategory.store');
        $editPage = false;
        return view('subcategory.create', compact('categories','subcategory','submitURL','editPage'));
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
            'name' => 'required|min:3|unique:categories|regex:/^[a-zA-Z ]+$/',
            'parent_id'=>'required'
        ]);
        // echo '<pre>'; print_r($request->all()); echo '</pre>'; exit;
        $subcategory = new Category();
        $subcategory->name = $request->name;
        $subcategory->slug = str_slug($request->name);
        $subcategory->parent_id = $request->parent_id;
        $subcategory->status = 1;
        $subcategory->save();

        return redirect()->back()->with('message', 'New subcategory has been added successfully!');
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
        $categories = Category::all();
        $subcategory = Category::findOrFail($id);
        $submitURL = route('subcategory.update', $subcategory->id);
        $editPage = true;
        return view('subcategory.edit', compact('subcategory', 'categories','submitURL','editPage'));
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
            'parent_id'=>'required'
        ]);

        $subcategory = Category::findOrFail($id);
        $subcategory->name = $request->name;
        $subcategory->slug = str_slug($request->name);
        $subcategory->parent_id = $request->parent_id;
        $subcategory->save();

        return redirect()->back()->with('message', 'Subcategory updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subcategory = Category::find($id);
        $subcategory->delete();
        return redirect()->back();

    }
}
