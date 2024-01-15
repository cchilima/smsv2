<?php

namespace App\Models\Academics;

use App\Models\Admissions\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['course_code', 'course_title', 'total', 'publication_status', 'student_id', 'academic_period_id', 'assessment_type_id','course_id'];

    public function classAssessment()
    {
        return $this->hasMany(ClassAssessment::class, 'class_assessment_id','id');
    }
    public function users()
    {
        return $this->hasMany(Student::class, 'student_id', 'id');
    }
    public function academicPeriods()
    {
        return $this->hasMany(AcademicPeriod::class, 'academic_period_id','id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function programCourses()
    {
        return $this->belongsTo(ProgramCourses::class, 'course_id', 'course_id');
    }
}
