<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    public function Salary()
    {
        return $this->hasMany(Salary::class, 'employee_id');
    }
}
