<?php

namespace App\Models\Academics;

use App\Models\Enrollments\Enrollment;
use App\Models\Users\User;
use App\Models\Enrollments\Enrollment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriodClass extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'instructor_id', 'academic_period_id', 'key'];

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    public function class_assessments()
    {
        return $this->hasMany(ClassAssessment::class, 'academic_period_class_id');
    }
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'academic_period_class_id','id');
    }


    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

}
