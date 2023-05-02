<?php

/**
 * ----------------------------------------
 * Ticket routes
 * Prefix /tickets
 * ----------------------------------------
 */

use App\Http\Controllers\Api\V1\TicketController;

Route::get('list', [TicketController::class, 'list']);
Route::post('create', [TicketController::class, 'create']);
