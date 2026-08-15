<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('client', 150);
            $table->string('source', 255)->nullable();
            $table->string('package_number', 100)->nullable();
            $table->decimal('budget', 18, 2)->nullable();
            $table->enum('status', ['announcement', 'kualifikasi', 'anwijzing', 'penawaran', 'menang', 'kalah', 'canceled'])->default('announcement');
            $table->date('bid_date')->nullable();
            $table->date('result_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('tender_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('tenders')->cascadeOnDelete();
            $table->string('name', 200);
            $table->enum('type', ['admin', 'legal', 'technical', 'financial', 'other']);
            $table->string('file_path', 255);
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_documents');
        Schema::dropIfExists('tenders');
    }
};
