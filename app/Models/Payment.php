<?php

namespace App\Models;

use App\Models\PaymentDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;
    protected $guarded= [];

  

    public function Customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function invoice(){
        return $this->belongsTo(Invoice::class,'invoice_id','id');
    }
    public function paymentDetails() {
        return $this->hasMany(PaymentDetail::class, 'invoice_id', 'invoice_id');
    }
}
