<?php

use Illuminate\Support\Facades\Route;
use E4nar\Xm\Http\Controllers\View\HomeController;

Route::get('/', [HomeController::class, 'indexAction'])->name('home');
