<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResearchController;
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

Route::middleware('basic.auth')->group(function () {
    // ADMIN ROUTES
    Route::prefix('admin')->group(function() {
        Route::prefix('conduct-research')->group(function() {
            Route::post('/folders/{folder:uuid?}', [AdminController::class, 'store'])->name('api.admin.folders.store');
            Route::post('/folders/{folder:uuid}/files', [AdminController::class, 'addFile'])->name('api.admin.folders.file');
            Route::get('/folders', [AdminController::class, 'index'])->name('api.admin.folders.index');
            Route::get('/folders/{folder:uuid}', [AdminController::class, 'show'])->name('api.admin.folders.show');
            Route::get('/files/{file:uuid}', [AdminController::class, 'showFile'])->name('api.admin.folders.file.show');
            Route::get('/search', [AdminController::class, 'search'])->name('api.admin.folders.search');

            Route::post('/folder/delete', [AdminController::class, 'deleteFolder'])->name('api.admin.folders.delete');
            Route::post('/file/delete', [AdminController::class, 'deleteFile'])->name('api.admin.files.delete');
        });
        
        Route::prefix('request-information')->group(function() {
            Route::post('/create', [AdminController::class, 'requestInformation'])->name('api.admin.request.create');
            Route::get('/requests', [AdminController::class, 'requestInformationIndex'])->name('api.admin.requests.index');
            Route::get('/requests/{research}', [AdminController::class, 'requestInformationShow'])->name('api.admin.requests.show');
            Route::post('/requests/{research}', [AdminController::class, 'requestInformationUpdate'])->name('api.admin.requests.update');
        });
    });


    // USER ROUTES
    Route::prefix('user')->group(function() {
        Route::prefix('conduct-research')->group(function() {
            Route::get('/folders', [ResearchController::class, 'index'])->name('api.folders.index');
            Route::get('/folders/{folder:uuid}', [ResearchController::class, 'show'])->name('api.folders.show');
            Route::get('/files/{file:uuid}', [ResearchController::class, 'showFile'])->name('api.folders.file.show');
            Route::get('/search', [ResearchController::class, 'search'])->name('api.folders.search');
        });

        Route::prefix('request-information')->group(function() {
            Route::post('/create', [ResearchController::class, 'requestInformation'])->name('api.request.create');
            Route::post('/requests', [ResearchController::class, 'requestInformationIndex'])->name('api.requests.index');
            Route::post('/requests/{research}', [ResearchController::class, 'requestInformationShow'])->name('api.requests.show');
        });
    });

});
