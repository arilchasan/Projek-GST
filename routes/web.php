<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForgotController;
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
});

Route::get('/home', function () {
    return view('home');
})->middleware('auth');
