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

// These are login routes that redirect users to their respective dashboards based on their roles.
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->hasRole('clinic staff')) {
            return redirect()->route('calendar');
        } elseif ($user->hasRole('patient')) {
            return redirect()->route('patient-homepage');
        }

        abort(403, 'Unauthorized action.');
    })->name('dashboard');

    // Calendar route for staff
    Route::get('/staff/calendar', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403); // Forbidden
        }
        return view('staff-calendar');
    })->name('calendar');

    // Patient homepage route
    Route::get('/homepage', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('patient')) {
            abort(403); // Forbidden
        }
        return view('welcome');
    })->name('patient-homepage');

    // These are routes for appointment management, including viewing, creating, and handling notifications.

    // Calendar route for patients
    Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');

    // Route for storing appointment data and sending notification
    Route::post('/appointment/notif', [AppointmentController::class, 'store'])
        ->name('appointment.notif')
        ->middleware('auth');

    // Appointment History route
    Route::get('/appointment-history', [AppointmentController::class, 'showAppointmentHistory'])->name(
        'appointment-history',
    );

    // Route for printing medical record
    Route::get('/print-medical-record/{appointmentId}', [MedicalRecordController::class, 'printMedicalRecord'])->name(
        'print.medical.record',
    );

    //These are routes for inventory management

    // Inventory route
    Route::get('/staff/inventory', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403); // Forbidden
        }
        return view('medical-inventory');
    })->name('medical-inventory');

    // Add Medicine route
    Route::get('/staff/add-medicine', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403); // Forbidden
        }
        return view('add-medicine');
    })->name('add-medicine');

    // Route for storing medicine data
    Route::post('/staff/inventory/add', [InventoryController::class, 'store'])->name('inventory.store');

    // Showing specific medicine details
    Route::get('/staff/inventory/{id}', [InventoryController::class, 'show'])->name('inventory.show');

    // These are routes for medical records management

    // Medical records routes
    Route::get('/staff/medical-records', function () {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403); // Forbidden
        }
        return view('medical-records');
    })->name('medical-records');

    // Route for adding a new medical record for a patient
    Route::get('/addRecordmain', [MedicalRecordController::class, 'create'])->name('add-medical-record');

    Route::get('/medical-records/{id}', [MedicalRecordController::class, 'view'])->name('view-medical-record');

    Route::get('/archived-records', [MedicalRecordController::class, 'archiveRecord'])->name('archived-records');

    // Route to open Add Medical Record during appointment
    Route::get('/staff/add-record', function (Illuminate\Http\Request $request) {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403);
        }

        return view('addRecordmain', [
            'appointment_id' => $request->query('appointment_id'),
            'fromStaffCalendar' => $request->query('fromStaffCalendar', false),
        ]);
    })->name('addRecordmain');

    //Route for summary report generation and viewing

    // Summary report routes
    Route::get('/staff/summary-report', [StaffSummaryReportController::class, 'index'])->name('summary-report');

    // Route for generating accomplishment report
    Route::get('/staff/generate-accomplishment-report', [
        StaffSummaryReportController::class,
        'generateAccomplishmentReport',
    ])->name('generate.accomplishment.report');

    // About us Button Route
    Route::get('/about-us', function () {
        return view('about-us');
    })->name('about');

    // Feedback route
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
});
