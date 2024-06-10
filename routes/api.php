<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('create-user', [UserController::class, 'CreateUser']);
Route::put('update-user/{id}', [UserController::class, 'UpdateUser']);
Route::delete('delete-user/{id}', [UserController::class, 'DeleteUser']);
Route::post('login', [UserController::class, 'Login']);

// unauthenticated
Route::get('unauthenticate', [UserController::class, 'Unauthenticate'])->name('unauthenticate');

// Secure  routes within auth middleware
Route::middleware('auth:api')->group(function(){
    Route::get('get-user', [UserController::class, 'GetUser']);
    Route::get('get-user-details/{id}', [UserController::class, 'GetUserDetails']);
    Route::post('logout', [UserController::class, 'Logout']);
});
