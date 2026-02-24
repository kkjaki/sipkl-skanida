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
        Schema::create('daily_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained('internships')->onDelete('cascade');
            $table->date('date');
            $table->text('activity')->nullable();
            $table->enum('status_attendance', ['present', 'excused', 'sick']);
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->string('attachment_path')->nullable();
            $table->text('rejection_note')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_journals');
    }
};
