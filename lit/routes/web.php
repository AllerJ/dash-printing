<?php

use Illuminate\Support\Facades\Route;
use Lit\Http\Controllers\WelcomeController;
use Lit\Http\Controllers\Crud\InventoryController;

Route::get('/', WelcomeController::class);
Route::post('/inventories/byid', [InventoryController::class, 'byid']);
