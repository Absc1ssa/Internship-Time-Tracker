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
        Schema::table('users', function (Blueprint $table) {
            $table->string('fname')->after('id');  // Adds fname column after id
            $table->string('lname')->after('fname'); // Adds lname column after fname
            $table->dropColumn('name'); // Removes the name column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');  // Re-adds name column for rollback
            $table->dropColumn(['fname', 'lname']);  // Removes fname and lname columns
        });
    }
};
