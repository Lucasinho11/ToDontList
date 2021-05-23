<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\PostController;
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

Route::post('auth/register', [ApiTokenController::class, 'register']);
Route::post('auth/login', [ApiTokenController::class, 'login']);
Route::middleware('auth:sanctum')->post('auth/me', [ApiTokenController::class, 'me']);
Route::middleware('auth:sanctum')->post('auth/logout', [ApiTokenController::class, 'logout']);

 Route::middleware('auth:sanctum')->get('posts', [PostController::class, 'index']);
 Route::middleware('auth:sanctum')->get('posts/{id}', [PostController::class, 'show']);
 Route::middleware('auth:sanctum')->post('posts/create', [PostController::class, 'create']);
 Route::middleware('auth:sanctum')->post('posts/update/{id}', [PostController::class, 'update']);
 Route::middleware('auth:sanctum')->get('posts/delete/{id}', [PostController::class, 'delete']);