<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accomplishment Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { font-size: 16px; font-weight: bold; text-align: left; margin-bottom: 15px; }
    </style>
</head>
<body>
        <p><strong>SUBJECT:</strong> Monthly ACCOMPLISHMENT REPORT</p>
        <p><strong>PERIOD:</strong> {{ date('F', mktime(0, 0, 0, $month, 10)) }} {{ $year }}</p>
    </div>

    <h2>Clinic Accomplishment Report</h2>

    <!-- 🔹 Diagnosis Data (Categorized) -->
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

    <br><br>

</body>
</html>
