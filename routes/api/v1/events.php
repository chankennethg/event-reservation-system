<?php


/**
 * ----------------------------------------
 * Event routes
 * Prefix /events
 * ----------------------------------------
 */

use App\Http\Controllers\Api\V1\EventController;

Route::get('list', [EventController::class, 'list']);
Route::post('create', [EventController::class, 'create']);
