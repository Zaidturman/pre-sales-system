<?php

namespace App\Models;

use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'date',
        'amount',
        'detials',
        'category_id',
        'created_by',
    ];
    public function category(){
        return $this->belongsTo(ExpenseCategory::class,'category_id');
    }
}
