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
        Schema::create('assessment_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained('internships')->onDelete('cascade');
            $table->foreignId('indicator_id')->constrained('evaluation_indicators')->onDelete('cascade');
            $table->integer('score_industry')->nullable();
            $table->integer('score_school')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_scores');
    }
};
