<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\TarefasController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);
});

Route::post('/user',[UserController::class,'store']);
Route::post('/authenticated-user', [UserController::class, 'authenticated']);
Route::post('/send-email', [MailController::class, 'index']);
Route::get('/get-users/{id}', [UserController::class,'index']);
Route::post('/create-atividade',[TarefasController::class, 'store']);
Route::get('/atividades/{id}',[TarefasController::class, 'index']);
Route::get('/get-all-tarefas/{id}',[TarefasController::class,'allTarefas']);