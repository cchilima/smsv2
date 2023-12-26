<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramCourses extends Model
{
    use HasFactory;

    protected $fillable = ['course_level_id','course_id','program_id'];
    public $timestamps = true;

    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function programs()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function levels()
    {
        return $this->belongsTo(CourseLevel::class, 'course_level_id');
    }
}
