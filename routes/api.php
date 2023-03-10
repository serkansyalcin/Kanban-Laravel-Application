<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\StatusController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\TaskController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
//Route::get('login', [RegisterController::class, 'login'])->name('login'); // just not to use for now

Route::middleware('auth:sanctum')->group( function () {
    // Tasks related apis
    Route::resource('tasks', TaskController::class);

    // Status related apis
    Route::resource('statuses', StatusController::class);

    // Categories related apis
    Route::resource('categories', CategoryController::class);

    // Logged in users tasks
    Route::get('user-tasks', [UserController::class, 'userTasks']);
});
