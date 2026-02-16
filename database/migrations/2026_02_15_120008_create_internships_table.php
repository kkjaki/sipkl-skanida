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
        Schema::create('internships', function (Blueprint $table) {
            $table->id();
            // student_id references user_id in students table (which is both PK and FK to users)
            $table->foreignId('student_id')->constrained('students', 'user_id')->onDelete('cascade');
            $table->foreignId('industry_id')->constrained('industries')->onDelete('cascade');
            $table->foreignId('supervisor_id')->nullable()->constrained('supervisors', 'user_id');
            $table->foreignId('academic_year_id')->constrained('academic_years');
            $table->date('start_date');
            $table->date('actual_end_date')->nullable();
            $table->enum('status', ['ongoing', 'finished', 'withdrawn'])->default('ongoing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internships');
    }
};
