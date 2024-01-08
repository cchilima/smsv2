<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $fillable = ['code','name','department_id','qualification_id','description','slug'];

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

//    public function courses()
//    {
//        return $this->belongsToMany(Course::class, 'program_courses', 'program_id', 'course_id')->orderBy('code', 'asc');
//    }
    public function course()
    {
        return $this->belongsToMany(Course::class, 'program_courses')->withPivot('level_id');
    }
    public function qualification(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Qualification::class, 'qualification_id');
    }
    public function levels()
    {
        return $this->hasMany(CourseLevel::class);
    }

    //levels
    public function programCourses()
    {
        return $this->hasMany(ProgramCourses::class, 'program_id');
    }

    public function courseLevels()
    {
        return $this->hasManyThrough(CourseLevel::class, ProgramCourses::class, 'program_id', 'id', 'id', 'course_level_id');
    }

    public function courses()
    {
        return $this->hasManyThrough(Course::class, ProgramCourses::class, 'program_id', 'id', 'id', 'course_id');
    }

}
