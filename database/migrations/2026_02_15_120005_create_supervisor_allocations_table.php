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
        Schema::create('supervisor_allocations', function (Blueprint $table) {
            $table->id();
            // Referencing supervisors table which uses user_id as PK
            $table->foreignId('supervisor_id')->constrained('supervisors', 'user_id')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years');
            $table->integer('quota')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisor_allocations');
    }
};
