<?php

use App\Http\Controllers\Api\BffController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| BFF Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "bff-api" middleware group. Enjoy building your API!
|
*/

Route::get('/aggregation', [BffController::class, 'aggregation'])
    ->middleware('cacheResponse:5');

Route::match(['GET', 'HEAD', 'OPTIONS'], '{uri}', [BffController::class, 'request'])
    ->where('uri', '.*')
    ->fallback();

Route::delete('{uri}', [BffController::class, 'request'])
    ->middleware('throttle:bff-write')
    ->where('uri', '.*')
    ->fallback();

Route::match(['POST', 'PUT', 'PATCH'], '{uri}', [BffController::class, 'multipartRequest'])
    ->middleware('throttle:bff-write')
    ->where('uri', '.*')
    ->fallback();
