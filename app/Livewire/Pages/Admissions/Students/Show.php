<?php

namespace App\Livewire\Pages\Admissions\Students;

use App\Helpers\Qs;
use App\Repositories\Academics\ClassAssessmentsRepo;
use App\Repositories\Academics\StudentRegistrationRepository;
use App\Repositories\Accounting\InvoiceRepository;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Enrollments\EnrollmentRepository;
use App\Traits\CanRefreshDataTable;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use CanRefreshDataTable;

    public $data;

    protected StudentRepository $studentRepo;
    protected StudentRegistrationRepository $studentRegistrationRepo;
    protected EnrollmentRepository $enrollmentRepo;
    protected ClassAssessmentsRepo $classaAsessmentRepo;
    protected InvoiceRepository $invoiceRepo;

    public function boot()
    {
        $this->studentRepo = app(StudentRepository::class);
        $this->studentRegistrationRepo = app(StudentRegistrationRepository::class);
        $this->enrollmentRepo = app(EnrollmentRepository::class);
        $this->classaAsessmentRepo = app(ClassAssessmentsRepo::class);
        $this->invoiceRepo = app(InvoiceRepository::class);
    }

    public function mount($userId)
    {
        Gate::allowIf(Qs::userIsAdministrative());

        $this->data['student'] = $this->studentRepo->getStudentInfor($userId);
        $this->data['studentId'] = $this->data['student']->id;

        $this->data['passport_photo_path'] = $this->data['student']->user->userPersonalInfo?->passport_photo_path
            ?? 'images/default-avatar.png';

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

        // Find student
        $student = $this->studentRepo->findUser($userId);

        $this->data['courses'] = $this->studentRegistrationRepo->getAll($student->student->id);

        $this->data['isRegistered'] = $this->studentRegistrationRepo
            ->getRegistrationStatus($student->student->id);

        $this->data['isWithinRegistrationPeriod'] = $this->studentRegistrationRepo
            ->checkIfWithinRegistrationPeriod($student->student->id);

        $this->data['isInvoiced'] = $this->studentRegistrationRepo->checkIfInvoiced($student->student->id);
        $this->data['enrollments'] = $this->enrollmentRepo->getEnrollments($student->student->id);
        $this->data['results'] = $this->classaAsessmentRepo->GetExamGrades($userId);
        $this->data['caresults'] = $this->classaAsessmentRepo->GetCaStudentGrades($userId);

        $this->data['percentage'] = $this->invoiceRepo->paymentPercentage($student->student->id);
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.admissions.students.show', $this->data);
    }
}
