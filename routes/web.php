<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CtafController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\MandatController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\InterneController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\NationUnisController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\MenuSideBarController;

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
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    //dd(date('Y-m-d'));
    return view('dashboard');
})->middleware(['auth', 'verified' ,'auth.timeout'])->name('home');

Route::middleware(['auth','auth.timeout'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'admin','App\Http\Controllers' => 'MenuSideBarController', 'middleware' => ['auth','auth.timeout']], function () {
    Route::get('/MenuSideBar', [MenuSideBarController::class, 'index'])->name('admin.MenuSideBar.index')->middleware('permission:View All Menu');
    Route::POST('/MenuSideBar/store', [MenuSideBarController::class , 'store'])->name('admin.MenuSideBar.store')->middleware('permission:Add Menu');
    Route::get('/MenuSideBar/ajax/{id}',[MenuSideBarController::class , 'ajaxParentPermission'])->name('admin.MenuSideBar.ajaxParentPermission');
});

Route::group(['prefix' => 'admin','App\Http\Controllers' => 'ModuleController', 'middleware' => ['auth','auth.timeout']], function () {
    Route::get('/modules', [ModuleController::class, 'index'])->name('admin.modules.index')->middleware('permission:View All Modules');
    Route::POST('/modules/store', [ModuleController::class , 'store'])->name('admin.modules.store')->middleware('permission:Add Module');
});

Route::group(['prefix' => 'admin','App\Http\Controllers' => 'PermissionController', 'middleware' => ['auth','auth.timeout']], function () {
    Route::get('/permissions', [PermissionController::class, 'index'])->name('admin.permissions.index')->middleware('permission:View All Permissions');
    Route::POST('/permissions/store', [PermissionController::class , 'store'])->name('admin.permissions.store')->middleware('permission:Add permission');
});

Route::group(['prefix' => 'admin','App\Http\Controllers' => 'RoleController', 'middleware' => ['auth','auth.timeout']], function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index')->middleware('permission:View All Roles');
    Route::POST('/roles/store', [RoleController::class , 'store'])->name('admin.roles.store')->middleware('permission:Add role');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('permission:Attribut permissions');
    Route::post('/roles/attribuer/{role}',[RoleController::class,'attribuer'])->name('admin.roles.attribuer')->middleware('permission:Attribut permissions');
});

Route::group(['prefix' => 'admin','App\Http\Controllers' => 'UserController', 'middleware' => ['auth','auth.timeout']], function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index')->middleware('permission:View All Users');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create')->middleware('permission:Add User');
    Route::POST('/users/store', [UserController::class , 'store'])->name('admin.users.store')->middleware('permission:Add User');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit')->middleware('permission:Update User');
    Route::POST('/users/{id}/update', [UserController::class , 'update'])->name('admin.users.update')->middleware('permission:Update User');
    Route::get('/users/{id}/reset', [UserController::class , 'resetPassword'])->name('admin.users.reset')->middleware('permission:Update Password');
    Route::get('/user/newPassword',[UserController::class , 'NewPassword'])->name('admin.user.newPassword');
    Route::post('/users/changePassword', [UserController::class, 'changePassword'])->name('admin.users.changePassword');
});

Route::group(['prefix' => 'admin','App\Http\Controllers' => 'MandatController', 'middleware' => ['auth','auth.timeout']], function () {
    Route::get('/mandats/{type}', [MandatController::class, 'index'])->name('admin.mandats.index')->middleware('permission:View All Mandats International');
    Route::get('/mandats_national', [MandatController::class, 'mandat_nationale'])->name('admin.mandats.index')->middleware('permission:View All Mandats National');
    Route::post('/mandats/import', [MandatController::class, 'import'])->name('admin.mandats.import')->middleware('permission:Import Mandats');
    Route::get('/mandats/types/mandats',[MandatController::class, 'type'])->name('admin.mandats.type')->middleware('permission:Type Mandats');
    Route::POST('/mandats/types', [MandatController::class , 'store'])->name('admin.mandats.store')->middleware('permission:Add Type Mandats');
    Route::get('/mandat/{id}',[MandatController::class , 'view'])->name('admin.mandats.view')->middleware('permission:view mandat');
    Route::POST('/mandats/updateStatus', [MandatController::class , 'updateStatus'])->name('admin.mandats.updateStatus')->middleware('permission:Update Status Mandat');
    Route::get('/ajax/mandats', [MandatController::class, 'ajaxListeMandat'])->name('admin.mandats.ajaxListeMandat');
});

Route::group(['prefix' => 'admin','App\Http\Controllers' => 'MandatController', 'middleware' => ['auth','auth.timeout']], function () {
    Route::get('/blacklist', [BlacklistController::class, 'index'])->name('admin.blacklist.index')->middleware('permission:View All Blacklist');
    Route::post('/blacklist/import', [BlacklistController::class, 'import'])->name('admin.blacklist.import')->middleware('permission:Import Blacklist');
    Route::get('/blacklist/old', [BlacklistController::class, 'old'])->name('admin.blacklist.oled')->middleware('permission:View old Blacklist');


});

Route::group(['prefix' => 'admin','App\Http\Controllers' => 'CtafController', 'middleware' => ['auth','auth.timeout']], function () {
    Route::get('/CTAF', [CtafController::class, 'index'])->name('admin.CTAF.index')->middleware('permission:View All CTAF');
    Route::post('/CTAF/import', [CtafController::class, 'import'])->name('admin.CTAF.import')->middleware('permission:Import CTAF');
    Route::get('/CTAF/old', [CtafController::class, 'old'])->name('admin.CTAF.old')->middleware('permission:View old CTAF');

});

Route::group(['prefix' => 'admin','App\Http\Controllers' => 'InterneController', 'middleware' => ['auth','auth.timeout']], function () {
    Route::get('/Interne', [InterneController::class, 'index'])->name('admin.Interne.index')->middleware('permission:View Interne');
    Route::post('/Interne/import', [InterneController::class, 'import'])->name('admin.Interne.import')->middleware('permission:Import Interne');
    Route::get('/Interne/old', [InterneController::class, 'old'])->name('admin.Interne.index')->middleware('permission:View old Interne');

});

Route::group(['prefix' => 'admin','App\Http\Controllers' => 'AgentController', 'middleware' => ['auth','auth.timeout']], function () {
    Route::get('/Agents', [AgentController::class, 'index'])->name('admin.Agent.index')->middleware('permission:View Liste Agent');
    Route::post('/Agent/store', [AgentController::class, 'store'])->name('admin.Agent.store')->middleware('permission:Add Agent');
    Route::get('/Agent/exist/{email}' ,[AgentController::class, 'isExistAgent'] );
    Route::post('/Agent/KYC', [AgentController::class, 'updateKYC'])->name('admin.Agent.updateKYC');

});

Route::group(['prefix' => 'admin','App\Http\Controllers' => 'NationUnisController', 'middleware' => ['auth','auth.timeout']], function () {
    Route::get('/NationUnis', [NationUnisController::class, 'index'])->name('admin.NationUnis.index')->middleware('permission:View Nation unis');
    Route::post('/NationUnis/import', [NationUnisController::class, 'import'])->name('admin.NationUnis.import')->middleware('permission:Import Nation unis');
    Route::post('NationUnis/valider', [NationUnisController::class, 'valider'])->name('admin.NationUnis.valider')->middleware('permission:Import Nation unis');

});

require __DIR__.'/auth.php';
