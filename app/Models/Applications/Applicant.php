<?php

namespace App\Models\Applications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Applications\{ApplicantAttachment};
use App\Models\Academics\{Program,StudyMode};
use App\Models\Admissions\{AcademicPeriodIntake};
use App\Models\Residency\{Country, Province, Town};
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Applicant extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = [
        'applicant_code',
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


    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function town()
    {
        return $this->belongsTo(Town::class, 'town_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function intake()
    {
        return $this->belongsTo(AcademicPeriodIntake::class, 'academic_period_intake_id');
    }

    public function study_mode()
    {
        return $this->belongsTo(StudyMode::class, 'academic_period_intake_id');
    }

    public function attachments()
    {
        return $this->hasMany(ApplicantAttachment::class, 'applicant_id');
    }
    public function payment()
    {
        return $this->hasMany(ApplicantPayment::class, 'applicant_id');
    }




}
