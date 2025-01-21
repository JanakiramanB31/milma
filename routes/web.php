<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\SalesController;


Auth::routes();


Route::get('/edit_profile', 'HomeController@edit_profile')->name('edit_profile');
Route::POST('/update_profile/{id}', 'HomeController@update_profile')->name('update_profile');
Route::get('/password_change/', 'HomeController@update_password')->name('update_password');

Route::group(['middleware' => 'App\Http\Middleware\SuperAdminMiddleware', 'prefix' => 'admin'], function() {

  Route::get('/', 'HomeController@index')->name('adminhome');
  Route::get('/home', 'HomeController@index')->name('adminhome');

  Route::get('/report/x_report/{date?}', 'ReportController@x_index')->name('x_report');
  Route::get('/report/x_report/print/{data}', 'ReportController@x_report_print')->name('x_report_print');
  Route::post('report/x_report/fetchByDate/{date}', 'ReportController@fetchByDate')->name('x_report.fetchByDate');
  Route::get('/report/y_report', 'ReportController@y_index')->name('z_report');
  Route::get('/report/overall_report', 'ReportController@overall_report')->name('overall_report');
  Route::post('/report/overall_report/fetch_company_invoice', 'ReportController@fetchCompanyInvoices')->name('fetchCompanyInvoices');

  Route::resource('bt_list', 'BankTransferController');
  Route::post('bt_list/{data?}', 'BankTransferController@fetchBTInvoicesByDate')->name('fetchBTInvoicesByDate');
  Route::post('bt_list_update/{data?}', 'BankTransferController@update')->name('updateBTList');

  Route::get('/report/z_report', 'ReportController@z_index')->name('z_report');
  Route::post('/report/z_report/fetch_company_invoice', 'ReportController@zReportCompanyInvoices')->name('zReportCompanyInvoices');
  Route::get('/report/z_report/print/{data?}', 'ReportController@zReportPrintCompanyInvoices')->name('zReportPrintCompanyInvoices');
  Route::post('/report/z_report/print/update', 'ReportController@zReportInvoiceUpdate')->name('zReportInvoiceUpdate');


  Route::resource('category', 'CategoryController');
  Route::resource('subcategory', 'SubcategoryController');
  Route::resource('tax', 'TaxController');
  Route::resource('unit', 'UnitController');
  Route::resource('supplier', 'SupplierController');
  Route::resource('customer', 'CustomerController');
  Route::resource('vehicle', 'VehicleController');
  Route::resource('route','RouteController');
  Route::resource('price','PriceController');
  Route::resource('company','CompanyController');
  Route::resource('stockintransit','StockInTransitController');
  Route::post('stockintransit/check', 'StockInTransitController@checkExistence')->name('stockintransit.check');
  Route::resource('createuser','UserController');
  Route::resource('product', 'ProductController');
  Route::resource('invoice', 'InvoiceController');
  Route::post('invoice/getProducts/{id}', 'InvoiceController@getProducts')->name('invoice.getProducts');
  Route::post('invoice/fetchProducts/{id}', 'InvoiceController@fetchProducts')->name('invoice.fetchProducts');
  Route::post('invoice/storeReturns', 'InvoiceController@storeReturns')->name('invoice.storeReturns');
  Route::resource('rate', 'RateController');
  Route::get('sales', [SalesController::class, 'index'])->name('sales');

  Route::post('sales', [SalesController::class, 'filterSalesData'])->name('filterSalesData');

  Route::resource('purchase', 'PurchaseController');
  Route::get('/findPrice', 'InvoiceController@findPrice')->name('findPrice');
  Route::get('/findPricePurchase', 'PurchaseController@findPricePurchase')->name('findPricePurchase');
});

Route::get('/', 'StockInTransitController@index')->name('home');
Route::get('/home', 'StockInTransitController@index')->name('home');
Route::resource('invoice', 'InvoiceController');

Route::group(['middleware' => ['auth']], function() {
Route::resource('customer', 'CustomerController')->middleware('check.customer.permissions');
});


Route::post('invoice/getProducts/{id}', 'InvoiceController@getProducts')->name('invoice.getProducts');
Route::post('invoice/fetchInvoiceByDate/{date}', 'InvoiceController@fetchInvoiceByDate')->name('invoice.fetchInvoiceByDate');
Route::post('invoice/fetchProducts/{id}', 'InvoiceController@fetchProducts')->name('invoice.fetchProducts');
Route::post('invoice/storeReturns', 'InvoiceController@storeReturns')->name('invoice.storeReturns');
Route::resource('stockintransit','StockInTransitController');
Route::post('stockintransit/check', 'StockInTransitController@checkExistence')->name('stockintransit.check');

Route::get('/report/x_report/{date?}', 'ReportController@x_index')->name('x_report');
Route::get('/report/x_report/print/{data}', 'ReportController@x_report_print')->name('x_report_print');
Route::post('report/x_report/fetchByDate/{date}', 'ReportController@fetchByDate')->name('x_report.fetchByDate');

Route::get('/report/z_report', 'ReportController@z_index')->name('z_report');
Route::post('/report/z_report/fetch_company_invoice', 'ReportController@zReportCompanyInvoices')->name('zReportCompanyInvoices');
Route::get('/report/z_report/print/{data?}', 'ReportController@zReportPrintCompanyInvoices')->name('zReportPrintCompanyInvoices');