<?php

namespace App\Livewire\Pages\Admissions\Students;

use App\Helpers\Qs;
use App\Repositories\Academics\ClassAssessmentsRepo;
use App\Repositories\Academics\StudentRegistrationRepository;
use App\Repositories\Accounting\InvoiceRepository;
use App\Repositories\Accounting\StudentFinancesRepository;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Enrollments\EnrollmentRepository;
use App\Repositories\Sponsor\SponsorsRepository;
use App\Repositories\Users\UserPersonalInfoRepository;
use App\Repositories\Users\UserRepository;
use App\Traits\CanRefreshDataTable;
use App\Traits\CanShowAlerts;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use CanRefreshDataTable,
        CanShowAlerts,
        WithFileUploads;

    public $data;
    public $financialInfo;

    protected UserRepository $userRepo;
    protected StudentRepository $studentRepo;
    protected StudentRegistrationRepository $studentRegistrationRepo;
    protected EnrollmentRepository $enrollmentRepo;
    protected ClassAssessmentsRepo $classaAsessmentRepo;
    protected InvoiceRepository $invoiceRepo;
    protected UserPersonalInfoRepository $userPersonalInfoRepo;
    protected StudentFinancesRepository $studentFinancesRepo;
    protected SponsorsRepository $sponsorsRepository;

    public function boot()
    {
        $this->userRepo = app(UserRepository::class);
        $this->studentRepo = app(StudentRepository::class);
        $this->studentRegistrationRepo = app(StudentRegistrationRepository::class);
        $this->enrollmentRepo = app(EnrollmentRepository::class);
        $this->classaAsessmentRepo = app(ClassAssessmentsRepo::class);
        $this->invoiceRepo = app(InvoiceRepository::class);
        $this->userPersonalInfoRepo = app(UserPersonalInfoRepository::class);
        $this->studentFinancesRepo = app(StudentFinancesRepository::class);
        $this->sponsorsRepository = app(SponsorsRepository::class);
    }

    public function mount($userId)
    {
        Gate::allowIf(Qs::userIsAdministrative());

        $this->data['user'] = $this->userRepo->find($userId);
        $this->data['student'] = $this->studentRepo->getStudentInfor($userId);
        $this->data['passport_photo_path'] = $this->data['student']->user->userPersonalInfo?->passport_photo_path ?? 'images/default-avatar.png';

        $this->data['countries'] = $this->studentRepo->getCountries();
        $this->data['programs'] = $this->studentRepo->getPrograms();
        $this->data['towns'] = $this->studentRepo->getTowns();
        $this->data['provinces'] = $this->studentRepo->getProvinces();
        $this->data['course_levels'] = $this->studentRepo->getCourseLevels();
        $this->data['periodIntakes'] = $this->studentRepo->getIntakes();
        $this->data['studyModes'] = $this->studentRepo->getStudyModes();
        $this->data['periodTypes'] = $this->studentRepo->getPeriodTypes();
        $this->data['relationships'] = $this->studentRepo->getRelationships();
        $this->data['maritalStatuses'] = $this->studentRepo->getMaritalStatuses();
        $this->data['paymentMethods'] = $this->studentRepo->getPaymentMethods();
        $this->data['fees'] = $this->studentRepo->getFees($userId);

        $this->data['courses'] = $this->studentRegistrationRepo->getAll($this->data['student']->id);
        $this->data['isRegistered'] = $this->studentRegistrationRepo->getRegistrationStatus($this->data['student']->id);
        $this->data['isWithinRegistrationPeriod'] = $this->studentRegistrationRepo->checkIfWithinRegistrationPeriod($this->data['student']->id);

        $this->data['isInvoiced'] = $this->studentRegistrationRepo->checkIfInvoiced($this->data['student']->id);
        $this->data['enrollments'] = $this->enrollmentRepo->getEnrollments($this->data['student']->id);
        $this->data['results'] = $this->classaAsessmentRepo->GetExamGrades($userId);
        $this->data['caresults'] = $this->classaAsessmentRepo->GetCaStudentGrades($userId);
        $this->data['enrolled_courses'] = $this->studentRegistrationRepo->curentEnrolledClasses($this->data['student']->id);
        $this->data['allInvoicesBalance'] = $this->invoiceRepo->paymentPercentageAllInvoices($this->data['student']->id);
        $this->data['hasOpenAcademicPeriod'] = $this->invoiceRepo->openAcademicPeriod($this->data['student']) != null;

        $this->financialInfo = $this->studentFinancesRepo->getStudentFinancialInfo($this->data['student']);
        $this->data['sponsors'] = $this->sponsorsRepository->getAll();
        $this->data['student_sponsor'] = $this->studentRepo->getAll();
    }

    public function updateFinancialStats()
    {
        $this->financialInfo = $this->studentFinancesRepo->getStudentFinancialInfo($this->data['student']);
    }

    public function updateCoursesAvailableForRegistration()
    {
        $this->data['courses'] = $this->studentRegistrationRepo->getAll($this->data['student']->id);
    }

    /**
     * Dynamically refresh specified Livewire component sections when a student is invoiced
     *
     * @param array $tableNames Names of any PowerGrid datatables to refresh on the page
     */
    public function invoiceStudentRefresh(array $tableNames): void
    {
        $this->refreshTables($tableNames);
        $this->updateFinancialStats();
        $this->updateCoursesAvailableForRegistration();
    }

    /**
     * Dynamically refresh specified Livewire component sections when a payment is collected
     *
     * @param array $tableNames Names of any PowerGrid datatables to refresh on the page
     */
    public function collectPaymentRefresh(array $tableNames): void
    {
        $this->invoiceStudentRefresh($tableNames);
    }

    /**
     * Handles uploads for passport photos
     */
    public function uploadPassportPhoto()
    {
        try {
            $this->validateOnly('passportPhoto');

            $this->data['passport_photo_path'] = $this->userPersonalInfoRepo
                ->uploadPassportPhoto(
                    $this->passportPhoto,
                    $this->data['user']->id
                );

            $this->data['user']->userPersonalInfo()->update(
                ['passport_photo_path' => $this->data['passport_photo_path']]
            );

            return Qs::goWithSuccess(['show.student', $this->data['user']->id], 'Passport photo updated successfully');
        } catch (ValidationException $ve) {
            return $this->flash($ve->errors()['passportPhoto'][0], 'error');
        } catch (\Throwable $th) {
            return $this->flash('<strong>Failed to upload passport photo:</strong> ' . $th->getMessage(), 'error');
        }
    }

    public function rules()
    {
        return [
            'passportPhoto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'passportPhoto.required' => 'Add a passport photo.',
            'passportPhoto.max' => 'The passport photo should not be greater than 5MB.',
            'passportPhoto.mimes' => 'The passport photo must be a file of type JPG or PNG.',
        ];
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.admissions.students.show', array_merge($this->data, $this->financialInfo));
    }
}
