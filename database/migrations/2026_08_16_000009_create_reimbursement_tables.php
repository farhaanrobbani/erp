<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->enum('status', ['pending', 'pm_approved', 'finance_approved', 'director_approved', 'paid', 'rejected'])->default('pending');
            $table->foreignId('approved_by_pm')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by_finance')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by_director')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('paid_at')->nullable();
            $table->string('rejected_reason', 255)->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('reimbursement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reimbursement_id')->constrained('reimbursements')->cascadeOnDelete();
            $table->string('description', 255);
            $table->enum('category', ['transport', 'meal', 'lodging', 'material', 'tool', 'other']);
            $table->decimal('amount', 14, 2);
            $table->string('receipt_path', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reimbursement_items');
        Schema::dropIfExists('reimbursements');
    }
};
