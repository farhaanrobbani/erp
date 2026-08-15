<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_managers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('file_path', 255);
            $table->bigInteger('file_size')->unsigned()->default(0);
            $table->string('mime_type', 100)->nullable();
            $table->foreignId('folder_id')->nullable()->constrained('file_managers')->cascadeOnDelete();
            $table->enum('category', ['project', 'company', 'hse', 'hr', 'finance', 'other'])->default('other');
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->json('access_roles')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('description', 255)->nullable();
            $table->timestamps();

            $table->index('category');
            $table->index('folder_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_managers');
    }
};
