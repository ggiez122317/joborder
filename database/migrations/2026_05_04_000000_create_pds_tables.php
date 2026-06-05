<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->nullable()->index();
            $table->string('full_name')->index();
            $table->string('surname')->nullable()->index();
            $table->string('first_name')->nullable()->index();
            $table->string('middle_name')->nullable();
            $table->string('name_extension')->nullable();
            $table->string('job_order')->nullable()->index();
            $table->string('position_title')->nullable()->index();
            $table->string('office')->nullable()->index();
            $table->string('sex_at_birth')->nullable()->index();
            $table->string('source_file')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('personal_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained('employees')->cascadeOnDelete();
            $table->string('surname')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('name_extension')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('sex_at_birth')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('citizenship_basis')->nullable();
            $table->string('dual_citizenship_country')->nullable();
            $table->string('height_m')->nullable();
            $table->string('weight_kg')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('umid_id_no')->nullable();
            $table->string('pagibig_id_no')->nullable();
            $table->string('philhealth_no')->nullable();
            $table->string('philsys_no')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('agency_employee_no')->nullable();
            $table->string('telephone_no')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email_address')->nullable();
            $table->string('job_order')->nullable();
            $table->string('residential_house_no')->nullable();
            $table->string('residential_street')->nullable();
            $table->string('residential_subdivision')->nullable();
            $table->string('residential_barangay')->nullable();
            $table->string('residential_city')->nullable();
            $table->string('residential_province')->nullable();
            $table->string('residential_zip_code')->nullable();
            $table->string('permanent_house_no')->nullable();
            $table->string('permanent_street')->nullable();
            $table->string('permanent_subdivision')->nullable();
            $table->string('permanent_barangay')->nullable();
            $table->string('permanent_city')->nullable();
            $table->string('permanent_province')->nullable();
            $table->string('permanent_zip_code')->nullable();
            $table->timestamps();
        });

        Schema::create('family_background', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained('employees')->cascadeOnDelete();
            $table->string('spouse_surname')->nullable();
            $table->string('spouse_first_name')->nullable();
            $table->string('spouse_middle_name')->nullable();
            $table->string('spouse_name_extension')->nullable();
            $table->string('spouse_occupation')->nullable();
            $table->string('spouse_employer_business_name')->nullable();
            $table->string('spouse_business_address')->nullable();
            $table->string('spouse_telephone_no')->nullable();
            $table->json('children')->nullable();
            $table->string('father_surname')->nullable();
            $table->string('father_first_name')->nullable();
            $table->string('father_middle_name')->nullable();
            $table->string('father_name_extension')->nullable();
            $table->string('mother_maiden_name')->nullable();
            $table->string('mother_surname')->nullable();
            $table->string('mother_first_name')->nullable();
            $table->string('mother_middle_name')->nullable();
            $table->timestamps();
        });

        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('level');
            $table->string('school_name')->nullable();
            $table->string('degree_course')->nullable();
            $table->string('attendance_from')->nullable();
            $table->string('attendance_to')->nullable();
            $table->string('highest_level_units_earned')->nullable();
            $table->string('year_graduated')->nullable();
            $table->string('honors_received')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('eligibility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('career_service')->nullable();
            $table->string('rating')->nullable();
            $table->string('examination_date')->nullable();
            $table->string('examination_place')->nullable();
            $table->string('license_number')->nullable();
            $table->string('license_valid_until')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('work_experience', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('date_from')->nullable();
            $table->string('date_to')->nullable();
            $table->string('position_title')->nullable();
            $table->string('department_agency_office_company')->nullable();
            $table->string('status_of_appointment')->nullable();
            $table->string('government_service')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('voluntary_work', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('organization_name_address')->nullable();
            $table->string('date_from')->nullable();
            $table->string('date_to')->nullable();
            $table->string('number_of_hours')->nullable();
            $table->string('position_nature_of_work')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('date_from')->nullable();
            $table->string('date_to')->nullable();
            $table->string('number_of_hours')->nullable();
            $table->string('type_of_ld')->nullable();
            $table->string('conducted_sponsored_by')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('other_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained('employees')->cascadeOnDelete();
            $table->json('special_skills_hobbies')->nullable();
            $table->json('non_academic_distinctions')->nullable();
            $table->json('memberships')->nullable();
            $table->json('questions')->nullable();
            $table->json('references')->nullable();
            $table->string('government_id_type')->nullable();
            $table->string('government_id_no')->nullable();
            $table->string('government_id_date_place_issued')->nullable();
            $table->date('date_accomplished')->nullable();
            $table->string('signature_name')->nullable();
            $table->json('visibility')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('other_information');
        Schema::dropIfExists('trainings');
        Schema::dropIfExists('voluntary_work');
        Schema::dropIfExists('work_experience');
        Schema::dropIfExists('eligibility');
        Schema::dropIfExists('education');
        Schema::dropIfExists('family_background');
        Schema::dropIfExists('personal_information');
        Schema::dropIfExists('employees');
    }
};
