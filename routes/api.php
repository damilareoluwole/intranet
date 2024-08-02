<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\TechnologyGuide\AdminTechnologyGuideController;
use App\Http\Controllers\TechnologyGuide\TechnologyGuideController;
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

        Route::prefix('technology-guide')->group(function() {
            Route::post('/folders/{folder:uuid?}', [AdminTechnologyGuideController::class, 'store'])->name('api.admin.hrfolders.store');
            Route::post('/folders/{folder:uuid}/files', [AdminTechnologyGuideController::class, 'addFile'])->name('api.admin.hrfolders.file');
            Route::get('/folders', [AdminTechnologyGuideController::class, 'index'])->name('api.admin.hrfolders.index');
            Route::get('/folders/{folder:uuid}', [AdminTechnologyGuideController::class, 'show'])->name('api.admin.hrfolders.show');
            Route::get('/files/{file:uuid}', [AdminTechnologyGuideController::class, 'showFile'])->name('api.admin.hrfolders.file.show');
            Route::get('/search', [AdminTechnologyGuideController::class, 'search'])->name('api.admin.hrfolders.search');

            Route::post('/folder/delete', [AdminTechnologyGuideController::class, 'deleteFolder'])->name('api.admin.hrfolders.delete');
            Route::post('/file/delete', [AdminTechnologyGuideController::class, 'deleteFile'])->name('api.admin.hrfiles.delete');
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

        Route::prefix('technology-guide')->group(function() {
            Route::get('/folders', [TechnologyGuideController::class, 'index'])->name('api.hrfolders.index');
            Route::get('/folders/{folder:uuid}', [TechnologyGuideController::class, 'show'])->name('api.hrfolders.show');
            Route::get('/files/{file:uuid}', [TechnologyGuideController::class, 'showFile'])->name('api.hrfolders.file.show');
            Route::get('/search', [TechnologyGuideController::class, 'search'])->name('api.hrfolders.search');
        });
    });

});
