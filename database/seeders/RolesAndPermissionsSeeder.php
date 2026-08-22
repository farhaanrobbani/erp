<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    private const MODULES = [
        'letter-category',
        'letter-request',
        'mail-archive',
        'disposition',
        'department',
        'employee',
        'employee-document',
        'company-profile',
        'project',
        'project-gallery',
        'certificate-legality',
        'post',
        'contact-message',
        'job-vacancy',
        'career-application',
        'working-permit',
        'certification',
        'tender',
        'tender-document',
        'hse-report',
        'incident-log',
        'safety-induction',
        'mcu',
        'safety-checklist',
        'safety-hour',
        'work-location',
        'attendance',
        'leave',
        'reimbursement',
        'reimbursement-item',
        'payroll',
        'payroll-detail',
        'file-manager',
        'user',
    ];

    private const ACTIONS = [
        'view_any', 'view', 'create', 'update', 'delete',
        'restore', 'force_delete',
    ];

    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (self::MODULES as $module) {
            foreach (self::ACTIONS as $action) {
                Permission::firstOrCreate(['name' => "{$action}_{$module}", 'guard_name' => 'web']);
            }
        }

        Permission::firstOrCreate(['name' => 'approve_letter-request', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'reject_letter-request', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'export_report', 'guard_name' => 'web']);

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $hrd = Role::firstOrCreate(['name' => 'hrd', 'guard_name' => 'web']);
        $hse = Role::firstOrCreate(['name' => 'hse_officer', 'guard_name' => 'web']);
        $finance = Role::firstOrCreate(['name' => 'finance', 'guard_name' => 'web']);
        $pm = Role::firstOrCreate(['name' => 'project_manager', 'guard_name' => 'web']);
        $employee = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);

        $superAdmin->syncPermissions(Permission::all());

        $contentManager = Role::firstOrCreate(['name' => 'content_manager', 'guard_name' => 'web']);

        $contentManager->syncPermissions([
            'view_any_project', 'view_project', 'create_project', 'update_project', 'delete_project',
            'view_any_company-profile', 'view_company-profile', 'create_company-profile', 'update_company-profile',
            'view_any_certificate-legality', 'view_certificate-legality', 'create_certificate-legality', 'update_certificate-legality', 'delete_certificate-legality',
            'view_any_post', 'view_post', 'create_post', 'update_post', 'delete_post',
            'view_any_job-vacancy', 'view_job-vacancy', 'create_job-vacancy', 'update_job-vacancy', 'delete_job-vacancy',
            'view_any_career-application', 'view_career-application', 'update_career-application',
            'view_any_contact-message', 'view_contact-message', 'update_contact-message', 'delete_contact-message',
        ]);

        $hrd->syncPermissions([
            'view_any_department', 'view_department', 'create_department', 'update_department',
            'view_any_employee', 'view_employee', 'create_employee', 'update_employee',
            'view_any_employee-document', 'view_employee-document', 'create_employee-document', 'update_employee-document',
            'view_any_work-location', 'view_work-location', 'create_work-location', 'update_work-location',
            'view_any_attendance', 'view_attendance', 'create_attendance', 'update_attendance',
            'view_any_leave', 'view_leave', 'create_leave', 'update_leave', 'approve_letter-request', 'reject_letter-request',
            'view_any_letter-request', 'view_letter-request', 'create_letter-request', 'update_letter-request',
            'view_any_letter-category', 'view_letter-category',
            'view_any_mail-archive', 'view_mail-archive', 'create_mail-archive', 'update_mail-archive',
            'view_any_file-manager', 'view_file-manager', 'create_file-manager', 'update_file-manager',
            'export_report',
        ]);

        $hse->syncPermissions([
            'view_any_project', 'view_project',
            'view_any_hse-report', 'view_hse-report', 'create_hse-report', 'update_hse-report',
            'view_any_incident-log', 'view_incident-log', 'create_incident-log', 'update_incident-log',
            'view_any_safety-induction', 'view_safety-induction', 'create_safety-induction', 'update_safety-induction',
            'view_any_mcu', 'view_mcu', 'create_mcu', 'update_mcu',
            'view_any_safety-checklist', 'view_safety-checklist', 'create_safety-checklist', 'update_safety-checklist',
            'view_any_safety-hour', 'view_safety-hour', 'create_safety-hour', 'update_safety-hour',
            'view_any_working-permit', 'view_working-permit', 'create_working-permit', 'update_working-permit',
            'view_any_certification', 'view_certification', 'create_certification', 'update_certification',
            'view_any_file-manager', 'view_file-manager', 'create_file-manager',
            'export_report',
        ]);

        $finance->syncPermissions([
            'view_any_payroll', 'view_payroll', 'create_payroll', 'update_payroll',
            'view_any_payroll-detail', 'view_payroll-detail',
            'view_any_reimbursement', 'view_reimbursement', 'create_reimbursement', 'update_reimbursement',
            'view_any_reimbursement-item', 'view_reimbursement-item',
            'view_any_letter-request', 'view_letter-request', 'create_letter-request', 'update_letter-request',
            'view_any_mail-archive', 'view_mail-archive', 'create_mail-archive', 'update_mail-archive',
            'view_any_employee', 'view_employee',
            'view_any_file-manager', 'view_file-manager', 'create_file-manager',
            'export_report',
        ]);

        $pm->syncPermissions([
            'view_any_project', 'view_project', 'create_project', 'update_project',
            'view_any_tender', 'view_tender', 'create_tender', 'update_tender',
            'view_any_tender-document', 'view_tender-document', 'create_tender-document', 'update_tender-document',
            'view_any_reimbursement', 'view_reimbursement', 'create_reimbursement', 'update_reimbursement',
            'view_any_reimbursement-item', 'view_reimbursement-item',
            'view_any_letter-request', 'view_letter-request', 'create_letter-request', 'update_letter-request',
            'view_any_mail-archive', 'view_mail-archive', 'create_mail-archive',
            'view_any_incident-log', 'view_incident-log', 'create_incident-log',
            'view_any_safety-hour', 'view_safety-hour',
            'view_any_employee', 'view_employee',
            'view_any_file-manager', 'view_file-manager', 'create_file-manager',
            'export_report',
        ]);

        $employee->syncPermissions([
            'view_any_letter-request', 'view_letter-request', 'create_letter-request', 'update_letter-request',
            'view_any_mail-archive', 'view_mail-archive',
            'view_any_attendance', 'view_attendance', 'create_attendance', 'update_attendance',
            'view_any_leave', 'view_leave', 'create_leave', 'update_leave',
            'view_any_reimbursement', 'view_reimbursement', 'create_reimbursement',
            'view_any_reimbursement-item', 'view_reimbursement-item',
            'view_any_employee', 'view_employee',
        ]);
    }
}
