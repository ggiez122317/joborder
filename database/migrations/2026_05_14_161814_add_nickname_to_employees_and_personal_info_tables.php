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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('nickname')->nullable()->after('name_extension');
        });

        Schema::table('personal_information', function (Blueprint $table) {
            $table->string('nickname')->nullable()->after('name_extension');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('nickname');
        });

        Schema::table('personal_information', function (Blueprint $table) {
            $table->dropColumn('nickname');
        });
    }
};
