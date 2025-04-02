<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_attendances', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->time('arrival_time')->nullable();
            $table->time('departure_time')->nullable();
            $table->string('status'); // You might want to limit this to specific statuses like 'present', 'absent', etc.
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_attendances');
    }
};
