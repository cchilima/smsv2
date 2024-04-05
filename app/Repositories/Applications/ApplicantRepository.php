<?php

namespace App\Repositories\Applications;

use App\Http\Requests\Applications\Attachment;
use App\Models\Applications\Applicant;
use App\Models\Applications\ApplicantAttachment;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Support\Arr;

class ApplicantRepository
{
    public function getAll()
    {
        return Applicant::all();
    }

    public function getByDate($date)
    {
        return Applicant::whereDate('created_at', $date);
    }

    public function initiateApplication($applicantIdentifier)
    {
        $application = Applicant::create($applicantIdentifier);

        if ($application) {
            return $application;
        } else {
            return false;
        }
    }

    public function saveApplication($data, $application_id)
    {
        $application = Applicant::find($application_id);

        if (!$application) {
            // Handle the case where the application with the given ID is not found
            return false;
        }

        DB::beginTransaction();

        try {
            // Handle upload
            if ($data->hasFile('attachment')) {
                $this->UploadAttachment($data, $application_id);
            }

            // Update the application attributes
            $application->update($data->toArray());

            // Check if you can make application as pending
            //  $this->completeApplication($application->id);

            DB::commit();

            return $application_id;
        } catch (\Exception $e) {
            DB::rollback();
            // Handle exceptions, you may log or throw an error here
            return false;  // Returning false to indicate the update failed
        }
    }

    public function checkApplications($data)
    {
        if ($data['nrc']) {
            return Applicant::where('nrc', $data['nrc'])->get();
        } elseif ($data['passport']) {
            return Applicant::where('passport', $data['passport'])->get();
        }
    }

    // public function completeApplication($application_id)
    // {
    //     // Find the application
    //     $application = Applicant::find($application_id);

    //     // Check if application exists
    //     if (!$application) {
    //         return response()->json(['message' => 'Application not found'], 404);
    //     }

    //     // Check if all fields except status are filled
    //     $fieldsToCheck = array_except($application->toArray(), ['status']);
    //     $allFieldsFilled = collect($fieldsToCheck)->filter()->isEmpty();

    //     // Update status to pending if all fields except status are filled
    //     if ($allFieldsFilled) {
    //         $application->status = 'pending';
    //         $application->save();
    //     }

    //     return response()->json(['message' => 'Application updated successfully'], 200);
    // }

    public function checkApplicationCompletion($application_id)
    {
        // Find the application
        $application = Applicant::find($application_id);

        // Check if application exists
        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        $applicationArr = $application->toArray();

        // Check if all mandatory fields are filled 
        $fieldsToCheck = Arr::except($applicationArr, ['status', 'middle_name', 'postal_code', 'period_type_id', 'application_date']);

        $allFieldsFilled = array_filter($fieldsToCheck, fn ($value) => $value === null);

        // Check if application has an attachments
        $hasAttachments = $application->attachments()->count() > 0;

        // Update status to pending if all fields except status are filled
        if (empty($allFieldsFilled) && $hasAttachments) {
            $application->status = 'pending';
            $application->save();

            return true;
        }

        // return response()->json(['message' => 'Application completed successfully'], 200);
        return false;
    }

    public function uploadAttachment($request, $application_id)
    {
        if ($request->hasFile('attachment')) {
            $application = Applicant::find($application_id);

            // Retrieve the uploaded file
            $attachment = $request->file('attachment');

            // Generate a unique filename
            $filename = time() . '.' . $attachment->getClientOriginalExtension();

            // Store the file in the storage disk
            $path = $attachment->storeAs('uploads/attachments/applications', $filename, 'public');

            return $application->attachments()->create(['type' => 'Results', 'attachment' => $filename]);
        }

        return null;  // Return null if no file is uploaded
    }

    public function changeDBOFromat($data)
    {
        if ($data['date_of_birth']) {
            $data['date_of_birth'] = date('Y-m-d', strtotime($data['date_of_birth']));
        }

        return $data;
    }

    public function getApplication($application_id)
    {
        return Applicant::find($application_id);
    }
}
