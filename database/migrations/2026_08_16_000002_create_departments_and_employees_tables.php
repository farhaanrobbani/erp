<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 20)->unique();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('employee_no', 30)->unique()->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('position', 100)->nullable();
            $table->enum('employment_status', ['permanent', 'contract', 'internship'])->default('contract');
            $table->date('join_date')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced'])->nullable();
            $table->string('ktp_no', 20)->unique()->nullable();
            $table->string('npwp_no', 20)->unique()->nullable();
            $table->string('bpjs_kes', 20)->nullable();
            $table->string('bpjs_tk', 20)->nullable();
            $table->string('bank_name', 50)->nullable();
            $table->string('bank_account_no', 30)->nullable();
            $table->string('emergency_contact', 20)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('department_id');
        });

        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('doc_type', ['ktp', 'npwp', 'ijazah', 'sk', 'bpjs', 'certificate', 'others']);
            $table->string('file_path', 255);
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('notes', 255)->nullable();
            $table->timestamps();

            $table->index('employee_id');
            $table->index('expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('departments');
    }
};
