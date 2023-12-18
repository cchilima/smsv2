<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriodClass extends Model
{
    use HasFactory;

    protected $fillable = ['academic_period_id', 'course_id', 'instructor_id'];
}
