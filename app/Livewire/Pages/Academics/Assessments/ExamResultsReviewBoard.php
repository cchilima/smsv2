<?php

namespace App\Livewire\Pages\Academics\Assessments;

use App\Helpers\Qs;
use App\Repositories\Academics\AcademicPeriodRepository;
use App\Repositories\Academics\ClassAssessmentsRepo;
use App\Repositories\Academics\CourseLevelsRepository;
use App\Repositories\Academics\ProgramsRepository;
use App\Traits\CanRefreshDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ExamResultsReviewBoard extends Component
{
    public array $data = [];

    protected ClassAssessmentsRepo $classAssessmentRepo;
    protected AcademicPeriodRepository $academicPeriodRepo;
    protected ProgramsRepository $programRepo;
    protected CourseLevelsRepository $courseLevelRepo;

    public function boot()
    {
        $this->classAssessmentRepo = app(ClassAssessmentsRepo::class);
        $this->academicPeriodRepo = app(AcademicPeriodRepository::class);
        $this->programRepo = app(ProgramsRepository::class);
        $this->courseLevelRepo = app(CourseLevelsRepository::class);
    }

    public function mount(Request $request)
    {
        Gate::allowIf(Qs::userIsTeamSA());

        $aid = $request->query('aid');
        $pid = $request->query('pid');
        $level = $request->query('level');

        $aid = Qs::decodeHash($aid);
        $pid = Qs::decodeHash($pid);
        $level = Qs::decodeHash($level);

        $this->data['grades'] = $this->classAssessmentRepo->getGrades($level, $pid, $aid);

        $this->data['period'] = $this->academicPeriodRepo->find($aid);
        $this->data['program_data'] = $this->programRepo->findOne($pid);
        $this->data['level'] = $this->courseLevelRepo->find($level);
        $this->data['students'] = $this->classAssessmentRepo->total_students($level, $pid, $aid);
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.academics.assessments.exam-results-review-board', $this->data);
    }
}
