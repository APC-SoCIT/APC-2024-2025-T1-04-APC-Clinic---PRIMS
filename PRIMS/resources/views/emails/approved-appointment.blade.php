<!DOCTYPE html>
<html>
<head>
    <title>Appointment Status</title>
</head>
<body>
    <p>Dear {{ $appointment->patient->first_name }} {{ $appointment->patient->middle_initial }}. {{ $appointment->patient->last_name }},</p>
    <p>Your appointment on {{ $appointment->appointment_date }} has been approved.</p>
    
    <p>Kindly come in early or on time on your appointment.</p>

    <p>Thank you,
    <br>PRIMS - APC Clinic</p>
</body>
</html>