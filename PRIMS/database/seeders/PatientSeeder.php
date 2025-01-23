<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define patient details for each user
        $patients = [
            [
                'email' => 'smcatingub@student.apc.edu.ph',
                'first_name' => 'Shannelien Mae',
                'middle_initial' => 'M',
                'last_name' => 'Catingub',
                'gender' => 'Female',
                'date_of_birth' => '2003-12-29',
                'contact_number' => '09123456789',
            ],
            [
                'email' => 'eddaduya@student.apc.edu.ph',
                'first_name' => 'Erika Alessandra',
                'middle_initial' => 'D',
                'last_name' => 'Daduya',
                'gender' => 'Female',
                'date_of_birth' => '2003-01-15',
                'contact_number' => '09127894561',
            ],
            [
                'email' => 'cknailgas@student.apc.edu.ph',
                'first_name' => 'Clart Kent',
                'middle_initial' => 'K',
                'last_name' => 'Nailgas',
                'gender' => 'Female',
                'date_of_birth' => '2002-10-05',
                'contact_number' => '09129087654',
            ],
            [
                'email' => 'barabajante3@student.apc.edu.ph',
                'first_name' => 'Byron Louis',
                'middle_initial' => 'A',
                'last_name' => 'Rabajante',
                'gender' => 'Male',
                'date_of_birth' => '2001-05-12',
                'contact_number' => '09134567890',
            ],
        ];

        foreach ($patients as $patient) {
            // Find the corresponding user by email
            $user = User::where('email', $patient['email'])->first();

            if ($user && $user->hasRole('patient')) {
                Patient::create([
                    'user_id' => $user->id,
                    'first_name' => $patient['first_name'],
                    'middle_initial' => $patient['middle_initial'],
                    'last_name' => $patient['last_name'],
                    'email' => $patient['email'],
                    'gender' => $patient['gender'],
                    'date_of_birth' => $patient['date_of_birth'],
                    'contact_number' => $patient['contact_number'],
                ]);
            }
        }
    }
}
