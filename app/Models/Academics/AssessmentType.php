<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class AssessmentType extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['name'];

    public function class_assessment()
    {
        return $this->hasOne(ClassAssessment::class, 'assessment_type_id','id');
    }
}
