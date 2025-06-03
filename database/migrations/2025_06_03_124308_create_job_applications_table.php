<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_posting_id')->constrained()->onDelete('cascade');
            $table->text('cover_letter')->nullable(); // optional cover letter
            $table->string('resume_path'); // path to the resume file
            $table->enum('status', ['applied', 'interviewing', 'offered', 'rejected'])->default('applied'); // application status
            // timestamps for when the application was made
            $table->timestamp('applied_at');
            $table->timestamps();

            $table->unique(['user_id', 'job_posting_id']); // prevent duplicate applications at database level
            $table->index(['user_id', 'job_posting_id']); // index for lookup performance
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
