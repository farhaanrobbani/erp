<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $directorate = Department::updateOrCreate(
            ['code' => 'DIR'],
            ['name' => 'Direktorat']
        );

        $user = User::updateOrCreate(
            ['email' => 'admin@perusahaan.co.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );

        $user->assignRole('super_admin');

        Employee::updateOrCreate(
            ['user_id' => $user->id],
            [
                'employee_no' => 'NIP-0001',
                'department_id' => $directorate->id,
                'position' => 'Direktur Utama',
                'employment_status' => 'permanent',
                'join_date' => now()->startOfYear(),
            ]
        );
    }
}
