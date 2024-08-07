<?php

namespace App\Models\Academics;

use App\Models\Admissions\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


class Program extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['code','name','department_id','qualification_id','description','slug'];

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'program_courses', 'program_id', 'course_id')->orderBy('code', 'asc');
    }
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
    public function students()
    {
        return $this->hasMany(Student::class, 'program_id');
    }
    public function programCourses()
    {
        return $this->hasMany(ProgramCourses::class, 'program_id');
    }

    public function courseLevels()
    {
        return $this->hasManyThrough(CourseLevel::class, ProgramCourses::class, 'program_id', 'id', 'id', 'course_level_id');
    }

    public function academicPeriodFees()
    {
        return $this->belongsToMany(AcademicPeriodFee::class, 'program_academic_period_fee', 'program_id', 'academic_period_fee_id')->withTimestamps();
    }

//    public function courses()
//    {
//        return $this->hasManyThrough(Course::class, ProgramCourses::class, 'program_id', 'id', 'id', 'course_id');
//    }
}
