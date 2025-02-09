@extends('layouts.app')

@section('title', 'Summary Report')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Summary Report
                </h2>

                <!-- Month and Year Selection -->
                <div class="flex space-x-4 mt-4">
                    <form method="GET" action="{{ route('summary-report') }}" class="flex space-x-2">
                        <select name="month" class="border rounded p-2">
                            @foreach([
                                'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
                            ] as $m)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                        <select name="year" class="border rounded p-2">
                            @for ($y = now()->year; $y >= now()->year - 10; $y--)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Filter</button>
                    </form>
                </div>

                <!-- Overview Statistics -->
                <div class="grid grid-cols-3 gap-2 mt-6">
                    <div class="bg-yellow-100 p-3 rounded shadow flex flex-col items-center">
                        <h3 class="text-md font-semibold">Appointments</h3>
                        <p class="text-3xl font-bold">{{ $appointments ?? 'N/A' }}</p>
                        <div class="chart-container" style="height: 150px;">
                            <canvas id="appointmentChart"></canvas>
                        </div>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded shadow flex flex-col items-center">
                        <h3 class="text-md font-semibold">Patients</h3>
                        <p class="text-3xl font-bold">{{ $patients ?? 'N/A' }}</p>
                        <div class="chart-container" style="height: 150px;">
                            <canvas id="patientsChart"></canvas>
                        </div>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded shadow flex flex-col items-center">
                        <h3 class="text-md font-semibold">Visitors</h3>
                        <p class="text-3xl font-bold">{{ $visitors ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Patient Count Graph -->
                <div class="bg-gray-100 p-4 rounded shadow mt-6">
                    <h3 class="text-lg font-semibold">Patient Count Graph</h3>
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="patientChart"></canvas>
                    </div>
                </div>

                <!-- Medical Reports -->
                <div class="grid grid-cols-2 gap-4 mt-6">
                    <div class="bg-gray-100 p-4 rounded shadow">
                        <h3 class="text-md font-semibold">Primary Reasons for Clinic Visits</h3>
                        <div class="chart-container" style="height: 200px;">
                            <canvas id="reasonsChart"></canvas>
                        </div>
                    </div>
                    <div class="bg-gray-100 p-4 rounded shadow">
                        <h3 class="text-md font-semibold">Most Commonly Prescribed Medications</h3>
                        <div class="chart-container" style="height: 200px;">
                            <canvas id="medicationsChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button class="px-4 py-2 bg-blue-500 text-white rounded">Generate a summary report</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Appointments Chart (Doughnut)
    var appointmentCtx = document.getElementById('appointmentChart').getContext('2d');
    new Chart(appointmentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Attended', 'Cancelled'],
            datasets: [{
                data: [{{ $appointments * 0.8 }}, {{ $appointments * 0.2 }}],
                backgroundColor: ['#4CAF50', '#FF0000']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Patients Chart (Bar)
    var patientsCtx = document.getElementById('patientsChart').getContext('2d');
    new Chart(patientsCtx, {
        type: 'bar',
        data: {
            labels: ['Male', 'Female', 'Other'],
            datasets: [{
                data: [30, 20, 2], // Example data
                backgroundColor: ['#3498db', '#e74c3c', '#9b59b6']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Patient Count Line Chart
    var patientCtx = document.getElementById('patientChart').getContext('2d');
    new Chart(patientCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Patient Count',
                data: {!! json_encode($patientCounts) !!},
                borderColor: '#007BFF',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                fill: true
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Pie Chart for Primary Reasons
    var reasonsCtx = document.getElementById('reasonsChart').getContext('2d');
    new Chart(reasonsCtx, {
        type: 'pie',
        data: {
            labels: ['Flu', 'Check-up', 'Consultation'],
            datasets: [{
                data: [60, 25, 15],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Bar Chart for Most Commonly Prescribed Medications
    var medicationsCtx = document.getElementById('medicationsChart').getContext('2d');
    new Chart(medicationsCtx, {
        type: 'bar',
        data: {
            labels: ['Drug 1', 'Drug 2', 'Drug 3', 'Drug 4', 'Drug 5'],
            datasets: [{
                data: [75, 50, 20, 60, 30],
                backgroundColor: '#3498db'
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
});
</script>

@endsection
