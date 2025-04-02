<?php

namespace App\Models;

use App\Models\PartialPayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function partialPayments()
    {
        return $this->hasMany(PartialPayment::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($customer) {
            // Delete related payments
            $customer->payments()->each(function ($payment) {
                // Delete related invoices
                $payment->invoice()->delete();
                $payment->delete();
            });
        });
    }
}
