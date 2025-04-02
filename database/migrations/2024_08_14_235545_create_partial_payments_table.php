<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartialPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('partial_payments', function (Blueprint $table) {
            $table->id();  // إنشاء عمود id تلقائيًا
            $table->unsignedBigInteger('customer_id');  // ربط بالعميل
            $table->decimal('amount', 10, 2);  // العمود الخاص بالمبلغ
            $table->date('payment_date');  // تاريخ الدفع
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');  // إعداد العلاقة الخارجية مع جدول العملاء
            $table->timestamps();  // العمودين created_at و updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('partial_payments');  // حذف الجدول عند التراجع عن الميجر
    }
}
