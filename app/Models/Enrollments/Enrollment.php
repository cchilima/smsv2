<?php

namespace App\Models\Enrollments;

use App\Models\Academics\AcademicPeriodClass;
use App\Models\Academics\ClassAssessment;
use App\Models\Admissions\Student;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Enrollment extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['academic_period_class_id', 'student_id'];

    public function class()
    {
        return $this->belongsTo(AcademicPeriodClass::class, 'academic_period_class_id', 'id');
    }

    public function assesment()
    {
        return $this->hasOne(ClassAssessment::class, 'academic_period_class_id', 'academic_period_class_id');
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Student::class, 'id', 'id', 'student_id', 'user_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
