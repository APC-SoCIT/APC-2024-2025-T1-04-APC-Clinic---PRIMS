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
                'clinic_staff_image' => null,
                'clinic_staff_desc' => null,
            ],
            [
                'email' => 'junavendano@apc.edu.ph',
                'clinic_staff_fname' => 'Jun',
                'clinic_staff_minitial' => null,
                'clinic_staff_lname' => 'Avendano',
                'clinic_staff_role' => 'Doctor',
                'clinic_staff_image' => 'img/clinic-staff/boy-doctor.png',
                'clinic_staff_desc' => 'Specializes in General Medicine',
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
                    'clinic_staff_image' => $clinicstaff['clinic_staff_image'],
                    'clinic_staff_desc' => $clinicstaff['clinic_staff_desc'],
                ]);
            }
        }
    }
}
