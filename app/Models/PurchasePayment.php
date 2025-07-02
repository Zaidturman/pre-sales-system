<?php

namespace App\Models;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchasePayment extends Model
{
    use HasFactory;
    protected $fillable=[
        'purchase_id',
        'supplier_id',
        'paid_status',
        'paid_amount',
        'due_amount',
        'total_amount',
        'discount_amount',
        'payment_method',
    ];

    public function purchase(){
        return $this->belongsTo(Purchase::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

}
