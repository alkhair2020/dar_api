<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Api\Users\AuthController as UsersAuthController;
// use App\Http\Controllers\Api\Clients\AuthController as ClientsAuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Route::group(['middleware' => ['api'],'prefix' => 'users','namespace' => 'App\Http\Controllers\Api\Users'], function () {
//     Route::post('login', 'AuthController@login');
// });


// Route::group(['middleware' => ['api'], 'prefix' => 'users'], function () {
//     Route::post('login', [AuthController::class, 'login']);
// });

Route::group(['middleware' => ['api'],'prefix' => 'clients', 'namespace' => 'Api\Clients'], function () {
    Route::post('login', 'AuthController@login');
});
Route::group(['middleware' => ['api'],'prefix' => 'users', 'namespace' => 'App\Http\Controllers\Api\Users'], function () {
    Route::post('login', 'AuthController@login');
    Route::get('search-client', 'FrontController@searchClient');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
