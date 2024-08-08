<?php

namespace App\Livewire\Applications;

use App\Models\Applications\Applicant;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Applications\ApplicantRepository;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

class CompleteApplication extends Component
{
    use WithFileUploads;

    private StudentRepository $studentRepo;
    private ApplicantRepository $applicantRepo;

    public $applicant;
    public $applicant_code;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $date_of_birth;
    public $gender;
    public $address;
    public $postal_code;
    public $marital_status_id;
    public $email;
    public $phone_number;
    public $application_date;
    public $status;
    public $town_id;
    public $province_id;
    public $country_id;
    public $program_id;
    public $period_type_id;
    public $study_mode_id;
    public $academic_period_intake_id;

    public $results;
    public $secondary_school;
    public $subject;
    public $grade;


    public $kin_full_name;
    public $kin_mobile;
    public $kin_telephone;
    public $kin_address;
    public $kin_country_id;
    public $kin_province_id;
    public $kin_town_id;
    public $kin_relationship_id;

    public $currentSection = 'personal_info';

    public function mount($application_id)
    {
        $this->applicant = Applicant::find($application_id);

        $this->applicant_code = $this->applicant->applicant_code;
        $this->first_name = $this->applicant->first_name;
        $this->middle_name = $this->applicant->middle_name;
        $this->last_name = $this->applicant->last_name;
        $this->date_of_birth = $this->applicant->date_of_birth;
        $this->gender = $this->applicant->gender;
        $this->address = $this->applicant->address;
        $this->email = $this->applicant->email;
        $this->marital_status_id = $this->applicant->marital_status_id;
        $this->phone_number = $this->applicant->phone_number;
        $this->status = $this->applicant->status;
        $this->town_id = $this->applicant->town_id;
        $this->province_id = $this->applicant->province_id;
        $this->country_id = $this->applicant->country_id;
        $this->program_id = $this->applicant->program_id;
        $this->study_mode_id = $this->applicant->study_mode_id;
        $this->academic_period_intake_id = $this->applicant->academic_period_intake_id;


        $this->kin_full_name = $this->applicant->nextOfKin ? $this->applicant->nextOfKin->full_name : null;
        $this->kin_mobile = $this->applicant->nextOfKin ? $this->applicant->nextOfKin->mobile : null;
        $this->kin_telephone = $this->applicant->nextOfKin ? $this->applicant->nextOfKin->telephone : null;
        $this->kin_address = $this->applicant->nextOfKin ? $this->applicant->nextOfKin->address : null;
        $this->kin_country_id = $this->applicant->nextOfKin ? $this->applicant->nextOfKin->country_id : null;
        $this->kin_province_id = $this->applicant->nextOfKin ? $this->applicant->nextOfKin->province_id : null;
        $this->kin_town_id = $this->applicant->nextOfKin ? $this->applicant->nextOfKin->town_id : null;
        $this->kin_relationship_id = $this->applicant->nextOfKin ? $this->applicant->nextOfKin->relationship_id : null;

    }

    public function boot(StudentRepository $studentRepo, ApplicantRepository $applicantRepo)
    {
        $this->studentRepo = $studentRepo;
        $this->applicantRepo = $applicantRepo;
    }




    public function saveProgress()
{
    // Set country_id, province_id, and town_id to null if they are empty or if the higher-level field is empty
    $this->country_id = $this->country_id ?: null;
    $this->province_id = $this->country_id ? ($this->province_id ?: null) : null;
    $this->town_id = $this->province_id ? ($this->town_id ?: null) : null;

    // Update applicant information
    $this->applicant->update([
        'applicant_code' => $this->applicant_code,
        'first_name' => $this->first_name,
        'middle_name' => $this->middle_name,
        'last_name' => $this->last_name,
        'date_of_birth' => $this->date_of_birth,
        'gender' => $this->gender,
        'address' => $this->address,
        'email' => $this->email,
        'marital_status_id' => $this->marital_status_id,
        'phone_number' => $this->phone_number,
        'status' => $this->status,
        'town_id' => $this->town_id,
        'province_id' => $this->province_id,
        'country_id' => $this->country_id,
        'program_id' => $this->program_id,
        'study_mode_id' => $this->study_mode_id,
        'academic_period_intake_id' => $this->academic_period_intake_id,
    ]);

    // Set kin_country_id, kin_province_id, and kin_town_id to null if they are empty or if the higher-level field is empty
    $this->kin_country_id = $this->kin_country_id ?: null;
    $this->kin_province_id = $this->kin_country_id ? ($this->kin_province_id ?: null) : null;
    $this->kin_town_id = $this->kin_province_id ? ($this->kin_town_id ?: null) : null;

    // Update or create next of kin information
    $this->applicant->nextOfKin()->updateOrCreate(
        [
            // Define the attributes to find the record by
            'applicant_id' => $this->applicant->id
        ],
        [
            // Define the attributes to be updated or created
            'full_name' => $this->kin_full_name,
            'mobile' => $this->kin_mobile,
            'telephone' => $this->kin_telephone,
            'address' => $this->kin_address,
            'country_id' => $this->kin_country_id,
            'town_id' => $this->kin_town_id,
            'province_id' => $this->kin_province_id,
            'relationship_id' => $this->kin_relationship_id,
        ]
    );

    // Check the application completion status
    $this->applicantRepo->checkApplicationCompletion($this->applicant->id);
}



public function saveGrade()
{
    try {
        if ($this->secondary_school && $this->subject && $this->grade) {
            $existingGrade = $this->applicant->grades()
                ->where('secondary_school', $this->secondary_school)
                ->where('subject', $this->subject)
                ->first();

            if ($existingGrade) {
                // Update the existing record
                $existingGrade->update(['grade' => $this->grade]);
            } else {
                // Create a new record
                $this->applicant->grades()->create([
                    'secondary_school' => $this->secondary_school,
                    'subject' => $this->subject,
                    'grade' => $this->grade
                ]);
            }

            $this->reset(['subject', 'grade']);

            $this->dispatch('grade-added');
        } else {
            $this->dispatch('fill-all-fields');
        }
    } catch (\Throwable $th) {
        $this->dispatch('grade-failed');

    }
}




    public function uploadDocument()
    {
        if (is_file($this->results)) {
            $this->applicantRepo->uploadAttachment($this->results, $this->applicant->id);
            $this->reset(['results']);
            $this->dispatch('attachment-added');
            
        } else {
            // Handle the case where $this->results is not a file
            $this->dispatch('attachment-failed');
        }
    }


    public function updated($propertyName)
    {
        $this->saveProgress();
    }

    public function sectionChanged($section)
    {
        $this->currentSection = $section;
    }

    #[Layout('components.layouts.administrator')]
    public function render()
    {
        if ($this->country_id) {
            // get province under selected country
            $provinces = $this->studentRepo->getCountryProvinces($this->country_id);
        } else {
            $provinces = [];
        }

        if ($this->province_id) {
            // get towns under selected province.
            $towns = $this->studentRepo->getProvinceTowns($this->province_id);
        } else {
            $towns = [];
        }

        if ($this->kin_country_id) {
            // get province under selected country
            $kin_provinces = $this->studentRepo->getCountryProvinces($this->kin_country_id);
        } else {
            $kin_provinces = [];
        }

        if ($this->kin_province_id) {
            // get towns under selected province.
            $kin_towns = $this->studentRepo->getProvinceTowns($this->kin_province_id);
        } else {
            $kin_towns = [];
        }

        return view('livewire.applications.complete-application', 
        [
            'programs' => $this->studentRepo->getPrograms(), 
            'studyModes' => $this->studentRepo->getStudyModes(), 
            'periodIntakes' => $this->studentRepo->getPeriodIntakes(), 
            'relationships' => $this->studentRepo->getRelationships(), 
            'marital_statuses' => $this->studentRepo->getMaritalStatuses(), 
            'countries' => $this->studentRepo->getCountries(), 
            'provinces' => $provinces, 
            'towns' => $towns, 
            'kin_countries' => $this->studentRepo->getCountries(), 
            'kin_provinces' => $kin_provinces, 
            'kin_towns' => $kin_towns, 
            'schools' => $this->studentRepo->getSchools(), 
            'subjects' => $this->studentRepo->getSubjects()
        ]);
    }
}
