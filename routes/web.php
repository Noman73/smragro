<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/test', [App\Http\Controllers\TestController::class, 'index']);
Route::get('/test2', [App\Http\Controllers\TestController::class, 'createCustomer']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/admin/dashboard-data',[App\Http\Controllers\HomeController::class, 'dashboard']);
Route::group([
    'prefix'=>"admin",
    'namespace' => 'App\Http\Controllers\Admin',
],function(){
    Route::resource('/category','CategoryController');
    Route::resource('/brand','BrandController');
    Route::resource('/market','MarketsController');
    Route::post('/get-market','MarketsController@getMarket');
    Route::resource('/model','ModelController');
    Route::post('/get-brand','BrandController@getBrand');
    Route::get('/brand-multiply/{id}','BrandController@getMultiply');
    Route::post('/get-model','ProductController@getModel');
    Route::post('/get-product-by-data','ProductController@getProductByData');
    Route::post('/get-part-id','ProductController@getPartId');
    Route::post('/get-all-products','ProductController@getAllProduct');
    Route::post('/product-details','ProductController@productDetails');
    Route::get('/get-product-by-code/{code}','ProductController@getProductByCode');
    Route::resource('/multi_note','MultiNoteController');
    Route::post('/get-multi-note','MultiNoteController@getMultiNote');
    Route::resource('/supplier','SupplierController');
    Route::resource('/unit','UnitController');
    Route::resource('/product','ProductController');
    Route::resource('/purchase','PurchaseInvoiceTest');
    Route::resource('/bank','BankAccountController');
    Route::resource('/customer','CustomerController');
    Route::resource('/invoice','InvoiceTestController');
    Route::resource('/pos','PosController');
    Route::resource('/warehouse','WarehouseController');
    Route::post('/get-warehouse','WarehouseController@getWarehouse');
    Route::resource('/regular-condition-list','RegularConditionListController');
    Route::resource('/regular-receive','RegularReceiveController');
    Route::get('/invoice-list','InvoiceTestController@invoiceList');
    Route::get('/purchase-list','PurchaseInvoiceTest@purchaseList');
    Route::resource('/sales_return','SalesReturnController');
    Route::resource('/voucer','VoucerController');
    Route::resource('/shipping-company','ShippingCompanyController');
    Route::resource('/payment','PaymentController');
    Route::resource('/s-payment','SPaymentController');
    Route::resource('/receive','ReceiveController');
    Route::resource('/c-receive','CustomerReceiveController');
    Route::resource('/sub_ledger','AccountSubLedgerController');
    Route::resource('/fund_transfer','FundTransferController');
    Route::resource('/make-price','MakePriceController');
    Route::resource('/sleep','SleepController');
    Route::resource('/credit-setup','CreditSetupController');
    Route::resource('/employee','EmployeeController');
    Route::resource('/employee-attendance','AttendanceController');
    Route::post('/get-employee','EmployeeController@getEmployee');
    Route::resource('/employee-salary','EmployeeSalaryController');
    Route::post('/get-category','CategoryController@getCategory');
    Route::post('/get-unit','UnitController@getUnit');
    Route::post('/get-product','ProductController@getProduct');
    Route::post('/get-product-without-combo','ProductController@getProductWithoutCombo');
    Route::post('/get-payment-method','BankAccountController@getPaymentMethod');
    Route::get('/get-bank-details/{id}','BankAccountController@getBankDetails');
    Route::post('/get-supplier','SupplierController@getSupplier');
    Route::post('/get-customer','CustomerController@getCustomer');
    Route::post('/check-customer','CustomerController@checkCustomer');
    Route::post('/get-shipping-company','ShippingCompanyController@getShippingCompany');
    Route::get('/courier-top/{customer}','ShippingCompanyController@getTopShippingCompany');
    Route::get('/stock','StockController@index');
    Route::get('/get-sale/{inv_id}','InvoiceController@searchInvoice');
    Route::get('/count_product/{category_id}','ProductController@productCountByCat');
    Route::get('/condition-list','ConditionController@index');
    Route::post('/condition-receive','ConditionController@store')->name('condition_receive.store');
    Route::post('/customer-price','MakePriceController@searchProduct');
    Route::get('/price-list/{customer_id}','MakePriceController@priceList');
    Route::get('/get-quantity/{product_id}','ProductController@getQantity');
    Route::post('/get-product-price','ProductController@productPrice');
    Route::get('/get-product-sale-price/{product_id}','ProductController@getProductSalePrice');
    Route::resource('/roles','RoleController');
    Route::resource('/permission','PermissionController');
    Route::resource('/asign-permission','ApplyPermissionController');
    Route::resource('/asign-role','RoleAsignController');
    Route::resource('/part','PartController');
    Route::resource('/customer-multiply','CustomerWiseMultiplyController');
    Route::get("get-customer-multiply/{customer_id}/{brand_id}",'CustomerWiseMultiplyController@getMultiply');
    Route::resource('/asign-permission-user','UserDirectPermissionController');
    Route::post('/get-role','RoleAsignController@getRole');
    Route::post('/get-user','RoleAsignController@getUser');
    Route::post('/get-part','PartController@getPart');
    Route::get('/get-role-has-permission','PermissionController@getPermission');
    Route::get('/get-model-has-permission/{user_id}','PermissionController@getModelPermission');
    Route::get('/sales-yearly-bar-chart','ChartDataController@getYearlyInvoiceData');
    Route::get('/receive-payment-yearly-line-chart','ChartDataController@last30DaysReceivePayment');
    Route::resource('/password','PasswordChangeController');
    Route::resource('/user','UserController');
    Route::get('/selected-product-data/{id}','ProductController@getAllData');
    Route::get('/part-id-product-data/{id}','ProductController@getAllPartIdData');
    Route::resource('/sms_template','SmsTemplateController');
});
Route::group([
    'prefix'=>"admin/accounts",
    'namespace' => 'App\Http\Controllers\Admin',
],function(){
    Route::resource('/classes','AccountClassController');
    Route::resource('/group','AccountGroupController');
    Route::resource('/account-ledger','AccountLedgerController');
    Route::resource('/journal','JournalController');
    Route::resource('/chart-of-account','ChartOfAccountController');
    Route::post('/get-account-class','AccountClassController@getClass');
    Route::post('/get-account-group','AccountGroupController@getAccountGroup');
    Route::post('/get-account-ledger','AccountLedgerController@getAccountLedger');
    Route::post('/get-account-ledger-sub','AccountLedgerController@getAccountLedgerCanSubAccount');
    Route::post('/get-sub-ledger','AccountLedgerController@getAccountSubLedger');
    Route::get('/get-customer-balance/{id}','CustomerController@getBalance');
    Route::get('/get-supplier-balance/{id}','SupplierController@getBalance');
    Route::get('/cash-balance','HelperController@getCashBalance');
    Route::get('/bank-balance/{bank_id}','HelperController@getBankBalance');
    
});

Route::group([
    'prefix'=>"admin/setting",
    'namespace' => 'App\Http\Controllers\Admin',
],function(){
    Route::resource('/general-info','CompanyInfoController');
});
Route::group([
    'prefix'=>"admin/view-pages",
    'namespace' => 'App\Http\Controllers\PrintView',
],function(){
    Route::get('/sales-invoice/{invoice_id}','InvoiceViewController@index');
    Route::get('/sales-invoice-print/{invoice_id}','InvoiceViewController@print');
    Route::get('/sales-invoice-pos-print/{invoice_id}','InvoiceViewController@posPrint');
    Route::get('/sales-invoice-bangla-print/{invoice_id}','InvoiceViewController@printInBangla');
    Route::get('/sales-chalan-invoice-print/{invoice_id}','InvoiceViewController@chalan');
    Route::get('/sales-total-chalan-invoice-print/{invoice_id}','InvoiceViewController@totalChalan');
    Route::get('/sales-road-chalan-invoice-print/{invoice_id}','InvoiceViewController@roadChalan');
    Route::get('/sales-pos-invoice-print/{invoice_id}','InvoiceViewController@posPrint');
    Route::get('/payment-view/{invoice_id}','PaymentViewController@index');
    Route::get('/payment-print/{invoice_id}','PaymentViewController@print');
    Route::get('/receive-view/{invoice_id}','ReceiveViewController@index');
    Route::get('/receive-print/{invoice_id}','ReceiveViewController@print');
    Route::get('/journal-view/{invoice_id}','JournalViewController@index');
    Route::get('/journal-print/{invoice_id}','JournalViewController@print');
    Route::get('/purchase-view/{invoice_id}','PurchaseViewController@index');
    Route::get('/purchase-print/{invoice_id}','PurchaseViewController@print');
    Route::get('/fund-transfer-view/{invoice_id}','FundTransferController@index');
    Route::get('/fund-transfer-print/{invoice_id}','FundTransferController@print');
    Route::resource('/road_chalan','RoadChalanController');
    Route::get('/vat-chalan/{invoice_id}','VatChalanController@print');
});


Route::group([
    'prefix'=>"admin",
    'namespace' => 'App\Http\Controllers\Reports',
],function(){
    Route::get('/ledger-report','LedgerReportController@index');
    Route::post('/ledger-report','LedgerReportController@generateReport');
    Route::get('/trial-balance','TrialBalanceController@index');
    Route::post('/trial-balance','TrialBalanceController@getReport');
    Route::get('/customer-list','CustomerReportController@index');
    Route::get('/customer-list-all','CustomerReportController@getReport');
    Route::get('/supplier-list','SupplierReportController@index');
    Route::get('/supplier-list-all','SupplierReportController@getReport');
    Route::get('/payment-report','PaymentReportController@index');
    Route::post('/payment-report','PaymentReportController@getReport');
    Route::get('/receive-report','ReceiveReportController@index');
    Route::post('/receive-report','ReceiveReportController@getReport');
    Route::get('/sales-report','SalesReportController@index');
    Route::post('/sales-report','SalesReportController@getReport');
    Route::get('/purchase-report','PurchaseReportController@index');
    Route::post('/purchase-report','PurchaseReportController@getReport');
    Route::get('/inventory-report','InventoryReportController@index');
    Route::post('/inventory-report','InventoryReportController@getReport');
    Route::get('/stock-value-report','StockValueController@index');
    Route::post('/stock-value-report','StockValueController@getReport');
    Route::get('/bank-balance-report','BankBalanceReportController@index');
    Route::post('/bank-balance-report','BankBalanceReportController@getReport');
    Route::get('/cash-in-hand-report','CashInHandReportController@index');
    Route::post('/cash-in-hand-report','CashInHandReportController@getReport');
    Route::get('/total-transaction-report','TotalTransactionController@index');
    Route::post('/total-transaction-report','TotalTransactionController@getReport');
    Route::get('/purchase-pricing-report','PurchasePricingController@index');
    Route::post('/purchase-pricing-report','PurchasePricingController@getReport');
    Route::get('/sale-pricing-report','SalePricingController@index');
    Route::post('/sale-pricing-report','SalePricingController@getReport');
    Route::get('/item-list-report','ItemListController@index');
    Route::post('/item-list-report','ItemListController@getReport');
    Route::get('/customer-wise-sale-report','CustomerWiseSalesReportController@index');
    Route::post('/customer-wise-sale-report','CustomerWiseSalesReportController@getReport');
    Route::get('/supplier-wise-purchase-report','SupplierWisePurchaseReportController@index');
    Route::post('/supplier-wise-purchase-report','SupplierWisePurchaseReportController@getReport');
    Route::get('/customer-statement-report','CustomerStatementReportController@index');
    Route::post('/customer-statement-report','CustomerStatementReportController@generateReport');
    Route::get('/supplier-statement-report','SupplierStatementReportController@index');
    Route::post('/supplier-statement-report','SupplierStatementReportController@generateReport');
    Route::get('/customer-balance-analysis-report','CustomerBalanceAnalysisController@index');
    Route::post('/customer-balance-analysis-report','CustomerBalanceAnalysisController@getReport');
    Route::get('/chart-of-account-report','ChartOfAccountController@index');
    Route::post('/chart-of-account-report','ChartOfAccountController@getReport');
    Route::get('/profit_loss','ProfitLossController@index');
    Route::post('/profit_loss','ProfitLossController@getReport');
    Route::get('/user-wise-amount','UserWiseAmountController@index');
    Route::post('/user-wise-amount','UserWiseAmountController@getReport');
});