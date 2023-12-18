<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriodClass extends Model
{
    use HasFactory;

    protected $fillable = ['academic_period_id', 'course_id', 'instructor_id'];

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
        // Assuming the user ID for the instructor is stored in the 'user_id' column
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
