<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\AppointmentHistoryController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ClinicStaffController;
use App\Mail\AppointmentNotif;

$url = config('app.url');
URL::forceRootUrl($url);

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->hasRole('clinic staff')) {
            return redirect()->route('staff-dashboard');
        } elseif ($user->hasRole('patient')) {
            return redirect()->route('patient-homepage');
        }

        abort(403, 'Unauthorized action.');
        
    })->name('dashboard');

    // Staff dashboard route
    Route::get('/staff/dashboard', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403); // Forbidden
        }
        return view('staff-dashboard');
    })->name('staff-dashboard');

    // Patient homepage route
    Route::get('/homepage', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('patient')) {
            abort(403); // Forbidden
        }
        return view('welcome');
    })->name('patient-homepage');

    // Appointment route
    Route::get('/appointment', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('patient')) {
            abort(403); // Forbidden
        }
        return view('patient-calendar');
    })->name('appointment');

    Route::post('/appointment/notif', [AppointmentController::class, 'store'])
    ->name('appointment.notif')
    ->middleware('auth');

    // Inventory route
    Route::get('/staff/inventory', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403); // Forbidden
        }
        return view('medical-inventory');
    })->name('medical-inventory');

    // Medical records route
    Route::get('/staff/medical-records', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403); // Forbidden
        }
        return view('medical-records');
    })->name('medical-records');

    // Summary report route
    Route::get('/staff/summary-report', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403); // Forbidden
        }
        return view('staff-summary-report');
    })->name('summary-report');

    // Calendar route
    Route::get('/staff/calendar', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403); // Forbidden
        }
        return view('staff-calendar');
    })->name('calendar');

    // Appointment History route
    Route::get('/appointment-history', [AppointmentHistoryController::class, 'showAppointmentHistory'])
    ->name('appointment-history');

    // Add Record route
    Route::get('/staff/add-record', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403); // Forbidden
        }
        return view('addRecordmain');
    })->name('addRecordmain');

     // Test route
     Route::get('/test', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('patient')) {
            abort(403); // Forbidden
        }
        return view('test');
    })->name('test');

});
