<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('key', 80)->unique();
            $table->string('section', 40)->default('general');
            $table->longText('value');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->string('client_name', 150);
            $table->enum('client_type', ['bumn', 'government', 'private', 'other'])->default('bumn');
            $table->enum('category', ['construction', 'civil', 'mep', 'interior', 'maintenance'])->default('construction');
            $table->decimal('value', 18, 2)->nullable();
            $table->enum('status', ['ongoing', 'completed'])->default('ongoing');
            $table->string('location', 150)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->longText('description')->nullable();
            $table->string('cover_image', 255)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index('status');
            $table->index('client_type');
        });

        Schema::create('project_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('file_path', 255);
            $table->enum('file_type', ['image', 'video'])->default('image');
            $table->string('caption', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('certificates_legalities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->enum('type', ['nib', 'sbu', 'skk', 'ska', 'iso_9001', 'iso_14001', 'iso_45001', 'k3', 'other']);
            $table->string('number', 100)->nullable();
            $table->string('issuer', 150)->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('file_path', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('expiry_date');
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->string('cover_image', 255)->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('category', ['news', 'article', 'announcement'])->default('news');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 150);
            $table->string('phone', 20)->nullable();
            $table->string('subject', 200);
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->longText('description')->nullable();
            $table->longText('requirements')->nullable();
            $table->string('location', 150)->nullable();
            $table->enum('type', ['fulltime', 'contract'])->default('fulltime');
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->date('deadline')->nullable();
            $table->timestamps();
        });

        Schema::create('career_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_vacancy_id')->constrained('job_vacancies')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('email', 150);
            $table->string('phone', 20)->nullable();
            $table->string('resume_path', 255);
            $table->text('cover_letter')->nullable();
            $table->enum('status', ['new', 'reviewed', 'interview', 'hired', 'rejected'])->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_applications');
        Schema::dropIfExists('job_vacancies');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('certificates_legalities');
        Schema::dropIfExists('project_galleries');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('company_profiles');
    }
};
