<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;
    protected $table = 'salaries';

    // الحقول القابلة للتعبئة (Mass Assignable)
    protected $fillable = [
        'employee_id',
        'month',
        'work_hours',
        'advance',
        'salary_status',
        'salary_value',
        'salarypaid_value',
        'salaryremaning_value'
    ];

    // تعريف العلاقة مع موديل Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
