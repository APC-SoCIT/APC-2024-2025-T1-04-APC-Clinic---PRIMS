import Chart from 'chart.js/auto';

document.addEventListener("DOMContentLoaded", function () {
    const canvas = document.getElementById("patientChart");
    
    if (canvas) {
        const ctx = canvas.getContext('2d');
        const labels = JSON.parse(canvas.dataset.labels);
        const data = JSON.parse(canvas.dataset.data);

        new Chart(ctx, {
            type: 'bar', // Change to 'line' or 'pie' if needed
            data: {
                labels: labels,
                datasets: [{
                    label: 'Patients Per Month',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Prevents infinite stretching
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
