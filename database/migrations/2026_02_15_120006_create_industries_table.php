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
        Schema::create('industries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_submitter_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name');
            $table->text('address');
            $table->string('city');
            $table->string('contact_person')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->enum('delivery_method_proposal', ['independent', 'school'])->default('school');
            $table->boolean('is_synced')->default(false);
            $table->enum('status', ['open', 'full', 'blacklisted'])->default('open');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industries');
    }
};
