<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MandatController;

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

Route::post('/createSuspectMandat', [MandatController::class, 'createSuspectMandat']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/Users', [UserController::class, 'alluser'])->middleware('permission:View All Users');
});

Route::post('/login', [UserController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index')->middleware('permission:View All Users');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create')->middleware('permission:Add User');
    Route::POST('/users/store', [UserController::class , 'store'])->name('admin.users.store')->middleware('permission:Add User');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit')->middleware('permission:Update User');
    Route::POST('/users/{id}/update', [UserController::class , 'update'])->name('admin.users.update')->middleware('permission:Update User');
    Route::get('/users/{id}/reset', [UserController::class , 'resetPassword'])->name('admin.users.reset')->middleware('permission:Update Password');
    Route::get('/user/newPassword',[UserController::class , 'NewPassword'])->name('admin.user.newPassword');
    Route::post('/users/changePassword', [UserController::class, 'changePassword'])->name('admin.users.changePassword');
});

Route::get('/Western', [MandatController::class, 'getWestern']);

