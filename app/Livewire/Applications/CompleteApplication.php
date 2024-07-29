<?php

namespace App\Livewire\Applications;

use App\Models\Applications\Applicant;
use Livewire\WithFileUploads;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Applications\ApplicantRepository;
use Livewire\Component;

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
        $this->phone_number = $this->applicant->phone_number;
        $this->status = $this->applicant->status;
        $this->town_id = $this->applicant->town_id;
        $this->province_id = $this->applicant->province_id;
        $this->country_id = $this->applicant->country_id;
        $this->program_id = $this->applicant->program_id;
        $this->study_mode_id = $this->applicant->study_mode_id;
        $this->academic_period_intake_id = $this->applicant->academic_period_intake_id;
    }

    public function boot(StudentRepository $studentRepo, ApplicantRepository $applicantRepo)
    {
        $this->studentRepo = $studentRepo;
        $this->applicantRepo = $applicantRepo;
    }

    public function saveProgress()
    {
        $this->applicant->update([
            'applicant_code' => $this->applicant_code,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'address' => $this->address,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'status' => $this->status,
            'town_id' => $this->town_id,
            'province_id' => $this->province_id,
            'country_id' => $this->country_id,
            'program_id' => $this->program_id,
            'study_mode_id' => $this->study_mode_id,
            'academic_period_intake_id' => $this->academic_period_intake_id,
        ]);

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

                session()->flash('success', 'Grade uploaded successfully');
            }
        } catch (\Throwable $th) {

            dd($th);
            session()->flash('error', 'Grade uploaded failed.');
        }
    }

    public function uploadDocument()
    {
        if (is_file($this->results)) {

            $this->applicantRepo->uploadAttachment($this->results, $this->applicant->id);
            $this->reset(['results']);
        } else {
            // Handle the case where $this->results is not a file
            throw new Exception('The provided results are not a valid file.');
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

        return view('livewire.applications.complete-application', ['programs' => $this->studentRepo->getPrograms(), 'studyModes' => $this->studentRepo->getStudyModes(), 'periodIntakes' => $this->studentRepo->getPeriodIntakes(), 'countries' => $this->studentRepo->getCountries(), 'provinces' => $provinces, 'towns' => $towns, 'schools' => $this->studentRepo->getSchools(), 'subjects' => $this->studentRepo->getSubjects()]);
    }
}
