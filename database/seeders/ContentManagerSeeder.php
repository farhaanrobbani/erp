<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ContentManagerSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'konten@perusahaan.co.id'],
            [
                'name' => 'Content Manager',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'is_active' => true,
            ]
        );

        $user->assignRole('content_manager');

        $directorate = Department::updateOrCreate(
            ['code' => 'DIR'],
            ['name' => 'Direktorat']
        );

        Employee::updateOrCreate(
            ['user_id' => $user->id],
            [
                'employee_no' => 'NIP-0002',
                'department_id' => $directorate->id,
                'position' => 'Content Manager',
                'employment_status' => 'permanent',
                'join_date' => now()->startOfYear(),
            ]
        );
    }
}
