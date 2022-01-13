<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdministrator;
use App\Http\Middleware\IsAuthenticated;
use App\Http\Middleware\IsNotAuthenticated;
use App\Http\Middleware\IsProjectManagerOrAdministrator;
use App\Http\Middleware\IsRegularOrProjectManager;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(IsNotAuthenticated::class)->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('showLogin');
    Route::post('login', [AuthController::class, 'login'])->name('loginUser');

    Route::get('register', [AuthController::class, 'register'])->name('showRegister');
    Route::post('register', [AuthController::class, 'registerUser'])->name('registerUser');
});

Route::middleware(IsAuthenticated::class)->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('showHome'); // todo change to the home
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    // User
    Route::get('/user/edit', [UserController::class, 'edit'])->name('editUser');
    Route::post('/user/edit', [UserController::class, 'update'])->name('updateUser');

    // Task
    Route::get('/task/{id}', [TaskController::class, 'show'])->name('showTask');
    Route::post('/task/{id}/comment', [TaskController::class, 'addComment'])->name('addComment');
    Route::get('/task/{id}/status/{new_status_id}', [TaskController::class, 'changeStatus'])
        ->name('changeStatus');

    // Project
    Route::get('/project', [ProjectController::class, 'index'])->name('showProjects');
    Route::get('/project/{id}', [ProjectController::class, 'show'])->name('showProject');
});

Route::middleware([IsAuthenticated::class, IsRegularOrProjectManager::class])
    ->group(function () {
        // User
        Route::get('/user/request/deactivation', [UserController::class, 'requestDeactivation'])
            ->name('deactivationRequest');
        Route::post('/user/request/status', [UserController::class, 'requestStatusChange'])
            ->name('statusChangeRequest');
    });

Route::middleware([IsAuthenticated::class, IsProjectManagerOrAdministrator::class])
    ->group(function () {
        // Task
        Route::get('/task/create', [TaskController::class, 'create'])->name('createTask');
        Route::post('/task/create', [TaskController::class, 'store'])->name('storeTask');
        Route::get('/task/{id}/edit', [TaskController::class, 'edit'])->name('editTask');
        Route::post('/task/{id}/edit', [TaskController::class, 'update'])->name('updateTask');
        Route::get('/task/{id}/delete', [TaskController::class, 'destroy'])->name('deleteTask');

        // Project
        Route::get('/project/create', [ProjectController::class, 'create'])->name('createProject');
        Route::post('/project/create', [ProjectController::class, 'store'])->name('storeProject');
        Route::get('/project/{id}/edit', [ProjectController::class, 'edit'])->name('editProject');
        Route::post('/project/{id}/edit', [ProjectController::class, 'update'])->name('updateProject');
        Route::get('/project/{id}/addTask/{task_id}', [ProjectController::class, 'addTask'])->name('addTask');
    });

Route::middleware([IsAuthenticated::class, IsAdministrator::class])->group(function () {
    // Project
    Route::get('/project/{id}/delete', [ProjectController::class, 'destroy'])->name('deleteProject');

    // Administration
    Route::get('admin', [AdminController::class, 'index'])->name('showAdminHome');

    Route::get('/admin/request/deactivation', [AdminController::class, 'indexDeactivationRequests'])
        ->name('showDeactivationRequests');
    Route::get('/admin/request/status', [AdminController::class, 'indexStatusChangeRequests'])
        ->name('showStatusChangeRequests');

    Route::get('/admin/request/deactivation/{id}', [AdminController::class, 'showDeactivationRequest'])
        ->name('showDeactivationRequest');
    Route::get('/admin/request/status/{id}', [AdminController::class, 'showStatusChangeRequest'])
        ->name('showStatusChangeRequest');

    Route::get('/admin/request/deactivation/{id}/accept', [AdminController::class, 'acceptDeactivationRequest'])
        ->name('acceptDeactivationRequest');
    Route::get('/admin/request/status/{id}/accept', [AdminController::class, 'acceptStatusChangeRequest'])
        ->name('acceptStatusChangeRequest');

    Route::get('/admin/request/deactivation/{id}/decline', [AdminController::class, 'declineDeactivationRequest'])
        ->name('declineDeactivationRequest');
    Route::get('/admin/request/status/{id}/decline', [AdminController::class, 'declineStatusChangeRequest'])
        ->name('declineStatusChangeRequest');
});
