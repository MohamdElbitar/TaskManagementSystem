<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

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


Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::resource('tasks', TaskController::class)->middleware([
        'index' => 'permission:view tasks',
        'show' => 'permission:view tasks',
        'store' => 'permission:create tasks',
        'update' => 'permission:update tasks',
        'destroy' => 'permission:delete tasks',
    ]);

    Route::post('/tasks/{taskId}/assign', [TaskController::class, 'assignTask'])->middleware('permission:assign tasks');
    Route::post('/tasks/{taskId}/dependencies', [TaskController::class, 'addDependency'])->middleware('permission:assign tasks');
    Route::get('/tasks/{taskId}/dependencies', [TaskController::class, 'getDependencies'])->middleware('permission:view tasks');
    Route::put('/tasks/{id}/status', [TaskController::class, 'updateStatus']);

    Route::get('/tasks/filter/status/{status}', [TaskController::class, 'filterByStatus'])->middleware('permission:view tasks');
    Route::get('/tasks/filter/due-date', [TaskController::class, 'filterByDueDateRange'])->middleware('permission:view tasks');
    Route::get('/tasks/filter/assignee/{assigneeId}', [TaskController::class, 'filterByAssignee'])->middleware('permission:view tasks');
});
