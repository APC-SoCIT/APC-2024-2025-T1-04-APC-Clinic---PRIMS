<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accomplishment Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        h2 { margin-bottom: 10px; text-align: center; }
        h3 { margin-top: 20px; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .summary th { color: white; }
        .total { background-color: #2196F3; }
        .attended { background-color: #2563EB; }
        .cancelled { background-color: #FF5733; }
        .submitted { margin-top: 30px; text-align: left; }
        .submitted-line { margin-top: 40px; width: 300px; border-top: 1px solid black; }
    </style>
</head>
<body>
    <!-- Header -->
    <table style="width: 100%; border: none; margin-bottom: 20px;">
        <tr>
            <td style="border: none; vertical-align: top;">
                <p><strong>SUBJECT:</strong> Accomplishment Report</p>
                <p>
                    <strong>PERIOD:</strong>
                    @if($type === 'monthly')
                        {{ date('F', mktime(0, 0, 0, $month ?? now()->month, 10)) }} {{ $year }}
                    @else
                        Year {{ $year }}
                    @endif
                </p>
            </td>
            <td style="border: none; text-align: right; vertical-align: top;">
                <img src="{{ public_path('img/apc_logo.png') }}" alt="Clinic Logo" style="height: 60px;">
            </td>
        </tr>
    </table>

    <h2>Clinic Accomplishment Report</h2>

    <!-- Summary -->
    <table class="summary">
        <thead>
            <tr>
                <th class="total">Total Patients</th>
                <th class="attended">Attended</th>
                <th class="cancelled">Cancelled</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $totalPatients }}</td>
                <td>{{ $attendedCount }}</td>
                <td>{{ $cancelledCount }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Diagnoses -->
    @foreach ($categorizedDiagnoses as $category => $diagnoses)
        <h3>{{ strtoupper($category) }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Diagnosis</th>
                    <th>Patient Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($diagnoses as $diagnosis)
                    <tr>
                        <td>{{ $diagnosis['name'] }}</td>
                        <td>{{ $diagnosis['count'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <!-- Submitted -->
    <div class="submitted">
        <p><strong>Submitted by:</strong></p>
        <div class="submitted-line"></div>
        <div style="height: 60px;"></div>
        <p><strong>Date Submitted:</strong> {{ date('F d, Y') }}</p>
    </div>
</body>
</html>
