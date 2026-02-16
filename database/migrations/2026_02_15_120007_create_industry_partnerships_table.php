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
        Schema::create('industry_partnerships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('industry_id')->constrained('industries')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('mou_file_path')->nullable();
            $table->text('agreement_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industry_partnerships');
    }
};
