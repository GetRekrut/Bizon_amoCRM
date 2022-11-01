<?php

use App\Http\Controllers\AmoAccessController;
use App\Http\Controllers\BizonAccessController;
use App\Http\Controllers\BizonHandleReportsController;
use App\Http\Controllers\BizonHookController;
use App\Http\Controllers\HandleParticipants\UoppolyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

//test
Route::post('/amocrm/checkAccount', [AmoAccessController::class, 'showAccount']);
Route::post('/bizon/takeHookLitelly', [BizonHookController::class, 'takeHookLitelly']);
//Route::get('/amocrm/show', [AmoAccessController::class, 'showAccount']);

//dev
Route::post('/amocrm/addAccount', [AmoAccessController::class, 'addAccount']);
Route::post('/bizon/addAccount', [BizonAccessController::class, 'addAccount']);

Route::post('/bizon/takeHookAstrology', [BizonHookController::class, 'takeHookAstrology']);
Route::post('/bizon/takeHookMatrix',    [BizonHookController::class, 'takeHookMatrix']);

Route::get('/bizon/handleHooks', [BizonHookController::class, 'handleHooks']);
Route::get('/bizon/handleReport', [BizonHandleReportsController::class, 'handleReport']);

Route::get('/amocrm/checkParticipant', [UoppolyController::class, 'checkNewParticipant']);
Route::get('/addNewParticipant', [UoppolyController::class, 'addNewParticipant']);



