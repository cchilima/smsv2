<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class ClassAssessment extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['total', 'end_date', 'assessment_type_id', 'academic_period_class_id'];

    public function assessment_type()
    {
        return $this->belongsTo(AssessmentType::class, 'assessment_type_id');
    }
    public function grades()
    {
        return $this->belongsTo(Grade::class, 'class_assessment_id', 'id');
    }

    public function academicPeriodClass()
    {
        return $this->belongsTo(AcademicPeriodClass::class, 'academic_period_class_id');
    }
}
