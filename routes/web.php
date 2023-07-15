<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SlideController;
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

//? Client Controller
Route::controller(ClientController::class)->group(function () {
    Route::get('/','pages')->defaults('page', 'home');
    Route::get('/shop', 'pages')->defaults('page', 'shop');
    Route::get('/card', 'pages')->defaults('page', 'card');
    Route::get('/checkout', 'checkout');
    Route::get('/register', 'pages')->defaults('page', 'register');
    Route::get('/signin', 'pages')->defaults('page', 'signin');
    Route::get('/account', 'pages')->defaults('page', 'account');
    Route::get('/addtocart/{id}', 'addtocart');
    Route::put('/cart/updateqty/{id}', 'updateqty');
    Route::get('/cart/removeitem/{id}', 'removeitem');
    Route::post('/createaccount', 'createaccount');
    Route::post('/accessaccount', 'accessaccount');
    Route::get('/logout', 'logout');

});

//? Admin Controller
Route::prefix('admin')->controller(AdminController::class, 'pages')->group(function () {
    Route::get('/', 'pages')->defaults('page', 'home');
    Route::get('/addcategory', 'pages')->defaults('page', 'addcategory');
    Route::get('/category', 'pages')->defaults('page', 'category');
    Route::get('/addslider', 'pages')->defaults('page', 'addslider');
    Route::get('/sliders', 'pages')->defaults('page', 'sliders');
    Route::get('/addproduct', 'pages')->defaults('page', 'addproduct');
    Route::get('/products', 'pages')->defaults('page', 'products');
    Route::get('/orders', 'pages')->defaults('page', 'orders');
});

//? Category Controller
Route::post('admin/savecategory', [CategoryController::class, 'savecategory']);
Route::delete('admin/deletecategory/{id}', [CategoryController::class, 'deletecategory']);
Route::get('admin/editcategory/{id}', [CategoryController::class, 'editcategory']);
Route::put('admin/updatecategory/{id}', [CategoryController::class, 'updatecategory']);

//? Slider Controller
Route::controller(SlideController::class)->group(function () {
    Route::post('admin/saveslider', "saveslider");
    Route::delete('admin/deleteslider/{id}', "deleteslider");
    Route::get('admin/editslider/{id}', "editslider");
    Route::put('admin/updateslider/{id}', "updateslider");
    Route::put('admin/unactivateslider/{id}', "unactivateslider");
    Route::put('admin/activateslider/{id}', "activateslider");
});

//? Product Controller
Route::controller(ProductController::class)->group(function () {
    Route::post('admin/saveproduct', "saveproduct");
    Route::delete('admin/deleteproduct/{id}', "deleteproduct");
    Route::get('admin/editproduct/{id}', "editproduct");
    Route::put('admin/updateproduct/{id}', "updateproduct");
    Route::put('admin/unactivateproduct/{id}', "unactivateproduct");
    Route::put('admin/activateproduct/{id}', "activateproduct");
});
