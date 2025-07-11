<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WalletController;

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


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login',    [AuthController::class, 'login']);
    Route::post('logout',   [AuthController::class, 'logout']);
    Route::post('refresh',  [AuthController::class, 'refresh']);
    Route::post('me',       [AuthController::class, 'me']);

});

Route::middleware('auth:api')->group( function () {
    // Users
    Route::resource('users', UserController::class);
    Route::put('users/{id}/restore', [UserController::class, 'restore']);
    // Tasks
    Route::resource('tasks', TaskController::class);
    Route::get('tasks/user/me', [TaskController::class, 'tasksLoggedUser']);
    Route::post('tasks/user/me', [TaskController::class, 'storeLoggedUser']);
    Route::get('tasks/user/{id}', [TaskController::class, 'tasksByUser']);
});
