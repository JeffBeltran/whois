<?php

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

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('companies', 'CompanyController')->only([
        'index',
        'show',
    ]);
    Route::apiResource('degrees', 'DegreeController')->only(['index', 'show']);
    Route::apiResource('highlights', 'HighlightController')->only([
        'index',
        'show',
    ]);
    Route::apiResource('institutions', 'InstitutionController')->only([
        'index',
        'show',
    ]);
    Route::apiResource('jobs', 'JobController')->only(['index', 'show']);
    Route::apiResource('profiles', 'ProfileController')->only([
        'index',
        'show',
    ]);
    Route::apiResource('skills', 'SkillController')->only(['index', 'show']);
    Route::apiResource('users', 'UserController')->only(['index', 'show']);
});
