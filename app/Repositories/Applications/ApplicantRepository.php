<?php

namespace App\Repositories\Applications;

use DB;
use Illuminate\Support\Arr;
use App\Models\Applications\Applicant;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Applications\Attachment;
use App\Models\Applications\{ApplicantAttachment, ApplicantPayment};



class ApplicantRepository
{

    private function generateUniqueApplicantCode()
    {
        do {
            $code = $this->generateMixedCaseCode(10); // Adjust the length as needed
        } while (Applicant::where('applicant_code', $code)->exists());

        return $code;
    }

    private function generateMixedCaseCode($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function collectApplicantFee($data)
    {

        $amount = $data->amount;
        $method = $data->payment_method_id;

        try {
            
            $applicant = Applicant::where('applicant_code', $data->applicant)->first();
            $updated = ApplicantPayment::create(['applicant_id' => $applicant->id, 'amount' => $amount, 'payment_method_id' => $method]);

            if($updated){
                $this->checkApplicationCompletion($applicant->id);
            }
            
            return true;

        } catch (\Throwable $th) {
            dd($th);
            return false;
        }

       
        
    }

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
        $applicant_code = $this->generateUniqueApplicantCode();

        $applicantIdentifier['applicant_code'] = $applicant_code;

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
               $this->checkApplicationCompletion($application_id);

            DB::commit();

            return $application_id;

        } catch (\Exception $e) {
            DB::rollback();
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
        $fieldsToCheck = Arr::except($applicationArr, ['status', 'middle_name', 'period_type_id', 'application_date', 'nrc', 'passport']);


        $allFieldsFilled = array_filter($fieldsToCheck, fn ($value) => $value === null);

        // Check if application has an attachments
        $hasAttachments = $application->attachment()->count() > 0;

        // Check if application has payment(s)
        $feePaid = $application->payment->sum('amount');

        // Update status to pending if all fields except status are filled
        if (empty($allFieldsFilled) && $hasAttachments && $feePaid < 150) {
            $application->status = 'pending';
            $application->save();

            return true;

        } elseif(empty($allFieldsFilled) && $hasAttachments && $feePaid >= 150){
           
            $application->status = 'complete';
            $application->save();

            return true;
        }

        return false;
    }

    public function uploadAttachment($document, $application_id)
    {


        if ($document) {

            $application = Applicant::find($application_id);
        
            $exists = $application->attachment;
        
            if (!$exists) {
        
                // Retrieve the uploaded file
                $attachment = $document; //$request->file('attachment');
        
                // Generate a unique filename
                $filename = time() . '.' . $attachment->getClientOriginalExtension();
        
                // Store the file in the storage disk
                $path = $attachment->storeAs('uploads/attachments/applications', $filename, 'public');
        
                return $application->attachment()->create(['type' => 'Results', 'attachment' => $filename]);
        
            } else {
        
                // Delete previous file using unlink
                $previousFile = public_path('storage/uploads/attachments/applications/' . $application->attachment->attachment);
                if (file_exists($previousFile)) {
                    unlink($previousFile);
                }
        
                // Retrieve the uploaded file
                $attachment = $document; //$request->file('attachment');
        
                // Generate a unique filename
                $filename = time() . '.' . $attachment->getClientOriginalExtension();
        
                // Store the file in the storage disk
                $path = $attachment->storeAs('uploads/attachments/applications', $filename, 'public');
        
                // Update the existing attachment record with the new file details
                return $application->attachment()->update(['type' => 'Results', 'attachment' => $filename]);
            }
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

    //applications count summary

    // Method to get the count of not paid applicants
    public function getNotPaidCount() {
        return Applicant::where('status', 'not_paid')
            ->count();
    }

    // Method to get the count of paid applicants
    public function getPaidCount() {
        return Applicant::where('status', 'paid')
            ->count();
    }

    // Method to get the count of distinct programs
    public function getProgramsCount() {
        return Applicant::distinct('program_id')
            ->count('program_id');
    }

    // Method to get the count of female applicants
    public function getGirlsCount() {
        return Applicant::where('gender', 'female')
            ->count();
    }

    // Method to get the count of male applicants
    public function getBoysCount() {
        return Applicant::where('gender', 'male')
            ->count();
    }

    // Method to get the count of declined applicants
    public function getDeclinedCount() {
        return Applicant::where('status', 'declined')
            ->count();
    }

    // Method to get the count of completed applications
    public function getCompletedCount() {
        return Applicant::where('status', 'completed')
            ->count();
    }

    // Method to get the count of incomplete applications
    public function getIncompleteCount() {
        return Applicant::where('status', 'incomplete')
            ->count();
    }

    // Method to get the count of processed applications
    public function getProcessedCount() {
        return Applicant::where('status', 'processed')
            ->count();
    }

    // Method to get the total count of applicants
    public function getApplicantsCount() {
        return Applicant::whereDate('created_at', '>=', now())->count();
    }

    // Method to get the count of last five applications
    public function getLastFiveAppsCount() {
        return Applicant::orderBy('created_at', 'desc')
            ->take(5)
            ->count();
    }
    public function getApplicationStatus($status) {
        return Applicant::where('status', $status)
            ->get();
    }
    public function getGender($gender) {
        return Applicant::where('gender', $gender)
            ->get();
    }
    public function getPaymentStatus($status) {
        return Applicant::with('payment')->where('payment.amount', 150)
            ->get();
    }
}
