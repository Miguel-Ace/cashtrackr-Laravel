<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BudgetChatController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\SubscriptionCheckoutController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TicketScanController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    Route::get('/budget/{budget}', [BudgetController::class, 'show'])->name('budget.show');
    Route::get('budget/{budget}/edit', [BudgetController::class, 'edit'])->name('budget.edit');
    Route::put('budget/{budget}', [BudgetController::class, 'update'])->name('budget.update');
    Route::delete('budget/{budget}', [BudgetController::class, 'destroy'])->name('budget.destroy');

    Route::post('budget/{budget}/expense', [ExpenseController::class, 'store'])->name('expense.store');
    Route::put('/budget/{budget}/expense/{expense}', [ExpenseController::class, 'update'])->name('expense.update');
    Route::delete('/budget/{budget}/expense/{expense}', [ExpenseController::class, 'destroy'])->name('expense.destroy');
});

Route::middleware(['auth','verified','subscribed'])->group(function() {
    Route::post('/budget/{budget}/chat', [BudgetChatController::class, 'store'])->name('budget.chat');
    Route::post('/budget/{budget}/scan-ticket', [TicketScanController::class, 'store'])->name('budget.scan-ticket');
});

Route::middleware(['auth', 'verified'])->group(function() {
    Route::post('/subscription-checkout/{plan}', [SubscriptionCheckoutController::class, 'store'])->name('subscription.checkout')->whereIn('plan', ['monthly','yearly']);

    Route::view('/billing/success', 'billing.success')->name('billing.success');
    Route::view('/billing/cancel', 'billing.cancel')->name('billing.cancel');

    Route::get('/plans', function() {
        return Inertia::render('Pro/Plans');
    })->name('plans');

    Route::get('/subscription', [SubscriptionController::class, 'show'])
        ->name('subscription.manage');

    Route::post('/subscription/swap/{plan}', [SubscriptionController::class, 'swap'])
        ->name('subscription.swap')
        ->whereIn('plan', ['monthly', 'yearly']);

    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])
        ->name('subscription.cancel');

    Route::post('/subscription/resume', [SubscriptionController::class, 'resume'])
        ->name('subscription.resume');

    Route::get('/billing', function (Request $request) {
        return $request->user()->redirectToBillingPortal(route('dashboard'));
    })->name('billing');
});