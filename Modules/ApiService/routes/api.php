<?php

use Illuminate\Support\Facades\Route;
use Modules\ApiService\Http\Controllers\ApiServiceController;


Route::post('/generateToken', [ApiServiceController::class, 'generateToken']);
