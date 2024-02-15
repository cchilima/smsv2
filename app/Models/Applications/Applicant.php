<?php

namespace App\Models\Applications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nrc',
        'passport',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'address',
        'postal_code',
        'email',
        'phone_number',
        'application_date',
        'status',
        'town_id',
        'province_id',
        'country_id',
        'program_id',
        'period_type_id',
        'study_mode_id',
        'academic_period_intake_id',
    ];
    
}
