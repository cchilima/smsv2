<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'registration_date', 'late_registration_date', 'ac_start_date', 'ace_end_date', 'period_type_id', 'academic_period_intake_id', 'type', 'study_mode_id'];
}
