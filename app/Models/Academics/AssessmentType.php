<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function class_assessment()
    {
        return $this->hasOne(ClassAssessment::class, 'assessment_type_id','id');
    }
}
