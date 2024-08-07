<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class CourseLevel extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['name'];

    public function programCourses()
    {
        return $this->hasMany(ProgramCourses::class, 'course_level_id');
    }
    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_courses', 'level_id', 'program_id')
            ->withPivot('course_id')
            ->with('courses');
    }
    public function courses()
    {
        return $this->hasManyThrough(Course::class, ProgramCourses::class, 'course_level_id', 'id', 'id', 'course_id');
    }

    public function program()
    {
        return $this->belongsToMany(Program::class, 'program_courses', 'course_level_id', 'program_id');
    }
}
