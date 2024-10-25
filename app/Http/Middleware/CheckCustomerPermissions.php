<?php

namespace App\Http\Middleware;

use App\Customer;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckCustomerPermissions
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if(Auth::check()) {
      $userRole = Auth::user()->role;
      $customerID = $request->route('customer');
      $customer = Customer::find($customerID);

      if($customer) {
        if($userRole == "sales" && !$customer->created_at->isToday()) {
          abort(403, 'Unauthorized action.');
        }
      }
    }
    return $next($request);
  }
}
