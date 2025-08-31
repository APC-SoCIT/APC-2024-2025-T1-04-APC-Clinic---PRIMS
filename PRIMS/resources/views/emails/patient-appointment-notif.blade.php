<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Notification</title>
</head>
<body style="margin:0; padding:0; background-color:#f7f9fc; font-family: Arial, Helvetica, sans-serif;">

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td align="center" style="padding: 30px 15px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="background-color:#ffffff; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); overflow:hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color:#F4BF4F; padding:20px 30px; text-align:center;">
                            <h1 style="margin:0; font-size:24px; font-weight:bold;">
                                PRIMS - APC Clinic
                            </h1>
                            <p style="margin:5px 0 0; color:#1f3a93; font-size:14px;">
                                Appointment Notification
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px; color:#333;">
                            <p style="font-size:16px; margin-bottom:15px;">
                                Dear <strong>{{ $appointment->patient->first_name }}</strong>,
                            </p>

                            <p style="font-size:16px; margin-bottom:10px;">
                                You have booked an appointment on:
                            </p>

                            <div style="background-color:#f1f7ff; padding:15px; border-radius:8px; margin:15px 0; font-size:15px;">
                                <p style="margin:5px 0;"><strong>Date & Time:</strong> {{ $selectedDate }}, {{ $selectedTime }}</p>
                                <p style="margin:5px 0;"><strong>Reason for visit:</strong> {{ $appointment->reason_for_visit }}</p>
                            </div>

                            <p style="font-size:15px; margin-top:20px; margin-bottom:30px; color:#555;">
                                Please wait for the nurse to approve or decline your appointment.
                            </p>

                            <p style="font-size:15px; margin-top:10px; margin-bottom:20px; color:#555;">
                                Thank you, <br>
                                PRIMS - APC Clinic
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#f1f1f1; padding:15px; text-align:center; font-size:13px; color:#333;">
                            This is an automated email from <strong>PRIMS - APC Clinic</strong>. 
                            Please do not reply to this message.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>