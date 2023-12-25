<?php

namespace App\Models\Academics;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriodClass extends Model
{
    use HasFactory;

    protected $fillable = ['academic_period_id', 'course_id', 'instructor_id','key'];

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

}
