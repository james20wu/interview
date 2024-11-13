<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'orders' => OrderController::class
], [
    'only' => ['store', 'show']
]);
