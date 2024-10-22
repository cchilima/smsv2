<?php

namespace App\Models\Sponsorship;

use App\Models\Admissions\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Sponsor extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['name', 'description', 'phone', 'email'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_sponsor', 'sponsor_id', 'student_id')
            ->withTimestamps();
    }

}
