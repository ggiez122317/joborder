<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Seed with existing config dynamically so we don't lose data
        $offices = config('offices', [
            'ACCOUNTING', 'BUDGET', 'ASSESSOR', 'HEALTH', 'ENGINEERING', 'MENRO',
            'MSWDO', 'MCR', 'EXECUTIVE', 'MPDO', 'ADMINISTRATOR', 'AGRICULTURE',
            'SB MEMBER', 'SB SECRETARY', 'TREASURY', 'VICE MAYOR', 'MARKET',
            'TOLL ROAD', 'WATERSYSTEM', 'MEDICAL', 'PESO', 'NATIONAL AGENCY',
            "BARANGAY'S", 'HRMO',
        ]);
        
        $insertData = [];
        $now = now();
        foreach ($offices as $office) {
            $insertData[] = ['name' => $office, 'created_at' => $now, 'updated_at' => $now];
        }
        if (!empty($insertData)) {
            DB::table('offices')->insert($insertData);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
