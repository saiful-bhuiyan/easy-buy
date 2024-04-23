<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CupponController;
use App\Http\Controllers\Admin\ShippingChargeController;
use App\Http\Controllers\Admin\OrderListController;


// Frontend Route

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PaymentController;



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

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin',[AuthController::class,'admin_login']);
Route::post('admin',[AuthController::class,'auth_login_admin']);
Route::get('admin/logout',[AuthController::class,'admin_logout']);

Route::group(['middleware' => 'admin'], function () {
    Route::get('admin/dashboard',[DashboardController::class,'dashboard']);
    // admin route
    Route::resource('admin/admin_list',AdminController::class);
    // product category route
    Route::resource('admin/category',CategoryController::class);
    // product sub category route
    Route::resource('admin/sub_category',SubCategoryController::class);
    // product brand route
    Route::resource('admin/brand',BrandController::class);
    // product color route
    Route::resource('admin/color',ColorController::class);
    // product cuppon route
    Route::resource('admin/cuppon',CupponController::class);
    // shipping charge
    Route::resource('admin/shipping_charge',ShippingChargeController::class);

    Route::get('admin/order_list',[OrderListController::class,'index']);
    Route::get('admin/order_list/details/{order_id}',[OrderListController::class,'OrderDetails']);


    // product route
    Route::resource('admin/product',ProductController::class);
    Route::post('admin/deleteProductImage', [ProductController::class, 'deleteProductImage']);
    Route::post('admin/productImageSortable', [ProductController::class, 'productImageSortable']);



    // ajax routes
    Route::post('admin/getSubCategoryByCatId', [ProductController::class, 'getSubCategoryByCatId']);
});

// Frontend Routes

Route::get('/',[HomeController::class,'home']);
Route::post('auth_register',[AuthController::class,'auth_register']);
Route::get('verify_user/{id}',[AuthController::class,'verify_user']);
Route::post('auth_login',[AuthController::class,'auth_login']);
Route::get('auth_logout',[AuthController::class,'auth_logout']);

Route::get('show-cart',[PaymentController::class,'show_cart']);
Route::get('cart',[PaymentController::class,'shopping_cart']);
Route::get('cart-update/{qty}/{id}',[PaymentController::class,'cart_update']);
Route::get('cart-delete/{id}',[PaymentController::class,'cart_delete']);
Route::get('checkout',[PaymentController::class,'checkout']);
Route::post('checkout/apply_cuppon',[PaymentController::class,'apply_cuppon']);
Route::post('checkout/place_order',[PaymentController::class,'place_order']);
Route::get('payment/paypal/{order_id}', [PaymentController::class,'paypal_payment'])->name('payment.paypal');
Route::get('paypal/success-payment', [PaymentController::class,'paypal_success_payment']);
Route::get('payment/stripe/{order_id}', [PaymentController::class,'stripe_payment'])->name('payment.stripe');
Route::get('stripe/success-payment', [PaymentController::class,'stripe_success_payment']);



Route::get('/{category?}/{subcategory?}',[FrontendController::class,'getCategory']);
Route::post('get_filter_product',[FrontendController::class,'get_filter_product']);
Route::get('get_search_product/{search}',[FrontendController::class,'get_search_product']);

Route::post('product/add-to-cart',[PaymentController::class,'add_to_cart']);


