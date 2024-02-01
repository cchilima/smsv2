<?php

namespace App\Models\Enrollments;

use App\Models\Admissions\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'academic_period_class_id'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id');
    }
}
