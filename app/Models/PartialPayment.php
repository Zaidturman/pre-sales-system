<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartialPayment extends Model
{
    use HasFactory;

    // تحديد الأعمدة التي يمكن ملؤها
    protected $fillable = [
        'customer_id',
        'amount',

        'payment_date',
    ];

    // إنشاء علاقة الربط مع نموذج Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
