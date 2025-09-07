<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="py-3">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">
            <x-prims-sub-header>
                Summary Report
            </x-prims-sub-header>
        </div>
    </div>

    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        <!-- 🔹 Filter Form (Top) -->
        <form method="GET" action="{{ route('summary-report') }}" class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-semibold text-gray-800">Summary Report</h2>
                <div class="flex space-x-6">
                    <div>
                        <label class="font-semibold text-gray-700">Month:</label>
                        <select name="month" class="rounded-lg border-gray-300 p-2 w-32">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == $selectedMonth ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="font-semibold text-gray-700">Year:</label>
                        <select name="year" class="rounded-lg border-gray-300 p-2 w-32">
                            @for ($i = 2020; $i <= now()->year; $i++)
                                <option value="{{ $i }}" {{ $i == $selectedYear ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600">
                        Apply Filter
                    </button>
                </div>
            </div>
        </form>

<!-- 🔹 Summary Cards -->
<div class="grid grid-cols-3 gap-4">
    <div class="p-6 bg-white shadow-lg rounded-lg text-center flex flex-col items-center">
        <!-- Heroicon: Users -->
        <x-heroicon-s-users class="h-10 w-10 text-blue-500 mb-2" />
        <h3 class="text-lg font-semibold">Total Patients</h3>
        <p class="text-2xl font-bold">{{ $totalPatients }}</p>
    </div>

    <div class="p-6 bg-white shadow-lg rounded-lg text-center flex flex-col items-center">
        <!-- Heroicon: Check Circle -->
        <x-heroicon-s-check-circle class="h-10 w-10 text-green-500 mb-2" />
        <h3 class="text-lg font-semibold">Attended</h3>
        <p class="text-2xl font-bold text-green-500">{{ $attendedCount }}</p>
    </div>

    <div class="p-6 bg-white shadow-lg rounded-lg text-center flex flex-col items-center">
        <!-- Heroicon: X Circle -->
        <x-heroicon-s-x-circle class="h-10 w-10 text-red-500 mb-2" />
        <h3 class="text-lg font-semibold">Cancelled</h3>
        <p class="text-2xl font-bold text-red-500">{{ $cancelledCount }}</p>
    </div>
</div>

        <!-- 🔹 Charts Section -->
        <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
            <h3 class="text-xl font-semibold text-gray-800 text-center mb-4">Appointments Overview</h3>
            <div class="relative h-[400px] w-full">
                <canvas id="appointmentMeter"></canvas>
            </div>
        </div>

        <!-- 🔹 Diagnosis & Medications Charts (Side by Side) -->
        <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
           <h3 class="text-xl font-semibold text-gray-800 text-center mb-4">
            Top 5 Prescribed Medications & Top Reasons for Visit
            </h3>
            <div class="flex space-x-8">
                <div class="relative h-[400px] w-1/2 p-4 bg-white shadow-lg rounded-lg">
                    <canvas id="medicationChart"></canvas>
                </div>
                <div class="relative h-[400px] w-1/2 p-4 bg-white shadow-lg rounded-lg">
                    <canvas id="diagnosisChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Generate Accomplishment Report Button -->
        <div class="flex justify-end mt-6">
            <button id="generateReport" class="bg-blue-500 text-white py-2 px-4 rounded-lg">
                Generate Accomplishment Report
            </button>
        </div>
            <div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg p-6 w-80">
                <h2 class="text-lg font-semibold mb-4">Select Report Type</h2>
                <button data-type="monthly" class="w-full bg-blue-600 text-white py-2 mb-2 rounded">Monthly</button>
                <button data-type="yearly" class="w-full bg-yellow-500 text-white py-2 rounded">Yearly</button>
                <button id="closeModal" class="mt-4 w-full bg-gray-300 py-2 rounded">Cancel</button>
            </div>
</div>
</div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Doughnut: Attended vs Cancelled
            function renderAttendedCancelledDoughnut() {
                const ctx = document.getElementById('appointmentMeter').getContext('2d');
                const attendedCount  = @json($attendedCount);
                const cancelledCount = @json($cancelledCount);
                const data  = [attendedCount, cancelledCount];
                const total = data.reduce((a, b) => a + b, 0);

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Attended Appointments', 'Cancelled Appointments'],
                        datasets: [{
                            data,
                            backgroundColor: ['#4CAF50', '#FF5733'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: {
                                callbacks: {
                                    label: (context) => {
                                        const value = context.parsed;
                                        const pct = total ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${context.label}: ${value} (${pct}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Medications & Diagnoses
            function renderCharts() {
                const ctxMed  = document.getElementById('medicationChart').getContext('2d');
                const ctxDiag = document.getElementById('diagnosisChart').getContext('2d');
                const medications = @json($medications);
                const diagnoses   = @json($diagnoses);

                // Medications: Horizontal Bar
                new Chart(ctxMed, {
                    type: 'bar',
                    data: {
                        labels: medications.map(m => m.name),
                        datasets: [{
                            label: 'Quantity Dispensed',
                            data: medications.map(m => m.quantity_dispensed),
                            backgroundColor: '#4CAF50'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        scales: {
                            x: { title: { display: true, text: 'Quantity Dispensed' } },
                            y: {
                                title: { display: true, text: 'Medications' },
                                ticks: { autoSkip: false, maxTicksLimit: 10 }
                            }
                        },
                        plugins: { legend: { position: 'top' } }
                    }
                });

                // Diagnoses: Pie Chart
                new Chart(ctxDiag, {
                    type: 'pie',
                    data: {
                        labels: diagnoses.map(d => d.diagnosis),
                        datasets: [{
                            data: diagnoses.map(d => d.count),
                            backgroundColor: ['#FF9F40', '#FF6384', '#36A2EB', '#4CAF50', '#9C27B0']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } }
                    }
                });
            }

            // Run all charts
            renderAttendedCancelledDoughnut();
            renderCharts();
        });
    </script>

    <script>
        const modal = document.getElementById('reportModal');
        const generateBtn = document.getElementById('generateReport');
        const closeModal = document.getElementById('closeModal');

        generateBtn.addEventListener('click', () => {
            modal.classList.remove('hidden'); // Show modal
        });

        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden'); // Hide modal
        });

        // Handle monthly/yearly buttons
        modal.querySelectorAll('button[data-type]').forEach(btn => {
            btn.addEventListener('click', () => {
                const type = btn.getAttribute('data-type');
                const month = "{{ $selectedMonth }}";
                const year = "{{ $selectedYear }}";

                if(type === 'monthly') {
                    window.open(`{{ route('generate.accomplishment.report') }}?type=monthly&month=${month}&year=${year}`, '_blank');
                } else {
                    window.open(`{{ route('generate.accomplishment.report') }}?type=yearly&year=${year}`, '_blank');
                }

                modal.classList.add('hidden'); // Close modal
            });
        });
        </script>


</x-app-layout>
