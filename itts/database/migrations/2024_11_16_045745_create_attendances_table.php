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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Reference to the user
            $table->date('date'); // Attendance date
            $table->timestamp('am_clock_in')->nullable();
            $table->timestamp('am_clock_out')->nullable();
            $table->timestamp('pm_clock_in')->nullable();
            $table->timestamp('pm_clock_out')->nullable();
            $table->string('photo')->nullable(); // Store the image path
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
