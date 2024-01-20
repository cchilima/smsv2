<?php

namespace App\Models\Academics;

use App\Models\Admissions\Student;
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
        return $this->belongsTo(CourseLevel::class, 'course_level_id','id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function courseLevel()
    {
        return $this->belongsTo(CourseLevel::class, 'course_level_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'program_id', 'program_id');
    }

    public function grade()
    {
        return $this->hasOne(Grade::class, 'course_id');
    }
}
