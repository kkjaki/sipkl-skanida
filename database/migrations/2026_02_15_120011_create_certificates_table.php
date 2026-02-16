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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained('internships')->onDelete('cascade');
            $table->string('certificate_number')->unique()->nullable();
            $table->date('issued_date')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['draft', 'validated', 'generated'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
