<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::apiResource('tickets', TicketController::class);
    Route::patch('tickets/{ticket}/status', [TicketController::class, 'updateStatus']);
    Route::patch('tickets/{ticket}/assign', [TicketController::class, 'assign']);
    Route::get('reports/tickets-summary', [TicketController::class, 'ticketsSummary']);
    Route::get('reports/tickets-by-status', [TicketController::class, 'ticketsByStatus']);
    Route::post('tickets/{ticket}/comments', [TicketController::class, 'storeComment']);
});