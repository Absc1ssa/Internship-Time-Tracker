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
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('am_clock_in_image')->nullable();
            $table->string('am_clock_out_image')->nullable();
            $table->string('pm_clock_in_image')->nullable();
            $table->string('pm_clock_out_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'am_clock_in_image',
                'am_clock_out_image',
                'pm_clock_in_image',
                'pm_clock_out_image',
            ]);
        });
    }
};
