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
                'nationality' => 'Filipino',
                'contact_number' => '09761164892',
                'apc_id_number' => '2022-140224',
                'street_number' => '60 Villa Alegre',
                'barangay' => 'Pinagbuhatan',
                'city' => 'Pasig',
                'province' => 'NCR',
                'zip_code' => '1602',
                'country' => 'Philippines',
            ],
            [
                'email' => 'eddaduya@student.apc.edu.ph',
                'first_name' => 'Erika Alessandra',
                'middle_initial' => 'D',
                'last_name' => 'Daduya',
                'gender' => 'Female',
                'date_of_birth' => '2003-10-27',
                'nationality' => 'Filipino',
                'contact_number' => '09326091141',
                'apc_id_number' => '2022-140335',
                'street_number' => '905-D, Raya Garden Condominiums',
                'barangay' => 'Merville',
                'city' => 'Paranaque',
                'province' => 'NCR',
                'zip_code' => '1709',
                'country' => 'Philippines',
            ],
            [
                'email' => 'cknailgas@student.apc.edu.ph',
                'first_name' => 'Clart Kent',
                'middle_initial' => 'K',
                'last_name' => 'Nailgas',
                'gender' => 'Female',
                'date_of_birth' => '2002-10-05',
                'nationality' => 'Filipino',
                'contact_number' => '09129087654',
                'apc_id_number' => '2022-140276',
                'address' => 'Taguig City',
            ],
            [
                'email' => 'barabajante3@student.apc.edu.ph',
                'first_name' => 'Byron Louis',
                'middle_initial' => 'A',
                'last_name' => 'Rabajante',
                'gender' => 'Male',
                'date_of_birth' => '2003-08-22',
                'nationality' => 'Filipino',
                'contact_number' => '09456298987',
                'apc_id_number' => '2022-140289',
                'street_number' => '239 B 8th St.',
                'barangay' => 'Katuparan',
                'city' => 'Taguig',
                'province' => 'NCR',
                'zip_code' => '1630',
                'country' => 'Philippines',
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
                    'apc_id_number' => $patient['apc_id_number'],
                    'street_number' => $patient['street_number'] ?? null, // Avoids the error
                    'barangay' => $patient['barangay'] ?? null,
                    'city' => $patient['city'] ?? null,
                    'province' => $patient['province'] ?? null,
                    'zip_code' => $patient['zip_code'] ?? null,
                    'country' => $patient['country'] ?? null,
                    'nationality' => $patient['nationality'],
                ]);
            }
        }
    }
}
