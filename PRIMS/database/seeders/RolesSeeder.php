<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $role_clinicstaff = Role::create(['name' => 'clinic staff']);
        $role_patient = Role::create(['name' => 'patient']);

        $permission_patient_homepage = Permission::create(['name' => 'patient homepage']);
        $permission_schedule_appointments = Permission::create(['name' => 'schedule appointments']);
        $permission_appointment_history = Permission::create(['name' => 'appointment history']);

        $permission_admin_dashboard = Permission::create(['name' => 'admin dashboard']);
        $permission_medical_records = Permission::create(['name' => 'medical records']);
        $permission_inventory = Permission::create(['name' => 'inventory']);
        $permission_calendar = Permission::create(['name' => 'calendar']);
        $permission_summary_reports = Permission::create(['name' => 'summary reports']);

        $permissions_patient = [$permission_patient_homepage, $permission_schedule_appointments, $permission_appointment_history];
        $permissions_staff = [$permission_admin_dashboard, $permission_medical_records, $permission_inventory, $permission_calendar, $permission_summary_reports];

        $role_clinicstaff->syncPermissions($permissions_staff);
        $role_patient->syncPermissions($permissions_patient);
    }
}
