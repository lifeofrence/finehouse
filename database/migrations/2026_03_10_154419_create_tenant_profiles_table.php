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
        Schema::create('tenant_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['student', 'ordinary'])->default('student');
            
            // Shared Fields
            $table->string('phone_number')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('religion')->nullable();
            $table->string('state_of_origin')->nullable();
            $table->string('lga')->nullable();
            $table->string('passport')->nullable();
            $table->string('next_of_kin')->nullable();
            $table->string('next_of_kin_phone')->nullable();

            // Student Only Fields
            $table->string('matric_number')->nullable();
            $table->string('level')->nullable();
            $table->string('department')->nullable();
            $table->string('faculty')->nullable();
            $table->string('course')->nullable();
            $table->string('university')->nullable();

            // Ordinary Only Fields
            $table->text('address')->nullable();

            // Rent Fields
            $table->date('rent_commencement_date')->nullable();
            $table->date('rent_expiry_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_profiles');
    }
};
