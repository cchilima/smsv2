<?php

namespace App\Repositories\Applications;

use App\Http\Requests\Applications\Attachment;
use App\Models\Applications\Applicant;
use App\Models\Applications\ApplicantAttachment;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Support\Arr;

class ApplicantAttachmentRepository
{
    public function getAll()
    {
        return ApplicantAttachment::all();
    }

    public function find($attachment_id)
    {
        return ApplicantAttachment::find($attachment_id);
    }
}
