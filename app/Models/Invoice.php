<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $guarded= [];

   public function payment()
{
    return $this->hasOne(Payment::class, 'invoice_id');
}

public function partialPayments()
{
    return $this->belongsToMany(PartialPayment::class, 'invoice_partial_payment', 'invoice_id', 'partial_payment_id');
}

    public function invoice_details(){
        return $this->hasMany(InvoiceDetail::class, 'invoice_id', 'id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
  
}
