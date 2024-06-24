<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Prerequisite extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    
    protected $fillable = ['course_id','prerequisite_course_id'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'prerequisites', 'prerequisite_course_id', 'course_id');
    }
}
