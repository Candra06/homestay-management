<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\RoleAksesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\AdditionalController;
use App\Http\Controllers\RoomTypesController;
use App\Http\Controllers\RoomsController;
use App\Http\Middleware\CheckAccessMidleware;

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

Route::get('/', [AuthController::class,'landingPage']);
Route::get('/backoffice', [AuthController::class,'login']);
Route::get('/print', [AuthController::class,'showReceipt']);
Route::post('/login',[AuthController::class,'submitLogin']);
Route::group(["prefix" => "/", "middleware" => ["auth", CheckAccessMidleware::class]], function () {
    Route::get('/dashboard', [AuthController::class,'dashboard']);
    Route::get('/logout', [AuthController::class,'logout']);

    Route::resource('menu', MenuController::class);
    Route::resource('role', RoleUserController::class);
    Route::resource('role-akses', RoleAksesController::class);
    Route::resource('user', UserController::class);
    Route::resource('booking', BookingController::class);
    Route::resource('facility', FacilityController::class);
    Route::resource('additional', AdditionalController::class);
    Route::resource('room-type', RoomTypesController::class);
    Route::resource('room', RoomsController::class);
});
Route::get('room-number/{floor}', [RoomsController::class, 'generateRoomNumber']);



