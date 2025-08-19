<?php

// Add these routes to your other Laravel project's routes/web.php file

use App\Http\Controllers\AppointmentController;

// Appointment routes
Route::prefix('appointments')->name('appointments.')->group(function () {
    // Authentication routes
    Route::get('/login', [AppointmentController::class, 'showLogin'])->name('login');
    Route::post('/login', [AppointmentController::class, 'login'])->name('login.post');
    Route::post('/logout', [AppointmentController::class, 'logout'])->name('logout');

    // Protected routes (require authentication)
    Route::middleware('auth.appointments')->group(function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::get('/create', [AppointmentController::class, 'create'])->name('create');
        Route::post('/', [AppointmentController::class, 'store'])->name('store');
        Route::get('/{id}', [AppointmentController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AppointmentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AppointmentController::class, 'update'])->name('update');
        Route::delete('/{id}', [AppointmentController::class, 'destroy'])->name('destroy');
        
        // Additional routes
        Route::get('/statistics/overview', [AppointmentController::class, 'statistics'])->name('statistics');
        Route::post('/bulk-update-status', [AppointmentController::class, 'bulkUpdateStatus'])->name('bulk-update');
        Route::get('/date-range/search', [AppointmentController::class, 'dateRange'])->name('date-range');
    });
});

// Optional: Create a middleware to check API authentication
// Add this to app/Http/Middleware/CheckAppointmentApiAuth.php
/*
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckAppointmentApiAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = Cache::get('appointment_api_token');
        
        if (!$token) {
            return redirect()->route('appointments.login')
                ->with('error', 'Please login to access appointments');
        }

        return $next($request);
    }
}
*/

// Register the middleware in app/Http/Kernel.php
// protected $routeMiddleware = [
//     // ... other middlewares
//     'auth.appointments' => \App\Http\Middleware\CheckAppointmentApiAuth::class,
// ]; 