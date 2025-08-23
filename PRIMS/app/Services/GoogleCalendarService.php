<?php
namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class GoogleCalendarService {
    protected $client;

    public function __construct() {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('app/google-calendar.json')); // OAuth JSON file
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
    }

    public function createEvent($appointment) {
        $service = new Google_Service_Calendar($this->client);

        $event = new Google_Service_Calendar_Event([
            'summary' => 'Clinic Appointment - ' . $appointment->patient_name,
            'description' => 'Appointment with Dr. ' . $appointment->doctor_name,
            'start' => ['dateTime' => $appointment->date . 'T' . $appointment->time, 'timeZone' => 'Asia/Manila'],
            'end' => ['dateTime' => $appointment->date . 'T' . date('H:i:s', strtotime($appointment->time) + 1800), 'timeZone' => 'Asia/Manila'], // +30 mins
            'attendees' => [
                ['email' => $appointment->doctor_email], 
                ['email' => $appointment->patient_email]
            ]
        ]);

        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $event);
        return $event->htmlLink;
    }
}
