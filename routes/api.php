<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// routes the request to url http://mydomain/api/historicaldata
// to the ApiController in the getHistoricalData function

use Illuminate\Support\Facades\Route;
use E4nar\Xm\Http\Controllers\Api\ApiController;

Route::get('/historical/data', [ApiController::class, 'getHistoricalData'])->name('api_get_historical_data');
