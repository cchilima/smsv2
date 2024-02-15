<?php

namespace App\Repositories\Applicants;

use DB;
use App\Models\Applicants\Applicant;
use App\Models\Applicants\ApplicantAttachment;

class ApplicantRepository
{

    public function initiateApplication($applicantIdentifier)
    {
        Applicant::create($applicantIdentifier);
    }

    public function saveApplication()
    {
        //
    }

    public function completeApplication()
    {
        //
    }

    public function completeApplicationWithAttachments()
    {
        //
    }
}
