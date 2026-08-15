<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hse_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->foreignId('project_id')->nullable()->constrained('projects')->cascadeOnDelete();
            $table->string('title', 200);
            $table->enum('report_type', ['daily', 'weekly', 'monthly', 'incident', 'induction']);
            $table->longText('description')->nullable();
            $table->string('file_path', 255)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('report_date');
        });

        Schema::create('incident_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->date('incident_date');
            $table->time('incident_time')->nullable();
            $table->string('location', 200)->nullable();
            $table->enum('incident_type', ['near_miss', 'first_aid', 'lost_time_injury', 'fatality', 'property_damage']);
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('description');
            $table->text('root_cause')->nullable();
            $table->text('corrective_action')->nullable();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['open', 'investigating', 'closed'])->default('open');
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('safety_inductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->date('induction_date');
            $table->string('trainer', 150)->nullable();
            $table->string('topic', 200)->nullable();
            $table->enum('result', ['pass', 'fail']);
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });

        Schema::create('mcus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('mcu_date');
            $table->string('provider', 150)->nullable();
            $table->enum('result', ['fit', 'unfit', 'fit_condition'])->nullable();
            $table->string('file_path', 255)->nullable();
            $table->date('next_mcu_date')->nullable();
            $table->timestamps();
        });

        Schema::create('safety_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->cascadeOnDelete();
            $table->date('check_date');
            $table->string('item_name', 200);
            $table->enum('status', ['ok', 'needs_repair', 'not_available']);
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('note', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('safety_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->date('period');
            $table->decimal('total_work_hours', 10, 2)->default(0);
            $table->integer('man_days')->unsigned()->default(0);
            $table->integer('zero_accident_days')->unsigned()->default(0);
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['project_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safety_hours');
        Schema::dropIfExists('safety_checklists');
        Schema::dropIfExists('mcus');
        Schema::dropIfExists('safety_inductions');
        Schema::dropIfExists('incident_logs');
        Schema::dropIfExists('hse_reports');
    }
};
