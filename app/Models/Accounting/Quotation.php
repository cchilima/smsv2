<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Academics\AcademicPeriod;
use App\Models\Academics\Program;
use App\Models\Admissions\Student;
use App\Models\Users\User;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'raised_by', 'cancelled', 'academic_period_id'];

    public function details()
    {
        return $this->hasMany(QuotationDetail::class, 'quotation_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function program()
    {
        return $this->hasOneThrough(Program::class, Student::class, 'id', 'id', 'student_id', 'program_id');
    }

    public function period()
    {
        return $this->belongsTo(AcademicPeriod::class, 'academic_period_id');
    }

    public function raisedBy()
    {
        return $this->belongsTo(User::class, 'raised_by', 'id');
    }

}
