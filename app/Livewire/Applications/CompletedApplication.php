<?php

namespace App\Livewire\Applications;

use DB;
use Auth;
use Livewire\Component;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Applications\ApplicantRepository;


class CompletedApplication extends Component
{

    private ApplicantRepository $applicantRepo;
    private StudentRepository $studentRepo;

    public $currentSection = 'application';
    public $application_id;


    public function mount($application_id)
    {
        $this->application_id = $application_id;
    }

    public function boot(ApplicantRepository $applicantRepo, StudentRepository $studentRepo)
    {
        $this->studentRepo = $studentRepo;
        $this->applicantRepo = $applicantRepo;
    }

    public function reject()
    {

        $applicantObj = $this->applicantRepo->getApplication($this->application_id);

        // Update application status
        $applicantObj->update(['status' => 'rejected']);

        $this->dispatch('rejected');
    }

    public function accept()
    {
        DB::beginTransaction();
    
        try {

            $applicantObj = $this->applicantRepo->getApplication($this->application_id);
            $applicant = $applicantObj->toArray();

            // Custom array_only function
            function array_only($array, $keys) {
                return array_intersect_key($array, array_flip((array) $keys));
            }
    
            // Extracting user data
            $userData = array_only($applicant, [
                'first_name', 
                'middle_name', 
                'last_name', 
                'gender', 
                'email', 
            ]);
    
            $userData['user_type_id'] = 3;
    
            // Extracting personal data
            $personalData = array_only($applicant, [
                'date_of_birth', 
                'marital_status_id', 
                'town_id', 
                'province_id', 
                'country_id', 
                'nrc', 
                'passport_number', 
                'passport_photo_path'
            ]);
    
            $personalData['street_main'] = $applicant['address'];
            $personalData['mobile'] = $applicant['phone_number'];
    
            // Extracting student data
            $studentData = array_only($applicant, [
                'program_id', 
                'study_mode_id', 
                'academic_period_intake_id', 
            ]);
    
            $studentData['period_type_id'] = 1;
            $studentData['course_level_id'] = 1;
            $studentData['admission_year'] = date('Y');
    
            // Create user account
            $user = $this->studentRepo->createUser($userData);
    
            // Create personal info record
            $userPersonalInfo = $user->userPersonalInfo()->create($personalData);
    
            // Use the created user instance to associate and create NextOfKin
            //dd($applicantObj->nextOfKin->toArray());
            $nextOfKin = $user->userNextOfKin()->create($applicantObj->nextOfKin->toArray());
    
            // Add student id to the data we have
            $studentData = $this->studentRepo->addStudentId($studentData);
    
            // Create the student record
            $student = $user->student()->create($studentData);

            // Update application status
            $applicantObj->update(['status' => 'accepted']);

            $this->dispatch('accepted');

    
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
        }
    
        DB::commit();
    }
    

    public function setSection($section)
    {
        $this->currentSection = $section;
    }

    public function render()
    {
        return view('livewire.applications.completed-application', ['application' => $this->applicantRepo->getApplication($this->application_id)]);
    }
}