<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Dispensed;
use App\Models\MedicalRecord;
use App\Models\Diagnosis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class StaffSummaryReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('clinic staff')) {
            abort(403);
        }

        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Appointment Summary (Patients Only)
        $attendedCount = Appointment::where('status', 'completed')
            ->whereMonth('appointment_date', $month)
            ->whereYear('appointment_date', $year)
            ->count();

        $cancelledCount = Appointment::where('status', 'cancelled')
            ->whereMonth('appointment_date', $month)
            ->whereYear('appointment_date', $year)
            ->count();

        $totalAppointments = $attendedCount + $cancelledCount;
        $totalPatients = User::whereHas('roles', function ($query) {
            $query->where('name', 'patient');
        })->count();

        // Most Prescribed Medications
        $medications = Dispensed::select('inventory_id', \DB::raw('SUM(quantity_dispensed) as total_dispensed'))
            ->whereMonth('date_dispensed', $month)
            ->whereYear('date_dispensed', $year)
            ->groupBy('inventory_id')
            ->orderByDesc('total_dispensed')
            ->take(5)
            ->get()
            ->map(function ($dispensed) {
                return [
                    'name' => $dispensed->inventory->supply->name,
                    'quantity_dispensed' => $dispensed->total_dispensed,
                ];
            });

        // Common Diagnoses
        $commonDiagnoses = Diagnosis::select('diagnosis', \DB::raw('COUNT(*) as count'))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('diagnosis')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        return view('staff-summary-report', [
            'attendedCount'   => $attendedCount,
            'cancelledCount'  => $cancelledCount,
            'totalAppointments' => $totalAppointments,
            'totalPatients'   => $totalPatients,
            'medications'     => $medications,
            'diagnoses'       => $commonDiagnoses,
            'selectedMonth'   => $month,
            'selectedYear'    => $year,
        ]);
    }

    public function generateAccomplishmentReport(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $to = $request->input('to');
        $submittedTo = $request->input('submittedTo');
        $staffName = Auth::user()->name;

        // Predefined categories of diagnoses
        $allDiagnoses = [
            'Cardiology' => ['Hypertension', 'BP Monitoring', 'Bradycardia', 'Hypotension', 'Angina'],
            'Pulmonology' => ['URTI', 'Pneumonia', 'PTB', 'Bronchitis', 'Lung Pathology'],
            'Gastroenterology' => ['Acute Gastroenteritis', 'GERD', 'Hemorrhoids', 'Anorexia'],
            'Neurology' => ['Tension Headache', 'Migraine', 'Vertigo', 'Insomnia'],
            'Endocrinology' => ['Diabetes Mellitus', 'Dyslipidemia'],
            'Nephrology' => ['Urinary Tract Infection'],
        ];

        // Count actual diagnoses occurrences from pivot
        $existingDiagnoses = Diagnosis::select('diagnosis', \DB::raw('COUNT(*) as count'))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('diagnosis')
            ->pluck('count', 'diagnosis')
            ->toArray();

        // Organizing Diagnoses into Categories
        $categorizedDiagnoses = [];
        foreach ($allDiagnoses as $category => $diagnoses) {
            $categorizedDiagnoses[$category] = [];
            foreach ($diagnoses as $diagnosis) {
                $categorizedDiagnoses[$category][] = [
                    'name'  => $diagnosis,
                    'count' => $existingDiagnoses[$diagnosis] ?? 0,
                ];
            }
        }

        $pdf = Pdf::loadView('pdf.report', compact(
            'month',
            'year',
            'to',
            'submittedTo',
            'staffName',
            'categorizedDiagnoses'
        ));

        return $pdf->download('accomplishment-report.pdf');
    }
}
