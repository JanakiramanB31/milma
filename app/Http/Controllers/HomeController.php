<?php

namespace App\Http\Controllers;

use App\Customer;
use App\User;
use App\Sale;
use App\Product;
use App\Supplier;
use App\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function index()
    // {
    //     return view('home');
    // }

    public function index()
{
    $totalProducts = Product::count();
    $totalSales = Sale::count();
    $totalSuppliers = Supplier::count();
    $totalInvoices = Invoice::count();
    $totalCustomers = Customer::count();

    // Fetch monthly sales data from the sales table
    $monthlySales = Sale::selectRaw('SUM(total_amount) as total_amount, MONTH(created_at) as month')
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->get();


    $formattedMonthlySales = [];
    foreach ($monthlySales as $sale) {
        $formattedMonthlySales[] = [
            'month' => \DateTime::createFromFormat('!m', $sale->month)->format('F'), // Format month name
            'total_amount' => (int) $sale->total_amount // Ensure the amount is an integer
        ];
    }

    $topSales = Sale::select('product_id', DB::raw('SUM(total_amount) as total_sales'))
        ->groupBy('product_id')
        ->orderByDesc('total_sales')
        ->take(5)
        ->get();

    $formattedTopSales = [];
    foreach ($topSales as $sale) {
        $product = Product::find($sale->product_id);
        if ($product) {
            $formattedTopSales[] = [
                'productName' => $product->name,
                'totalSales' => $sale->total_sales,
            ];
        }
    }

    // Get today's date and yesterday's date
    $today = Carbon::today()->toDateString();
    $yesterday = Carbon::yesterday()->toDateString();

    // Query sales data for today and yesterday
    $todaySales = Sale::whereDate('created_at', $today)->sum('total_amount');
    $yesterdaySales = Sale::whereDate('created_at', $yesterday)->sum('total_amount');

    // Fetch this week's sales
    $thisWeekSales = Sale::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                        ->sum('total_amount');

    // Fetch last week's sales
    $lastWeekSales = Sale::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                        ->sum('total_amount');

    return view('home', [
        'monthlySales' => $formattedMonthlySales,
        'formattedTopSales'=> $formattedTopSales,
        'totalProducts' => $totalProducts,
        'totalSales' => $totalSales,
        'totalCustomers' => $totalCustomers,
        'totalSuppliers' => $totalSuppliers,
        'totalInvoices' => $totalInvoices,
        'todaySales' => $todaySales, 
        'yesterdaySales' => $yesterdaySales,
        'thisWeekSales' =>$thisWeekSales,
         'lastWeekSales' =>$lastWeekSales,
    ]);
}

    public function add_profile(){
      $userRoles = config('constants.USER_ROLES');
      return view('profile.add_profile', compact('userRoles'));
    }

    public function store_profile(Request $request){
      $request->validate([
        'f_name' => 'required',
        'l_name' => 'required',
        'user_role' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'confirm_password' => 'required|same:password',
        'image' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);
      $user = new User();
      $user->f_name = $request->f_name;
      $user->l_name = $request->l_name;
      $user->email = $request->email;
      $user->role = $request->user_role;

      if ($request->hasFile('image')){
        $imageName =request()->image->getClientOriginalName();
        request()->image->move(public_path('images/user/'), $imageName);
        $user->image = $imageName;
      }

      if ($request->filled(['password', 'confirm_password'])) {
          // Validate password change fields
          $request->validate([
              'password' => 'required|min:8',
              'confirm_password' => 'required|same:password',
          ]); 
          $user->password = Hash::make($request->password);
      }
      

      $user->save();

      return redirect()->back()->with('success', 'Profile Added successfully');
    }

    public function edit_profile(){
      $userRoles = config('constants.USER_ROLES');
      return view('profile.edit_profile', compact('userRoles'));
    }

    public function update_profile(Request $request, $id){

        $user = User::find($id);
        $user->f_name = $request->f_name;
        $user->l_name = $request->l_name;
        $user->email = $request->email;
        

        if ($request->hasFile('image')){
          $image_path ="images/user/".$user->image;
          if (file_exists($image_path)){
              unlink($image_path);
          }
          $imageName =request()->image->getClientOriginalName();
          request()->image->move(public_path('images/user/'), $imageName);
          $user->image = $imageName;
        }

        if ($request->filled(['current_password', 'new_password', 'confirm_password'])) {
            // Validate password change fields
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|different:current_password',
                'confirm_password' => 'required|same:new_password',
            ]);
        
            // Verify if the entered current password matches the actual password
            if (Hash::check($request->current_password, $user->password)) {
                // Check if the new and confirm passwords match
                if ($request->new_password !== $request->confirm_password) {
                    return redirect()->back()->with('error', 'New and confirm passwords do not match');
                }
        
                // Hash and update the new password
                $user->password = Hash::make($request->new_password);
            } else {
                return redirect()->back()->with('error', 'Incorrect current password');
            }
        }
        

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully');
    }


    // public function update_password(){
    //     return view('profile.password');
    // }

    // public function update_password() {
    //     return view('profile.password', ['token' => $token]);
    // }
}
