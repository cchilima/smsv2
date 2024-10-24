<?php

namespace App\Livewire\Applications;

use App\Helpers\Qs;
use App\Http\Requests\Applications\Attachment as AttachmentRequest;
use App\Models\Applications\Applicant;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Applications\ApplicantRepository;
use App\Traits\CanShowAlerts;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

class CompleteApplication extends Component
{
    use WithFileUploads, CanShowAlerts;

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
    public $year_applying_for;


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

    // Define an array of section names in the desired order
    protected $sections = ['personal_info', 'academic_info', 'next_of_kin', 'results'];

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
        $this->year_applying_for = $this->applicant->year_applying_for;
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
        $this->year_applying_for = $this->year_applying_for ?: null;
        $this->academic_period_intake_id = $this->academic_period_intake_id ?: null;
        $this->study_mode_id = $this->study_mode_id ?: null;
        $this->program_id = $this->program_id ?: null;
        $this->marital_status_id = $this->marital_status_id ?: null;

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
            'year_applying_for' => $this->year_applying_for,
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
        if ($this->applicantRepo->checkApplicationCompletion($this->applicant->id)) {
            return $this->flash('Application completed successfully. Pay the application fee (K' . Qs::getSetting('application_fee') . ') to continue.');
        }

        $this->flash('Application progress saved');
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

                $this->flash('Grade added successfully');

                return $this->mount($this->applicant->id);
            } else {
                $this->flash('Provide all mandatory fields', 'error');
            }
        } catch (\Throwable $th) {
            $this->flash('Failed to add grade: ' . $th->getMessage(), 'error');
        }
    }

    public function uploadDocument()
    {
        if (empty($this->results)) {
            return $this->flash('No file selected', 'warning');
        }

        $this->validate();

        try {
            DB::beginTransaction();

            $this->applicantRepo->uploadAttachment($this->results, $this->applicant->id);
            $this->reset(['results']);

            $this->flash('Results added successfully');

            DB::commit();

            return $this->mount($this->applicant->id);
        } catch (\Throwable $th) {
            $this->flash('Failed to upload file ' . $th->getMessage(), 'error');
            DB::rollBack();
        }
    }

    public function updated($propertyName)
    {
        // $this->saveProgress();
    }

    /**
     * Navigate to the previous section.
     */
    public function previousSection()
    {
        // If results file has been attached, upload it before navigating to the previous section
        if (!empty($this->results)) $this->uploadDocument();

        // Get the current section index
        $currentIndex = array_search($this->currentSection, $this->sections);

        // If not the first section, go to the previous section
        if ($currentIndex > 0) {
            $this->currentSection = $this->sections[$currentIndex - 1];
        }
    }

    /**
     * Navigate to the next section.
     */
    public function nextSection()
    {
        // If results file has been attached, upload it before navigating to the next section
        if (!empty($this->results)) $this->uploadDocument();

        // Get the current section index
        $currentIndex = array_search($this->currentSection, $this->sections);

        // If not the last section, go to the next section
        if ($currentIndex < count($this->sections) - 1) {
            $this->currentSection = $this->sections[$currentIndex + 1];
        }
    }

    /**
     * Navigate to a specific section of the application form
     * 
     * @param string $sectionName The name of the section to navigate to 
     */
    public function setSection(string $sectionName): void
    {
        $this->currentSection = $sectionName;
    }

    /**
     * Validation rules
     */
    public function rules()
    {
        return (new AttachmentRequest())->rules();
    }

    #[Layout('components.layouts.app-bootstrap')]
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

        $currentYear = date('Y'); // Get the current year
        $currentMonth = date('n'); // Get the current month as a number (1 = January, 12 = December)

        // Initialize the years array with the mandatory next year
        $years = [
            (int)$currentYear + 1, // Next year is always included
        ];

        // Include the current year only if the month is before June
        if ($currentMonth < 7) {
            $years[] = (int)$currentYear;
        }

        // Sort the years to maintain order if necessary (optional)
        sort($years);

        if ($this->year_applying_for && $this->year_applying_for != null && $this->year_applying_for != '') {
            // Fetch all intakes as an Eloquent Collection
            $periodIntakes = $this->studentRepo->getPeriodIntakes();

            // If applying for the current year, filter intakes based on the current or future months
            if ((int)$this->year_applying_for == $currentYear) {
                $currentMonth = date('F'); // Get the current month (1-12)
                // Use the `filter` method on the Eloquent Collection
                $intakes = $periodIntakes->filter(function ($intake) use ($currentMonth) {
                    // Assuming $intake has a 'month' field representing the intake month
                    return $intake->name >= $currentMonth; // Adjust based on your data structure
                });
            } else {
                // If not the current year, return all intakes
                $intakes = $periodIntakes;
            }
        } else {
            $intakes = collect(); // Use an empty collection if no year is selected
        }

        return view(
            'livewire.applications.complete-application',
            [
                'programs' => $this->studentRepo->getPrograms(),
                'studyModes' => $this->studentRepo->getStudyModes(),
                'periodIntakes' => $intakes,
                'relationships' => $this->studentRepo->getRelationships(),
                'marital_statuses' => $this->studentRepo->getMaritalStatuses(),
                'countries' => $this->studentRepo->getCountries(),
                'provinces' => $provinces,
                'towns' => $towns,
                'kin_countries' => $this->studentRepo->getCountries(),
                'kin_provinces' => $kin_provinces,
                'kin_towns' => $kin_towns,
                'schools' => $this->studentRepo->getSchools(),
                'subjects' => $this->studentRepo->getSubjects(),
                'years' => $years,
            ]
        );
    }
}
