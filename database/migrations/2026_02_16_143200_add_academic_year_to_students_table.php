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
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('academic_year_id')
                  ->nullable()
                  ->after('department_id')
                  ->constrained('academic_years');

            // Performance indexes for dashboard statistics queries
            $table->index('academic_year_id');
            $table->index('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropIndex(['academic_year_id']);
            $table->dropIndex(['department_id']);
            $table->dropColumn('academic_year_id');
        });
    }
};
