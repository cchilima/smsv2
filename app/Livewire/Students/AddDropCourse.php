<?php

namespace App\Livewire\Students;

use App\Helpers\Qs;
use App\Repositories\Academics\ClassAssessmentsRepo;
use App\Repositories\Academics\StudentRegistrationRepository;
use App\Repositories\Accounting\InvoiceRepository;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Enrollments\EnrollmentRepository;
use App\Traits\CanShowAlerts;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class AddDropCourse extends Component
{
    use CanShowAlerts;

    public $course_id;
    public $student;
    public $data;

    protected StudentRepository $studentRepo;
    protected StudentRegistrationRepository $studentRegistrationRepo;
    protected EnrollmentRepository $enrollmentRepo;
    protected ClassAssessmentsRepo $classaAsessmentRepo;
    protected InvoiceRepository $invoiceRepo;

    protected $rules = ['course_id' => 'required'];

    public function boot()
    {
        $this->studentRepo = app(StudentRepository::class);
        $this->studentRegistrationRepo = app(StudentRegistrationRepository::class);
        $this->enrollmentRepo = app(EnrollmentRepository::class);
        $this->classaAsessmentRepo = app(ClassAssessmentsRepo::class);
        $this->invoiceRepo = app(InvoiceRepository::class);
    }

    public function mount($student_id)
    {
        Gate::allowIf(Qs::userIsAdministrative());

        // Fetch student, all courses, and currently enrolled courses
        $this->data['student'] = $this->studentRegistrationRepo->getStudentById($student_id);
        $this->data['courses'] = $this->studentRegistrationRepo->getAll($student_id);
        $this->data['enrolled_courses'] = $this->studentRegistrationRepo->curentEnrolledClasses($student_id);

        // Extract the IDs of the enrolled courses
        $enrolledCourseIds = collect($this->data['enrolled_courses'])->pluck('course.id')->toArray();

        // Filter out the courses that are already enrolled
        $this->data['courses'] = collect($this->data['courses'])->reject(function ($course) use ($enrolledCourseIds) {
            return in_array($course->course_id, $enrolledCourseIds); // Assumes $course->id is the identifier
        })->values(); // Reset the keys to keep the collection neat

        $this->student = $this->data['student'];
    }

    public function dropCourse($enrollment_id)
    {
        if ($this->enrollmentRepo->dropCourse($enrollment_id)) {
            $this->mount($this->student->id);

            $this->flash('Course dropped successfully');
        } else {
            $this->flash('Failed to drop course', 'error');
        }
    }

    public function addCourse()
    {
        $this->validate();

        try {

            if ($this->enrollmentRepo->addCourse($this->student, $this->course_id)) {
                $this->mount($this->student->id);

                return $this->flash('Course added successfully');
            } else {
                return $this->flash('Failed to add course', 'error');
            }
        } catch (\Throwable $th) {
            return $this->flash('Failed to add course: ' . $th->getMessage(), 'error');
        }
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.students.add-drop-course', $this->data);
    }
}
