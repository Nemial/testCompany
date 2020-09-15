<?php

use App\Http\Controllers\FilesJsonController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * @OA\Info(title="FilesJSON API", version="1.0"),
 */

/**
 * @OA\Get (
 *      path="/files/{id}/export",
 *      summary="Export JSON File",
 *      @OA\Response(response="200", description="jsonData")
 * )
 */
Route::get('files/{id}/export', [FilesJsonController::class, 'export']);
/**
 * @OA\Post (
 *      path="/files",
 *      summary="Create New JSON File",
 *     @OA\Response(response="200", description="ID")
 * )
 */
Route::post('files', [FilesJsonController::class, 'store']);
/**
 * @OA\Put (
 *      path="/files/{id}",
 *      summary="Update JSON File",
 *      @OA\Response(response="200", description="Updated")
 * )
 */
Route::put('files/{id}', [FilesJsonController::class, 'update']);
/**
 * @OA\Delete (
 *      path="/files/{id}",
 *      summary="Delete JSON File",
 *     @OA\Response(response="200", description="Deleted")
 * )
 */
Route::delete('files/{id}', [FilesJsonController::class, 'destroy']);
