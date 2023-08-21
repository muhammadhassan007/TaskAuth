<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

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

Route::middleware('sessionAuthWeb')->prefix('/')->group(function () {
    Route::get('/', [LoginController::class, 'login_page'])->name('LoginPage');
    Route::post('login', [LoginController::class, 'login'])->name('LoginUser');
    Route::get('register-page', [UserController::class, 'get'])->name('UserRegister');
    Route::post('register', [UserController::class, 'store'])->name('Register');
    Route::get('forgot-password-page', [LoginController::class, 'forgetPasswordPage'])->name('password.forget');
    Route::post("forget/password", [LoginController::class, 'forget_password_email_verification'])->name("ResetPasswordRequest");
    Route::get('reset-password', [LoginController::class, 'resetpasswordotp'])->name('resetPasswordOtp');
    Route::post("logout", [LoginController::class, 'logout'])->name("Logout");
    Route::get('update-password', [LoginController::class, 'ConfirmToeknPage'])->name('GetTokenPage');
    Route::post('update/user-password', [LoginController::class, 'reset_password'])->name('verifyToken');
});