<?php

use App\Http\Controllers\EventManageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/calendar/add',[EventManageController::class,'store'])->name('calendar.add');
Route::get('/calendar/events',[EventManageController::class,'get_events'])->name('calendar.get_event');
Route::delete('/calendar/event/delete/{id}',[EventManageController::class,'destroy'])->name('calendar.delete');
Route::put('/calendar/event/update',[EventManageController::class,'update'])->name('calendar.update');
