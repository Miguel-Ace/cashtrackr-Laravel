<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\LogoutController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/register', [RegisterController::class, 'index'])->name('register');
Route::post('/auth/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/auth/login', [LoginController::class, 'index'])->name('login');
Route::post('/auth/login', [LoginController::class, 'store'])->name('login.store');

Route::post('/auth/logout', [LogoutController::class, 'store'])->name('logout.store');

Route::get('/email/verify/{id}/{hash}', function(EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('dashboard')->with('success', 'Tu correo fue verificado correctamente. Ya puedes crear presupuestos y gastos.');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/email/verify', function() {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function(Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('success', 'Se ha reenviado el correo de verificación');
})->middleware(['auth', 'throttle:1,1'])->name('verification.send');



Route::middleware(['auth','verified'])->prefix('dashboard')->group(function() {
    Route::get('/', [BudgetController::class, 'index'])->name('dashboard');
    Route::get('/budget/create', [BudgetController::class, 'create'])->name('budget.create');
    Route::post('/budget', [BudgetController::class, 'store'])->name('budget.store');
    Route::get('budget/{budget}/edit', [BudgetController::class, 'edit'])->name('budget.edit');
    Route::put('budget/{budget}', [BudgetController::class, 'update'])->name('budget.update');
    Route::delete('budget/{budget}', [BudgetController::class, 'destroy'])->name('budget.destroy');
});