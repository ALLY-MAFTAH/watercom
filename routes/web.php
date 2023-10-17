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

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/reset-password', [ResetPasswordController::class, 'index'])->name('password.request');
Route::post('/send-reset-link', [ResetPasswordController::class, 'validatePasswordRequest'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'reset'])->name('password.reset');
Route::post('/password/reset-confirm', [ResetPasswordController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth'])->group(function () {

    // STOCK ROUTES
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('/show-stock/{stock}', [StockController::class, 'showStock'])->name('stocks.show');
    Route::put('stocks/{stock}/status', [StockController::class, 'toggleStatus'])->name('stocks.toggle-status');

    // ITEM ROUTES
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::post('/add-item', [ItemController::class, 'postItem'])->name('items.add');
    Route::get('/show-item/{item}', [ItemController::class, 'showItem'])->name('items.show');
    Route::put('/edit-item/{item}', [ItemController::class, 'putItem'])->name('items.edit');
    Route::delete('/delete-item/{item}', [ItemController::class, 'deleteItem'])->name('items.delete');
    Route::put('items/{item}/status', [ItemController::class, 'toggleStatus'])->name('items.toggle-status');
    Route::post('/add-ingredients/{item}', [ItemController::class, 'postIngredients'])->name('items.add-ingredients');
    Route::put('/edit-ingredients/{item}', [ItemController::class, 'putIngredients'])->name('items.edit-ingredients');
    Route::delete('/delete-ingredient/{ingredient}', [ItemController::class, 'deleteIngredient'])->name('items.delete-ingredient');

    // PRODUCTS ROUTES
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/add-product', [ProductController::class, 'postProduct'])->name('products.add');
    Route::get('/show-product/{product}', [ProductController::class, 'showProduct'])->name('products.show');
    Route::put('/edit-product/{product}', [ProductController::class, 'putProduct'])->name('products.edit');
    Route::delete('/delete-product/{product}', [ProductController::class, 'deleteProduct'])->name('products.delete');
    Route::put('products/{product}/status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::put('products/{product}/discount', [ProductController::class, 'toggleDiscount'])->name('products.toggle-discount');

    // SELLING CART
    Route::get('/sale-product', [SaleController::class, 'saleProduct'])->name('products.sale');
    Route::get('/carts', [SaleController::class, 'index'])->name('carts.index');
    Route::get('get-cart-data', [SaleController::class, 'getCartData'])->name('cart');
    Route::get('check-cart', [SaleController::class, 'checkCart'])->name('cart');
    Route::post('add-to-cart/{id}', [SaleController::class, 'addToCart'])->name('add.to.cart');
    Route::patch('update-cart', [SaleController::class, 'update'])->name('update.cart');
    Route::delete('remove-from-cart', [SaleController::class, 'remove'])->name('remove.from.cart');
    Route::get('empty-cart', [SaleController::class, 'empty'])->name('empty.cart');

    // CUSTOMERS ROUTES
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/add-customer', [CustomerController::class, 'postCustomer'])->name('customers.add');
    Route::get('/show-customer/{customer}', [CustomerController::class, 'showCustomer'])->name('customers.show');
    Route::put('/edit-customer/{customer}', [CustomerController::class, 'putCustomer'])->name('customers.edit');
    Route::delete('/delete-customer/{customer}', [CustomerController::class, 'deleteCustomer'])->name('customers.delete');
    Route::put('customers/{customer}/status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');

    // ORDERS ROUTES
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/add-order', [OrderController::class, 'postOrder'])->name('orders.add');
    Route::post('/send-order', [OrderController::class, 'sendOrder'])->name('orders.send');
    Route::get('/show-order/{order}', [OrderController::class, 'showOrder'])->name('orders.show');
    Route::put('/edit-order/{order}', [OrderController::class, 'putOrder'])->name('orders.edit');
    Route::delete('/delete-order/{order}', [OrderController::class, 'deleteOrder'])->name('orders.delete');

    // SALES ROUTES
    Route::get('/sales', [SaleController::class, 'allSales'])->name('sales.index');

    // REPORTS ROUTES
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/sales-report', [ReportController::class, 'salesReport'])->name('reports.sales');
    Route::get('/stocks-report', [ReportController::class, 'stocksReport'])->name('reports.stocks');
    Route::get('/customers-report', [ReportController::class, 'customersReport'])->name('reports.customers');
    Route::get('/orders-report', [ReportController::class, 'ordersReport'])->name('reports.customers');
});

Route::group(['middleware' => ['auth', 'role.user:' . Role::ADMIN, 'verified']], function () {

    Route::post('/add-stock', [StockController::class, 'postStock'])->name('stocks.add');
    Route::put('/edit-stock/{stock}', [StockController::class, 'putStock'])->name('stocks.edit');
    Route::delete('/delete-stock/{stock}', [StockController::class, 'deleteStock'])->name('stocks.delete');
    // USERS ROUTES
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/show-user/{user}', [UserController::class, 'showUser'])->name('users.show');
    Route::post('/add-user', [UserController::class, 'postUser'])->name('users.add');
    Route::put('users/{user}/status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::put('edit-user/{user}', [UserController::class, 'putUser'])->name('users.edit');
    Route::delete('/delete-user/{user}', [UserController::class, 'deleteUser'])->name('users.delete');
    Route::put('change-password', [UserController::class, 'changePassword'])->name('users.change-password');

    // ROLES ROUTES
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/show-role/{role}', [RoleController::class, 'showRole'])->name('roles.show');
    Route::post('/add-role', [RoleController::class, 'postRole'])->name('roles.add');
    Route::put('roles/{role}/status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
    Route::put('edit-role/{role}', [RoleController::class, 'putRole'])->name('roles.edit');
    Route::delete('/delete-role/{role}', [RoleController::class, 'deleteRole'])->name('roles.delete');
    Route::put('orders/{order}/status', [OrderController::class, 'toggleStatus'])->name('orders.toggle-status');

    // SETTINGS ROUTES
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/edit-setting', [SettingController::class, 'putSetting'])->name('settings.edit');
    Route::post('/add-setting', [SettingController::class, 'postSetting'])->name('settings.add');
    Route::delete('/delete-setting/{key}', [SettingController::class, 'deleteSetting'])->name('settings.delete');

    // ACTIVITY LOGS ROUTES
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
    Route::post('/add-log', [ActivityLogController::class, 'postActivityLog'])->name('logs.add');
    Route::put('/edit-log', [ActivityLogController::class, 'putActivityLog'])->name('logs.edit');
    Route::delete('/delete-log/{key}', [ActivityLogController::class, 'deleteActivityLog'])->name('logs.delete');
});
