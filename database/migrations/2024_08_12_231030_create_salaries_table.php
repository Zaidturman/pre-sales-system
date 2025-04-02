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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('employee_id');
            $table->string('month', 7); 
            $table->double('work_hours')->default(0);
            $table->double('advance')->default(0);
            $table->enum('salary_status', ['unPaid', 'Partial', 'Delayed', 'Paid'])->default('unPaid');
            $table->double('salary_value')->default(0);
            $table->double('salarypaid_value')->default(0);
            $table->double('salaryremaning_value')->default(0);

            // Foreign key constraint
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
