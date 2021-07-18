<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login',[LoginController::class, 'loginUser']);
Route::post('/register', [LoginController::class, 'registerUser']);
Route::get('/logout', [LoginController::class, 'logoutUser']);

Route::get('/getAllProduct',[ ProductController::class, 'index']);
Route::get( '/getAllAuthProduct', [ProductController::class, 'getAuthenticatedProduct']);
Route::post('/addproduct', [ProductController::class, 'addProduct']);
Route::get('/editproduct/{id}', [ProductController::class, 'editData']);
Route::post('/updateproduct', [ProductController::class, 'updateData']);
Route::get('/deleteproduct/{id}', [ProductController::class, 'deleteData']);

