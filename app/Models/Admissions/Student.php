<?php

namespace App\Models\Admissions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['admission_year', 'graduated', 'program_id', 'academic_period_intake_id', 'study_mode_id', 'course_level_id', 'period_type_id', 'user_id' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
