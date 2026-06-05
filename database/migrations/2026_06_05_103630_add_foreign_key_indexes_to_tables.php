<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->index('created_by');
        });

        Schema::table('education', function (Blueprint $table) {
            $table->index('employee_id');
        });

        Schema::table('eligibility', function (Blueprint $table) {
            $table->index('employee_id');
        });

        Schema::table('work_experience', function (Blueprint $table) {
            $table->index('employee_id');
        });

        Schema::table('voluntary_work', function (Blueprint $table) {
            $table->index('employee_id');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->index('employee_id');
        });

        Schema::table('import_histories', function (Blueprint $table) {
            $table->index('employee_id');
            $table->index('created_by');
        });

        Schema::table('employee_change_logs', function (Blueprint $table) {
            $table->index('employee_id');
            $table->index('user_id');
        });

        Schema::table('system_audit_logs', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('user_trusted_devices', function (Blueprint $table) {
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['created_by']);
        });

        Schema::table('education', function (Blueprint $table) {
            $table->dropIndex(['employee_id']);
        });

        Schema::table('eligibility', function (Blueprint $table) {
            $table->dropIndex(['employee_id']);
        });

        Schema::table('work_experience', function (Blueprint $table) {
            $table->dropIndex(['employee_id']);
        });

        Schema::table('voluntary_work', function (Blueprint $table) {
            $table->dropIndex(['employee_id']);
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->dropIndex(['employee_id']);
        });

        Schema::table('import_histories', function (Blueprint $table) {
            $table->dropIndex(['employee_id']);
            $table->dropIndex(['created_by']);
        });

        Schema::table('employee_change_logs', function (Blueprint $table) {
            $table->dropIndex(['employee_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('system_audit_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('user_trusted_devices', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });
    }
};
