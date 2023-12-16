<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prerequisite extends Model
{
    use HasFactory;
    protected $fillable = ['course_id','prerequisite_course_id'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'prerequisites', 'prerequisite_course_id', 'course_id');
    }
}
