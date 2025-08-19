<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\AppointmentController;

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

// Public routes (if any)
Route::post('/login', [App\Http\Controllers\API\LoginController::class, 'login']);

// Service Account routes (for external applications)
Route::post('/service-account/generate-token', [App\Http\Controllers\API\ServiceAccountController::class, 'generateServiceToken']);
Route::post('/service-account/authenticate', [App\Http\Controllers\API\ServiceAccountController::class, 'authenticateWithServiceToken']);

// Public test route for appointment update (temporary)
Route::put('/appointments/{id}/public-test', [AppointmentController::class, 'testUpdate']);

// Raw test route for debugging
Route::put('/appointments/{id}/raw-test', [AppointmentController::class, 'rawTestUpdate']);

// Protected routes with Sanctum authentication
Route::middleware('auth:sanctum')->group(function () {
    
    // User profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Appointment routes
    Route::prefix('appointments')->group(function () {
        Route::get('/', [AppointmentController::class, 'index']);
        Route::post('/', [AppointmentController::class, 'store']);
        
        // Specific routes must come before parameterized routes
        Route::get('/calendar', [AppointmentController::class, 'calendar']);
        Route::get('/statistics/overview', [AppointmentController::class, 'statistics']);
        Route::get('/date-range/search', [AppointmentController::class, 'getByDateRange']);
        
        // Parameterized routes come after specific routes
        Route::get('/{id}', [AppointmentController::class, 'show']);
        Route::put('/{id}', [AppointmentController::class, 'update']);
        Route::patch('/{id}', [AppointmentController::class, 'update']);
        Route::delete('/{id}', [AppointmentController::class, 'destroy']);
        Route::put('/{id}/test', [AppointmentController::class, 'testUpdate']);
        
        // Additional appointment endpoints
        Route::post('/bulk-update-status', [AppointmentController::class, 'bulkUpdateStatus']);
    });
    
    // Logout
    Route::post('/logout', [App\Http\Controllers\API\LoginController::class, 'logout']);
    
    // Service Account management (admin only)
    Route::prefix('service-account')->group(function () {
        Route::get('/list', [App\Http\Controllers\API\ServiceAccountController::class, 'listServiceAccounts']);
        Route::post('/{id}/deactivate', [App\Http\Controllers\API\ServiceAccountController::class, 'deactivateServiceAccount']);
    });
});



