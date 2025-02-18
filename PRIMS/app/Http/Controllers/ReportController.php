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

        // Sample Data (palitan for db)
        $appointments = 120; 
        $patients = 85; 
        $visitors = 40; 

        $patientCounts = [50, 40, 30, 45, 60, 70, 80, 65, 55, 35, 25, 20]; 

        return view('staff-summary-report', compact('months', 'appointments', 'patients', 'visitors', 'patientCounts'));
    }
}
