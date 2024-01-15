<?php

namespace App\Models\Enrollments;

use App\Models\Academics\AcademicPeriodClass;
use App\Models\Academics\ClassAssessment;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'academic_period_class_id'];

    public function class()
    {
        return $this->belongsTo(AcademicPeriodClass::class, 'academic_period_class_id', 'id');

    }

    public function assesment() {
        return $this->hasOne(ClassAssessment::class,'academic_period_class_id','academic_period_class_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
