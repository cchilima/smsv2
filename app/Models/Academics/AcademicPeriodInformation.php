<?php

namespace App\Models\Academics;

use App\Models\Admissions\AcademicPeriodIntake;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriodInformation extends Model
{
    protected $fillable = ['academic_period_intake_id', 'study_mode_id', 'view_results_threshold', 'exam_slip_threshold', 'registration_threshold', 'late_registration_end_date', 'late_registration_date', 'registration_date','academic_period_id'];
    use HasFactory;

    public function academic_period(){
        return $this->belongsTo(AcademicPeriod::class,'academic_period_id','id');
    }
    public function study_mode(){
        return $this->belongsTo(StudyMode::class,'study_mode_id','id');
    }
    public function intake(){
        return $this->belongsTo(AcademicPeriodIntake::class,'academic_period_intake_id','id');
    }
}
