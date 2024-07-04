<?php

namespace App\Models\Applications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantGrade extends Model
{
    use HasFactory;

    protected $fillable = ['secondary_school', 'subject', 'grade', 'applicant_id'];

    public function application()
    {
        return $this->belongsTo(Applicant::class);
    }
}
