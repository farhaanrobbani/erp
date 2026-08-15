<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_permits', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->enum('category', ['sbu', 'skk', 'ska', 'k3_umum', 'other']);
            $table->enum('holder_type', ['company', 'person'])->default('company');
            $table->foreignId('holder_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('number', 100);
            $table->string('issuer', 150)->nullable();
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->string('file_path', 255)->nullable();
            $table->date('notif_sent_at')->nullable();
            $table->string('notes', 255)->nullable();
            $table->timestamps();

            $table->index('expiry_date');
        });

        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_name', 150);
            $table->enum('certificate_type', ['sertifikasi', 'kalibrasi']);
            $table->string('number', 100);
            $table->string('issuer', 150);
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->string('file_path', 255)->nullable();
            $table->date('notif_sent_at')->nullable();
            $table->string('notes', 255)->nullable();
            $table->timestamps();

            $table->index('expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifications');
        Schema::dropIfExists('working_permits');
    }
};
