<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountTransferController;
use App\Http\Controllers\AdvancesController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\customerController;
use App\Http\Controllers\DisplayMessagesController;
use App\Http\Controllers\empStatementController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FixedExpensesController;
use App\Http\Controllers\hrmController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ObsoleteController;
use App\Http\Controllers\payrollController;
use App\Http\Controllers\permissionsController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ReconditionedController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\reportsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\rolesController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\usersController;
use App\Http\Controllers\VisitsController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WithdrawalDepositController;
use App\Models\quotation;
use App\Models\repair;
use App\Models\Stock;
use App\Models\Unit;
use Faker\Core\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware('guest')->group(function () {
Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();
});
Route::middleware('auth')->group(function () {

    Route::get('/account/{type}', [AccountController::class, "index"]);

    Route::get('/account/depositWithdrawals', [WithdrawalDepositController::class, 'index']);
    Route::get('/account/depositWithdrawals/create', [WithdrawalDepositController::class, 'create']);
    Route::post('/account/depositWithdrawals/store', [WithdrawalDepositController::class, 'store']);
    Route::get('/account/depositWithdrawals/delete/{id}', [WithdrawalDepositController::class, 'destroy']);

    Route::get('/account/transfer', [AccountTransferController::class, 'index']);
    Route::get('/account/transfer/create', [AccountTransferController::class, 'create']);
    Route::post('/account/transfer/store', [AccountTransferController::class, 'store']);
    Route::get('/account/transfer/delete/{ref}', [AccountTransferController::class, 'destroy']);

    Route::get('/account/expense/category', [ExpenseCategoryController::class, 'index']);
    Route::get('/account/expense/category/create', [ExpenseCategoryController::class, 'create']);
    Route::post('/account/expense/category/store', [ExpenseCategoryController::class, 'store']);
    Route::get('/account/expense/category/edit/{id}', [ExpenseCategoryController::class, 'edit']);
    Route::post('/account/expense/category/update', [ExpenseCategoryController::class, 'update']);
    Route::get('/account/expense/category/delete/{id}', [ExpenseCategoryController::class, 'destroy']);

    Route::get('/account/expense', [ExpenseController::class, 'index']);
    Route::get('/account/expense/create', [ExpenseController::class, 'create']);
    Route::post('/account/expense/store', [ExpenseController::class, 'store']);
    Route::get('/account/expense/delete/{ref}', [ExpenseController::class, 'destroy']);

    Route::get("/account/fixedExpenses", [FixedExpensesController::class, "index"]);
    Route::get("/account/fixedExpenses/create", [FixedExpensesController::class, "create"]);
    Route::post("/account/fixedExpenses/store", [FixedExpensesController::class, "store"]);
    Route::get("/account/fixedExpenses/delete/{id}", [FixedExpensesController::class, "delete"]);
    Route::get("/account/fixedExpenses/edit/{id}", [FixedExpensesController::class, "edit"]);
    Route::post("/account/fixedExpenses/update", [FixedExpensesController::class, "update"]);

    Route::get('/account/statement/{id}', [AccountController::class, 'statement']);
    Route::get('/account/details/{id}/{from}/{to}', [AccountController::class, 'statementDetails']);

    Route::get('/product/generateCode', [ProductController::class, 'generateCode']);
    Route::get('/product/supplier/{id}', [ProductController::class, 'supplier']);
    Route::post('/product/addPrice', [ProductController::class, 'storePrice']);
    Route::get('/product/prices/{id}', [ProductController::class, 'getPrices']);
    Route::get('/product/price/delete/{id}', [ProductController::class, 'deletePrice']);

    Route::get('/unit/getValue/{id}', [UnitController::class, 'getValue']);
    Route::post('ajax/{method}', [App\Http\Controllers\AjaxController::class, 'handle'])->name('ajax.handle');
    Route::get('/getCatItems/{id}', [App\Http\Controllers\AjaxController::class, 'getCatItems']);
    Route::get('/getBrandItems/{id}', [App\Http\Controllers\AjaxController::class, 'getBrandItems']);
    Route::get('/getMostSelling', [App\Http\Controllers\AjaxController::class, 'mostSelling']);

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home/details/{start}/{end}/{warehouse}', [App\Http\Controllers\HomeController::class, 'details']);
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
    Route::resource('/brand', \App\Http\Controllers\BrandController::class);
    Route::resource('/category', \App\Http\Controllers\CategoryController::class);
    Route::resource('/product', \App\Http\Controllers\ProductController::class);

    Route::resource('/warehouse', \App\Http\Controllers\WarehouseController::class);
    /* Route::get('/warehouse/delete/{id}', [WarehouseController::class, "destroy"]); */
    Route::resource('/account', \App\Http\Controllers\AccountController::class);

    Route::get('/purchase', [\App\Http\Controllers\PurchaseController::class, 'payment'])->name('purchase.payment');
    Route::resource('/purchase', \App\Http\Controllers\PurchaseController::class);
    Route::resource('/unit', \App\Http\Controllers\UnitController::class);
    Route::resource('/purchaseReceive', \App\Http\Controllers\PurchaseReceiveController::class);
    Route::resource('/sale', \App\Http\Controllers\SaleController::class);
    Route::resource('/saleDelivered', \App\Http\Controllers\SaleDeliveredController::class);
    Route::resource('/saleDelivered', \App\Http\Controllers\SaleDeliveredController::class);
    Route::resource('/salePayment', \App\Http\Controllers\SalePaymentController::class);
    Route::resource('/saleReturn', \App\Http\Controllers\SaleReturnController::class);
    Route::post('/purchase/return/create', [PurchaseReturnController::class, 'search']);
    Route::post('/sale/return/create', [SaleReturnController::class, 'search']);
    Route::get('/sale/printBill/{id}',[SaleController::class, 'printBill']);
    Route::get('/sale/{start?}/{end?}/{warehouse?}',[SaleController::class, 'index']);
    Route::get('/sale/product/history/{id}/{customer}',[SaleController::class, 'proHistory']);

    Route::resource('/saleReturnPayment',\App\Http\Controllers\SaleReturnPaymentController::class);

    Route::resource('/purchaseReceive',\App\Http\Controllers\PurchaseReceiveController::class);
    Route::resource('/purchaseReturn',\App\Http\Controllers\PurchaseReturnController::class);
    Route::resource('/purchaseReturnPayment',\App\Http\Controllers\PurchaseReturnPaymentsController::class);

    Route::get('/stocks','App\Http\Controllers\StockController@index')->name('stock.index');
    Route::get('/stocks/{stockDetails}','App\Http\Controllers\StockController@show')->name('stock.show');
    Route::get('/stock/transfer',[StockController::class, 'transfer']);
    Route::get('/stock/transfer/create',[StockController::class, 'transferCreate']);
    Route::get('/stock/transfer/getProducts',[StockController::class, 'getProducts']);
    Route::post('/stock/transfer/store',[StockController::class, 'storeTransfer']);
    Route::get('/stock/transfer/view/{id}',[StockController::class, 'viewTransfer']);
    Route::get('/stock/transfer/accept/{id}',[StockController::class, 'acceptTransfer']);
    Route::get('/stock/transfer/reject/{id}',[StockController::class, 'rejectTransfer']);
    Route::get('/stock/transfer/edit/{id}',[StockController::class, 'editTransfer']);
    Route::get('/stock/transfer/delete/{ref}',[StockController::class, 'deleteTransfer']);

    Route::get('/pos', [POSController::class, 'create']);
    Route::get('/pos/getSingleProduct/{batch}', [POSController::class, 'getSingleProduct']);
    Route::get('/pos/store', [POSController::class, 'store']);
    Route::get('/reset', function() {
        Artisan::call('migrate:fresh --seed');
          return back()->with('message', 'Reset Successful');
        })->name('reset');

    Route::get('/users', [usersController::class, 'index']);
    Route::get('/user/add', [usersController::class, 'add']);
    Route::post('/user/create', [usersController::class, 'create']);
    Route::get('/user/permissions/{id}', [usersController::class, 'viewPermissions']);
    Route::get('/user/edit/{id}', [usersController::class, 'editUser']);
    Route::post('/user/update', [usersController::class, 'update']);
    Route::post('/user/assignRole', [usersController::class, 'assignRole']);
    Route::post('/user/assignPermissions', [usersController::class, 'assignPermissions']);
    Route::get('/user/role/revoke/{id}/{role}', [usersController::class, 'revokeRole']);
    Route::get('/roles', [rolesController::class, 'index']);
    Route::get('/role/edit/{id}', [rolesController::class, 'edit']);
    Route::post('/role/update', [rolesController::class, 'update']);
    Route::post('/roles/store', [rolesController::class, 'store']);
    Route::post('/role/updatePermissions', [rolesController::class, 'updatePermissions']);
    Route::get('/permissions', [permissionsController::class, 'index']);


    Route::get('/reports/summaryReport',[reportsController::class, 'summaryReport']);
    Route::get('/reports/summaryReport/data/{start}/{end}/{warehouse}',[reportsController::class, 'summaryReportData']);

    Route::get('/reports/productsSummary',[reportsController::class, 'productsSummary']);
    Route::get('/reports/productsSummary/data/{start}/{end}/{warehouse}/{category}',[reportsController::class, 'productsSummaryData']);

    Route::get('/reports/productExpiry', [reportsController::class, 'productExpiry']);
    Route::get('/reports/productExpiry/data/{warehouse}',[reportsController::class, 'productExpiryData']);

    Route::get('/reports/lowStock', [reportsController::class, 'lowStock']);
    Route::get('/reports/lowStock/data/{warehouse}',[reportsController::class, 'lowStockData']);

    Route::get('/reports/profitLoss', [reportsController::class, 'profitLoss']);
    Route::get('/reports/profitLoss/data/{start}/{end}',[reportsController::class, 'profitLossData']);

    Route::get('/reports/customerBalance',[reportsController::class, 'customerBalance']);
    Route::get('/reports/customerBalance/data/{area}',[reportsController::class, 'customerBalanceData']);

    Route::get('/hrm/employees', [hrmController::class, 'employees']);
    Route::get('/hrm/employees/add', [hrmController::class, 'employeesAdd']);
    Route::post('/hrm/employees/store', [hrmController::class, 'employeesStore']);
    Route::get('/hrm/employees/edit/{id}', [hrmController::class, 'edit']);
    Route::post('/hrm/employees/update', [hrmController::class, 'update']);

    Route::get('/hrm/attendance', [hrmController::class, 'attendance']);
    Route::get('/hrm/attendance/add', [hrmController::class, 'attendanceAdd']);
    Route::post('/hrm/attendance/store', [hrmController::class, 'attendanceStore']);
    Route::get('/hrm/attendance/delete/{id}', [hrmController::class, 'attendanceDelete']);
    Route::get('/hrm/attendance/edit/{id}', [hrmController::class, 'attendanceEdit']);
    Route::post('/hrm/attendance/update/', [hrmController::class, 'attendanceUpdate']);

    Route::get('/hrm/attendance/record/{id}', [hrmController::class, 'attendanceRecord']);
    Route::get('/hrm/attendance/record/details/{id}/{from}/{to}', [hrmController::class, 'attendanceRecordDetails']);

    Route::get('/hrm/payroll', [payrollController::class, 'payroll']);
    Route::post('/hrm/payroll/generate', [payrollController::class, 'payrollGenerate']);
    Route::post('/hrm/payroll/store', [payrollController::class, 'payrollStore']);
    Route::get('/hrm/payroll/delete/{id}', [payrollController::class, 'payrollDelete']);
    Route::get('/hrm/payroll/getSalary/{id}', [payrollController::class, 'payrollGetSalary']);
    Route::get('/hrm/payroll/view/{id}', [payrollController::class, 'payrollView']);
    Route::get('/hrm/payroll/print/{id}', [payrollController::class, 'print']);
    Route::get('/hrm/payroll/delete/{id}', [payrollController::class, 'delete']);
    Route::post('/hrm/payroll/pay', [payrollController::class, 'pay']);

    Route::get('/hrm/advances', [AdvancesController::class, 'index']);
    Route::post('/hrm/advances/store', [AdvancesController::class, 'store']);
    Route::get('/hrm/advances/view/{id}', [AdvancesController::class, 'view']);
    Route::get('/hrm/advances/payment/{id}', [AdvancesController::class, 'payment']);
    Route::post('/hrm/advance/payment/create', [AdvancesController::class, 'PaymentStore']);
    Route::get('/hrm/advances/delete/{ref}', [AdvancesController::class, 'delete']);
    Route::post('/hrm/advance/update/deduction', [AdvancesController::class, 'updateDeduction']);
    Route::get('/hrm/statement/{id}', [empStatementController::class, 'index']);
    Route::get('/hrm/statement/details/{id}/{from}/{to}', [empStatementController::class, 'details']);

    Route::post('/message/store', [DisplayMessagesController::class, 'store']);
    Route::get('/message/delete/{id}', [DisplayMessagesController::class, 'delete']);

    Route::get('/quotation', [QuotationController::class, 'index']);
    Route::get('/quotation/create', [QuotationController::class, 'create']);
    Route::post('/quotation/store', [QuotationController::class, 'store']);
    Route::get('/quotation/print/{id}', [QuotationController::class, 'print']);
    Route::get('/quotation/delete/{id}', [QuotationController::class, 'delete']);

    Route::get('/customer/purchaseHistory/{id}', [customerController::class, 'purchaseHistory']);

    Route::get('/todo', [TodoController::class, 'index']);
    Route::get('/todo/store', [TodoController::class, 'store']);
    Route::get('/todo/update', [TodoController::class, 'update']);
    Route::get('/todo/status/{id}/{status}', [TodoController::class, 'status']);
    Route::get('/todo/level/{id}/{level}', [TodoController::class, 'level']);
    Route::get('/todo/delete/{id}', [TodoController::class, 'delete']);
    Route::get('/todo/forceDelete/{id}', [TodoController::class, 'forceDelete']);
    Route::get('/todo/restore/{id}', [TodoController::class, 'restore']);

    Route::get("/notifications/get", [NotificationsController::class, 'get']);
    Route::get("/notifications/markAsRead/{id}", [NotificationsController::class, 'markAsRead']);

    Route::get("/visits", [VisitsController::class, 'index']);
    Route::get("/visits/create", [VisitsController::class, 'create']);
    Route::post("/visits/store", [VisitsController::class, 'store']);
    Route::get("/visits/view/{id}", [VisitsController::class, 'view']);
    Route::get("/visits/delete/{ref}", [VisitsController::class, 'delete']);

    Route::get("/obsolete", [ObsoleteController::class, 'index']);
    Route::get("/obsolete/create", [ObsoleteController::class, 'create']);
    Route::get('/obsolete/getProducts',[ObsoleteController::class, 'getProducts']);
    Route::post('/obsolete/store',[ObsoleteController::class, 'store']);
    Route::get('/obsolete/delete/{ref}',[ObsoleteController::class, 'delete']);
    Route::post('/obsolete/update',[ObsoleteController::class, 'update']);

    Route::get("/recondition",[ReconditionedController::class, 'index']);
    Route::get("/recondition/create/{id}",[ReconditionedController::class, 'create']);
    Route::post("/recondition/store",[ReconditionedController::class, 'store']);
    Route::get("/recondition/delete/{ref}",[ReconditionedController::class, 'delete']);

    Route::get('confirm-password', [ConfirmPasswordController::class, 'showConfirmationForm'])->name('confirm-password.show');
    Route::post('confirm-password', [ConfirmPasswordController::class, 'confirm'])->name('confirm-password.confirm');

    Route::get('/repair', [RepairController::class, 'index']);
    Route::get('/repair/create', [RepairController::class, 'create']);
    Route::post('/repair/store', [RepairController::class, 'store']);
    Route::get('/repair/delete/{id}', [RepairController::class, 'delete']);
    Route::post('/repair/addPayment', [RepairController::class, 'addPayment']);
    Route::get('/repair/view/{id}', [RepairController::class, 'show']);
    Route::get('/repair/edit/{id}', [RepairController::class, 'edit']);
    Route::post('/repair/update', [RepairController::class, 'update']);
    Route::get('/repair/payment/delete/{ref}', [RepairController::class, 'deletePayment']);
    Route::get('/repair/print/{id}', [RepairController::class, 'print']);

});
