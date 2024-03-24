<?php

use App\Http\Controllers\AdminController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ButtonController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResetPasswordController;

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
    return view('auth.register');
});
Route::prefix('/auth')->group(function () {
    Route::get('/register',[UserController::class, 'registerForm']);
    Route::post('/register-add',[UserController::class, 'register']);
    Route::get('/login',[UserController::class, 'loginForm'])->name('login');
    Route::post('/login-add',[UserController::class, 'login']);
    Route::get('/forgot-password',[ForgotController::class, 'forgotForm']);
    Route::post('/reset-password',[ForgotController::class, 'forgot']);
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showresetForm'])->name('password.reset');
    Route::post('/reset-password/{token}', [ResetPasswordController::class, 'reset'])->name('password.update');
    Route::get('/logout',[UserController::class, 'logout']);
    Route::get('/login-admin',[AdminController::class, 'loginAdminForm']);
    Route::post('/login-admin-add',[AdminController::class, 'loginPost']);
    Route::get('/logout-admin',[AdminController::class, 'logout']);
});

Route::get('/home', function () {
    return view('home');
})->middleware('auth');
Route::post('/upload', [TaxController::class, 'uploadExcel'])->name('upload.excel')->middleware('auth');
Route::get('/button/{filename}', [ButtonController::class, 'index'])->name('button')->middleware('auth');
Route::get('/uploaded-file', [UserController::class, 'userFile'])->name('uploaded.file')->middleware('auth');
Route::prefix('/export')->middleware('auth')->group(function(){
    Route::get('/b2b/{filename}', [ButtonController::class, 'showB2B'])->name('show.b2b');
    Route::get('/b2b/{data}/export', [ButtonController::class, 'exportB2B'])->name('export.b2b');
    Route::get('/b2cs/{filename}', [ButtonController::class, 'showB2CS'])->name('show.b2cs');
    Route::get('/b2cs/{data}/export', [ButtonController::class, 'exportB2CS'])->name('export.b2cs');
    Route::get('/hsn/{filename}', [ButtonController::class, 'showHSN'])->name('show.hsn');
    Route::get('/hsn/{data}/export', [ButtonController::class, 'exportHSN'])->name('export.hsn');
});

//dashboard route
Route::prefix('/dashboard')->middleware('admin.auth')->group( function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user', [DashboardController::class, 'user'])->name('user');
    Route::get('/user-file/{id}', [DashboardController::class, 'fileUser'])->name('file.user');
    Route::get('/active-user/{id}', [DashboardController::class, 'activeUser'])->name('active.user');
    Route::get('/nonactive-user/{id}', [DashboardController::class, 'nonActiveUser'])->name('nonActive.user');
});
