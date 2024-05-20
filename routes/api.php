<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\UsersCT;
use App\Http\Controllers\ProductCT;
use App\Http\Controllers\CategoriesCT;
// // use App\Http\Controllers\GoogleCT;
// use App\Http\Controllers\Auth\GoogleCT;


Route::post('login', [UsersCT::class, 'login']);
Route::post('register', [UsersCT::class, 'register']);

// Group untuk rute yang memerlukan middleware 'checkrole.jwt'
Route::middleware(['allakses.jwt'])->group(function() {
    // Rute untuk product
    Route::post('product', [ProductCT::class, 'AddProduct']);
    Route::put('product/{id}', [ProductCT::class, 'UpdateProduct']);
    Route::get('product', [ProductCT::class, 'ShowProduct']);
    Route::delete('product/{id}', [ProductCT::class, 'DeleteProduct']);
});

// Group untuk rute admin yang memerlukan middleware 'admin'
Route::middleware(['admin.jwt'])->group(function () {
    Route::post('categories', [CategoriesCT::class, 'AddCategories']);
    Route::put('categories/{id}', [CategoriesCT::class, 'UpdateCategories']);
    Route::get('categories', [CategoriesCT::class, 'ShowCategories']);
    Route::delete('categories/{id}', [CategoriesCT::class, 'DeleteCategories']);
});    

// Route::get('auth/google/redirect', [GoogleCT::class, 'redirect']);
// Route::get('auth/google/callback', [GoogleCT::class, 'callbackGoogle']);