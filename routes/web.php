<?php

declare(strict_types=1);

use App\Http\Controllers\MeetupController;
use Hypervel\Support\Facades\Route;

Route::get('/', [MeetupController::class, 'home']);
