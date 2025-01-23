<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClinicStaff;

class ClinicStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define patient details for each user
        $clinicstaffs = [
            [
                'email' => 'anamaet@apc.edu.ph',
                'clinic_staff_fname' => 'Ana Mae',
                'clinic_staff_minitial' => 'J',
                'clinic_staff_lname' => 'Torre',
                'clinic_staff_role' => 'Nurse',
            ],
        ];

        foreach ($clinicstaffs as $clinicstaff) {
            // Find the corresponding user by email
            $user = User::where('email', $clinicstaff['email'])->first();

            if ($user && $user->hasRole('clinic staff')) {
                ClinicStaff::create([
                    'user_id' => $user->id,
                    'clinic_staff_fname' => $clinicstaff['clinic_staff_fname'],
                    'clinic_staff_minitial' => $clinicstaff['clinic_staff_minitial'],
                    'clinic_staff_lname' => $clinicstaff['clinic_staff_lname'],
                    'email' => $clinicstaff['email'],
                    'clinic_staff_role' => $clinicstaff['clinic_staff_role'],
                ]);
            }
        }
    }
}
