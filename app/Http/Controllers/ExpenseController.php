<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Expense;
use App\Rate;
use App\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $expenses = Expense::all();
    $expenseTypes = config('constants.EXPENSE_TYPES');
    $currency = config('constants.CURRENCY_SYMBOL');
    $decimalLength = config('constants.DECIMAL_LENGTH');
    return view('expense.index', compact('expenses', 'expenseTypes', 'currency', 'decimalLength'));
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $expenseTypes = config('constants.EXPENSE_TYPES');
    $expense = new Expense();
    $submitURL = route('expense.store');
    $currency = config('constants.CURRENCY_SYMBOL');
    $decimalLength = config('constants.DECIMAL_LENGTH');
    $editPage =false;

    return view('expense.create',compact('expenseTypes', 'currency', 'decimalLength', 'expense', 'submitURL', 'editPage'));
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
      'expense_type_id' => 'required',
      'expense_amt' => 'required',
      'expense_date' => 'required|date|before_or_equal:today'
    ]);
      
    $userID = Auth::id();

    $expense = new Expense();
    $expense->user_id = $userID;
    $expense->expense_type_id = $request->expense_type_id;
    $expense->other_expense_details = $request->other_expense_details;
    $expense->expense_amt = $request->expense_amt;
    $expense->expense_date = $request->expense_date;
    $expense->description = $request->description;
    $expense->save();

    return redirect(route('expense.index'))->with('message', 'Expense added successfully');
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
    $expenseTypes = config('constants.EXPENSE_TYPES');
    $expense = Expense::findOrFail($id);
    $submitURL = route('expense.update', $expense->id);
    $currency = config('constants.CURRENCY_SYMBOL');
    $decimalLength = config('constants.DECIMAL_LENGTH');
    $editPage = true;

    return view('expense.edit',compact('expenseTypes', 'currency', 'decimalLength','expense', 'submitURL', 'editPage'));
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
      'expense_type_id' => 'required',
      'expense_amt' => 'required',
      'expense_date' => 'required|date|before_or_equal:today'
    ]);
      
    $userID = Auth::id();

    $expense = Expense::findOrFail($id);
    $expense->user_id = $userID;
    $expense->expense_type_id = $request->expense_type_id;
    $expense->other_expense_details = $request->other_expense_details;
    $expense->expense_amt = $request->expense_amt;
    $expense->expense_date = $request->expense_date;
    $expense->description = $request->description;
    $expense->save();

    return redirect(route('expense.index'))->with('message', 'Expense Updated successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
