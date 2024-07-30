<?php

namespace App\Models\Academics;

use App\Models\Admissions\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Grade extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['course_code', 'course_title', 'total', 'publication_status', 'student_id', 'academic_period_id', 'assessment_type_id','course_id'];

    public function assessment_type()
    {
        return $this->belongsTo(AssessmentType::class, 'assessment_type_id','id');
    }
    public function users()
    {
        return $this->hasMany(Student::class, 'student_id', 'id');
    }
    public function academicPeriods()
    {
        return $this->belongsTo(AcademicPeriod::class, 'academic_period_id','id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function programCourses()
    {
        return $this->hasMany(ProgramCourses::class, 'course_id', 'course_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function programCourse()
    {
        return $this->belongsTo(ProgramCourses::class, 'course_id', 'course_id');
    }
    public function class_assessment()
    {
        return $this->belongsTo(ClassAssessment::class, 'assessment_type_id', 'assessment_type_id');
    }
    public function academic_periods()
    {
        return $this->belongsTo(AcademicPeriod::class, 'academic_period_id', 'id');
    }



}
