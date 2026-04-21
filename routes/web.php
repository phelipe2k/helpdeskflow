<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Autenticação - Rotas públicas
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::middleware('throttle:10,1')->post('/login', [AuthController::class, 'login']);

// Rotas protegidas - Requer autenticação
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', function () {
        $totalOpen = \App\Models\Ticket::where('status', '!=', 'resolvido')->count();
        $byStatus = \App\Models\Ticket::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status');
        $byPriority = \App\Models\Ticket::selectRaw('priority, count(*) as count')->groupBy('priority')->pluck('count', 'priority');
        $resolvedThisMonth = \App\Models\Ticket::where('status', 'resolvido')->whereMonth('closed_at', now()->month)->count();
        return view('dashboard', compact('totalOpen', 'byStatus', 'byPriority', 'resolvedThisMonth'));
    })->name('dashboard');

    // Tickets - Todas as ações protegidas
    Route::resource('tickets', \App\Http\Controllers\TicketController::class);
    Route::post('tickets/{ticket}/comments', [\App\Http\Controllers\TicketCommentController::class, 'store'])->name('tickets.comments.store');

    // Admin apenas
    Route::middleware('role:Administrator')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::post('users/{user}/reset-password', [\App\Http\Controllers\UserController::class, 'resetPassword'])->name('users.reset-password');
    });
});

// Redirecionar raiz para login ou dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});
