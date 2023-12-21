<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['course_code', 'course_title', 'total', 'publication_status', 'student_id', 'academic_period_id', 'assessment_type_id'];
}
