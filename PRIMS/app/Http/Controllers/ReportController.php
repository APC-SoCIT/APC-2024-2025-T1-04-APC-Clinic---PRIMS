<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function summaryReport(Request $request)
    {
        // Define months
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Sample Data (Replace with Database Query)
        $appointments = 120; // Replace with actual data count
        $patients = 85; // Replace with actual patient count
        $visitors = 40; // Replace with actual visitor count

        $patientCounts = [50, 40, 30, 45, 60, 70, 80, 65, 55, 35, 25, 20]; // Replace with real query

        return view('staff-summary-report', compact('months', 'appointments', 'patients', 'visitors', 'patientCounts'));
    }
}
