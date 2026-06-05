<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/status', fn () => [
    'system' => 'LGU TRENTO PDS Management System',
    'status' => 'protected',
]);
