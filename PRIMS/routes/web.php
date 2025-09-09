<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StaffSummaryReportController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\FeedbackController;

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
            return redirect()->route('calendar');
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

    Route::get('/addRecordmain', [MedicalRecordController::class, 'create'])
    ->name('add-medical-record');

    Route::get('/medical-records/{id}', [MedicalRecordController::class, 'view'])
    ->name('view-medical-record');

    Route::get('/archived-records', [MedicalRecordController::class, 'archiveRecord'])
    ->name('archived-records');


    // Summary report route
    // Route::get('/staff/summary-report', function () {
    //     $user = Auth::user();
    //     if (!$user || !$user->hasRole('clinic staff')) {
    //         abort(403); // Forbidden
    //     }
    //     return view('staff-summary-report');
    // })->name('summary-report');

    Route::get('/staff/summary-report', [StaffSummaryReportController::class, 'index'])->name('summary-report');

    Route::get('/staff/generate-accomplishment-report', 
        [StaffSummaryReportController::class, 'generateAccomplishmentReport'])
        ->name('generate.accomplishment.report');


    // Calendar route
    Route::get('/staff/calendar', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403); // Forbidden
        }
        return view('staff-calendar');
    })->name('calendar');

    // Appointment History route
    Route::get('/appointment-history', [AppointmentController::class, 'showAppointmentHistory'])
    ->name('appointment-history');

    Route::get('/print-medical-record/{appointmentId}', [MedicalRecordController::class, 'printMedicalRecord'])->name('print.medical.record');
    

    // Add Record route  
    Route::get('/staff/add-record', function (Illuminate\Http\Request $request) {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403);
        }
    
        return view('addRecordmain', [
            'appointment_id' => $request->query('appointment_id'),
            'fromStaffCalendar' => $request->query('fromStaffCalendar', false)
        ]);
    })->name('addRecordmain');

     // Test route
     Route::get('/test', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('patient')) {
            abort(403); // Forbidden
        }
        return view('test');
    })->name('test');

    // Add Medicine route
    Route::get('/staff/add-medicine', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403); // Forbidden
        }
        return view('add-medicine');
    })->name('add-medicine');

    // About us
    Route::get('/about-us', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('patient')) {
            abort(403); // Forbidden
        }
        return view('about-us');
    })->name('about-us');

    // About us Button Route
    Route::get('/about-us', function () {
        return view('about-us');
    })->name('about');

    Route::post('/staff/inventory/add', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/staff/inventory/{id}', [InventoryController::class, 'show'])->name('inventory.show');

    // Feedback route
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    Route::get('/generate-accomplishment-report', 
        [App\Http\Controllers\StaffSummaryReportController::class, 'generateAccomplishmentReport']
    )->name('generate.accomplishment.report');
});