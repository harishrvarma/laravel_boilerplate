<?php

use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Controllers\ApiServiceController;


Route::post('/generateToken', [ApiServiceController::class, 'generateToken'])->defaults('label', 'Generate Token');
