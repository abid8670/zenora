<?php

use App\Http\Controllers\SupportTicketController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Support Ticket Form
Route::get('/support', [SupportTicketController::class, 'create'])->name('support-ticket.create');
Route::post('/support', [SupportTicketController::class, 'store'])->name('support-ticket.store');
