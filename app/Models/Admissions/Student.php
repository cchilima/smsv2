<?php

namespace App\Models\Admissions;

use App\Models\Users\User;
use App\Models\Academics\CourseLevel;
use App\Models\Academics\PeriodType;
use App\Models\Academics\Program;
use App\Models\Academics\StudyMode;
use App\Models\Academics\AcademicPeriodInformation;
use App\Models\Accounting\{Invoice, Statement, Receipt};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'graduated', 'program_id', 'academic_period_intake_id', 'study_mode_id', 'course_level_id', 'period_type_id', 'user_id', 'admission_year' ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
    public function intake()
    {
        return $this->belongsTo(AcademicPeriodIntake::class, 'academic_period_intake_id');
    }
    public function study_mode()
    {
        return $this->belongsTo(StudyMode::class, 'study_mode_id');
    }
    public function level()
    {
        return $this->belongsTo(CourseLevel::class, 'course_level_id');
    }
    public function period_type()
    {
        return $this->belongsTo(PeriodType::class, 'period_type_id');
    }

    public function academic_info()
    {
        return $this->belongsTo(AcademicPeriodInformation::class, 'study_mode_id', 'study_mode_id')->where('academic_period_intake_id', $this->academic_period_intake_id);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function statements()
    {
        return $this->hasMany(Statement::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function statementsWithoutInvoice()
    {
        return $this->hasMany(Statement::class, 'collected_from')->whereNull('invoice_id')->where('amount', '>' , 0)->orderBy('created_at');
    }

}
