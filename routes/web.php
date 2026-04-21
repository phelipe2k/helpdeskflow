<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::middleware('throttle:10,1')->post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $totalOpen = \App\Models\Ticket::where('status', '!=', 'resolvido')->count();
        $byStatus = \App\Models\Ticket::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status');
        $byPriority = \App\Models\Ticket::selectRaw('priority, count(*) as count')->groupBy('priority')->pluck('count', 'priority');
        $resolvedThisMonth = \App\Models\Ticket::where('status', 'resolvido')->whereMonth('closed_at', now()->month)->count();
        return view('dashboard', compact('totalOpen', 'byStatus', 'byPriority', 'resolvedThisMonth'));
    })->name('dashboard');

    Route::resource('tickets', \App\Http\Controllers\TicketController::class);
    Route::post('tickets/{ticket}/comments', [\App\Http\Controllers\TicketController::class, 'storeComment'])->name('tickets.comments.store');

    Route::middleware('role:Administrator')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::post('users/{user}/reset-password', [\App\Http\Controllers\UserController::class, 'resetPassword'])->name('users.reset-password');
    });
});
