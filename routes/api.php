<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\EmployeeController;

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

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);

Route::group(['middleware' => ['web']], function () {
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
    Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
});

Route::middleware('auth:api')->group(function () {
    /* Company Routes - Start */
    Route::get('company/get', [CompanyController::class, 'show']);
    Route::post('company/create', [CompanyController::class, 'store']);
    Route::post('company/edit', [CompanyController::class, 'update']);
    Route::delete('company/delete', [CompanyController::class, 'delete']);
    /* Company Routes - End */

    /* Employee Routes - Start */
    Route::post('employee/create', [EmployeeController::class, 'store']);
    Route::post('employee/edit', [EmployeeController::class, 'update']);
    Route::delete('employee/delete', [EmployeeController::class, 'delete']);
    /* Employee Routes - End */

    Route::post('logout', [LoginController::class, 'logout']);
});
