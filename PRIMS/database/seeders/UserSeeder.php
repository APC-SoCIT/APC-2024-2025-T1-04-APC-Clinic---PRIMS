<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        //
        $users = [
            [
                'email' => 'smcatingub@student.apc.edu.ph',
                'password' => 'smcatingub',
                'role' => 'patient',
            ],
            [
                'email' => 'eddaduya@student.apc.edu.ph',
                'password' => 'eddaduya',
                'role' => 'patient',
            ],
            [
                'email' => 'cknailgas@student.apc.edu.ph',
                'password' => 'cknailgas',
                'role' => 'patient',
            ],
            [
                'email' => 'barabajante3@student.apc.edu.ph',
                'password' => 'barabajante3',
                'role' => 'patient',
            ],
            [
                'email' => 'anamaet@apc.edu.ph',
                'password' => 'anamaet',
                'role' => 'clinic staff',
            ],
            [
                'email' => 'smcatingub@apc.edu.ph',
                'password' => 'smcatingub',
                'role' => 'clinic staff',
            ],
            [
                'email' => 'jmmiyabe@student.apc.edu.ph',
                'password' => 'jmmiyabe',
                'role' => 'patient',
            ],
            [
                'email' => 'junavendano@apc.edu.ph',
                'password' => 'junavendano',
                'role' => 'clinic staff',
            ],
        ];

        foreach($users as $user) {
            $created_user = User::create([
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
            ]);

            $created_user->assignRole($user['role']);
        }
    }
}
