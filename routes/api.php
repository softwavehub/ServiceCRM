<?php

use App\Http\Controllers\Backend\LeadsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;

Route::get('/leads/{lead}/available-staff', [LeadsController::class, 'getAvailableStaff']);