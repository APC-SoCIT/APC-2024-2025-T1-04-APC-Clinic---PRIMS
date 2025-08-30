<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\RfidCard;

class RfidCardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Find a patient (example using apc_id_number)
        $patient = Patient::where('apc_id_number', '2022-140145')->first();

        if ($patient) {
            RfidCard::updateOrCreate(
                ['rfid_uid' => '1249080257'], // unique RFID UID
                [
                    'patient_id' => $patient->id, // link to patient, not user
                ]
            );
        }
    }
}
