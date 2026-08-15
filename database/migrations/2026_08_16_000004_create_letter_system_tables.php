<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letter_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->string('code_format', 100)->default('{NUMBER}/{CODE}/{ROMAN}/{YEAR}');
            $table->tinyInteger('pad_length')->unsigned()->default(3);
            $table->boolean('is_active')->default(true);
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('letter_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('letter_category_id')->constrained('letter_categories')->restrictOnDelete();
            $table->string('subject', 255);
            $table->string('recipient', 255);
            $table->date('request_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('generated_letter_number', 255)->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->string('rejection_reason', 255)->nullable();
            $table->string('file_path', 255)->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->index('status');
            $table->index('request_date');
            $table->index('letter_category_id');
        });

        Schema::create('letter_number_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_category_id')->constrained('letter_categories')->cascadeOnDelete();
            $table->tinyInteger('period_month')->unsigned();
            $table->smallInteger('period_year')->unsigned();
            $table->integer('last_number')->unsigned()->default(0);
            $table->timestamps();

            $table->unique(['letter_category_id', 'period_month', 'period_year'], 'lns_category_month_year_unique');
        });

        Schema::create('mail_archives', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['incoming', 'outgoing']);
            $table->string('letter_number', 255)->nullable();
            $table->foreignId('letter_category_id')->nullable()->constrained('letter_categories')->nullOnDelete();
            $table->foreignId('letter_request_id')->nullable()->constrained('letter_requests')->nullOnDelete();
            $table->string('subject', 255);
            $table->string('sender', 200);
            $table->string('recipient', 200);
            $table->date('letter_date');
            $table->date('received_date')->nullable();
            $table->longText('disposition')->nullable();
            $table->string('file_path', 255);
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('type');
            $table->index('letter_number');
            $table->index('received_date');
        });

        Schema::create('dispositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mail_archive_id')->constrained('mail_archives')->cascadeOnDelete();
            $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('action', 150)->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['forwarded', 'read', 'done'])->default('forwarded');
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispositions');
        Schema::dropIfExists('mail_archives');
        Schema::dropIfExists('letter_number_sequences');
        Schema::dropIfExists('letter_requests');
        Schema::dropIfExists('letter_categories');
    }
};
