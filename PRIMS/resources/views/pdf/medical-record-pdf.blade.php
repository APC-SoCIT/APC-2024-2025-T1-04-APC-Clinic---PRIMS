<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Record - {{ $patient->first_name }} {{ $patient->last_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #000;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        Medical Record - {{ $patient->first_name }} {{ $patient->last_name }}
    </div>

    <!-- Patient Information -->
    <div class="section-title">Patient Information</div>
    <table>
        <tr>
            <th>Name</th>
            <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
        </tr>
        <tr>
            <th>Gender</th>
            <td>{{ $patient->gender }}</td>
        </tr>
        <tr>
            <th>Date of Birth</th>
            <td>{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('F j, Y') }}</td>
        </tr>
        <tr>
            <th>Contact</th>
            <td>{{ $patient->contact_number }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $patient->email }}</td>
        </tr>
    </table>

    <!-- Reason for Visit -->
    <div class="section-title">Reason for Visit</div>
    <table>
        <tr>
            <td>{{ $appointment->reason_for_visit ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- Description of Symptoms -->
    <div class="section-title">Description of Symptoms</div>
    <table>
        <tr>
            <td>{{ $appointment->description_of_symptoms ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- Allergies -->
    <div class="section-title">Allergies</div>
    <table>
        <tr>
            <td>{{ $appointment->allergies ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- Past Medical History -->
    <div class="section-title">Past Medical History</div>
    <table>
        <tr>
            <th>Condition</th>
            <th>Status</th>
        </tr>
        @if($appointment->mumps)
        <tr>
            <td>Mumps</td>
            <td>Patient has had Mumps</td>
        </tr>
        @endif
        @if($appointment->heart_disorder)
        <tr>
            <td>Heart Disorder</td>
            <td>Patient has a history of Heart Disorder</td>
        </tr>
        @endif
        @if($appointment->bleeding_problem)
        <tr>
            <td>Bleeding Problem</td>
            <td>Patient has a history of Bleeding Problems</td>
        </tr>
        @endif
        @if($appointment->hepatitis)
        <tr>
            <td>Hepatitis</td>
            <td>Patient has had Hepatitis (Type: {{ $appointment->hepatitis_type ?? 'N/A' }})</td>
        </tr>
        @endif
        @if($appointment->chicken_pox)
        <tr>
            <td>Chicken Pox</td>
            <td>Patient has had Chicken Pox</td>
        </tr>
        @endif
        @if($appointment->dengue)
        <tr>
            <td>Dengue</td>
            <td>Patient has had Dengue</td>
        </tr>
        @endif
        @if($appointment->kidney_disease)
        <tr>
            <td>Kidney Disease</td>
            <td>Patient has a history of Kidney Disease</td>
        </tr>
        @endif
        @if($appointment->covid19)
        <tr>
            <td>Covid-19</td>
            <td>Patient has had Covid-19</td>
        </tr>
        @endif
    </table>

    <!-- Family History -->
    <div class="section-title">Family History</div>
    <table>
        <tr>
            <th>Condition</th>
            <th>Status</th>
        </tr>
        @if($appointment->bronchial_asthma)
        <tr>
            <td>Bronchial Asthma</td>
            <td>Family history of Bronchial Asthma</td>
        </tr>
        @endif
        @if($appointment->diabetes_mellitus)
        <tr>
            <td>Diabetes Mellitus</td>
            <td>Family history of Diabetes Mellitus</td>
        </tr>
        @endif
        @if($appointment->thyroid_disorder)
        <tr>
            <td>Thyroid Disorder</td>
            <td>Family history of Thyroid Disorder</td>
        </tr>
        @endif
        @if($appointment->cancer)
        <tr>
            <td>Cancer</td>
            <td>Family history of Cancer</td>
        </tr>
        @endif
        @if($appointment->hypertension)
        <tr>
            <td>Hypertension</td>
            <td>Family history of Hypertension</td>
        </tr>
        @endif
        @if($appointment->liver_disease)
        <tr>
            <td>Liver Disease</td>
            <td>Family history of Liver Disease</td>
        </tr>
        @endif
        @if($appointment->epilepsy)
        <tr>
            <td>Epilepsy</td>
            <td>Family history of Epilepsy</td>
        </tr>
        @endif
    </table>

    <!-- Personal & Social History -->
    <div class="section-title">Personal & Social History</div>
    <table>
        <tr>
            <th>Condition</th>
            <th>Details</th>
        </tr>
        <tr>
            <td>Smoking</td>
            <td>{{ $appointment->smoke ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Alcohol Consumption</td>
            <td>{{ $appointment->alcohol_consumption ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Vape</td>
            <td>{{ $appointment->vape ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- Medical Findings -->
    <div class="section-title">Medical Findings</div>
    <table>
        <tr>
            <th>Physical Examination</th>
            <td>{{ $appointment->physical_examination ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Diagnosis</th>
            <td>{{ $appointment->diagnosis ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Prescription</th>
            <td>{{ $appointment->prescription ?? 'N/A' }}</td>
        </tr>
    </table>

</body>
</html>
