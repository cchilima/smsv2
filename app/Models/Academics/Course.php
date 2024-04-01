<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name'];

    public function prerequisites()
    {
        return $this->belongsToMany(Course::class, 'prerequisites', 'course_id', 'prerequisite_course_id');
    }

//    public function programCourses()
//    {
//        return $this->hasMany(ProgramCourses::class, 'course_id','id');
//    }
    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_courses','course_id','program_id');
    }
    public function levels()
    {
        return $this->belongsToMany(CourseLevel::class, 'program_courses', 'course_id', 'course_level_id');
    }
    public function programCourses()
    {
        return $this->hasMany(ProgramCourses::class, 'course_id');
    }
    public function grades()
    {
        return $this->hasMany(Grade::class,'course_id','id');
    }
}
