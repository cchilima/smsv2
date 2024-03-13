<?php

namespace App\Repositories\Applications;

use DB;
use App\Models\Applications\Applicant;
use App\Models\Applications\ApplicantAttachment;

class ApplicantRepository
{

    public function initiateApplication($applicantIdentifier)
    {
        $application = Applicant::create($applicantIdentifier);

        if($application){
            return $application;
        } else {
            return false;
        }
    }

    public function saveApplication()
    {
        //Applicant::create($applicantIdentifier);
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
