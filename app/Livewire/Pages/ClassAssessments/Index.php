<?php

namespace App\Livewire\Pages\ClassAssessments;

use App\Helpers\Qs;
use App\Repositories\Academics\AcademicPeriodRepository;
use App\Repositories\Academics\AssessmentTypesRepo;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    protected AcademicPeriodRepository $academicPeriodRepo;
    protected AssessmentTypesRepo $assessmentTypesRepo;

    public function boot()
    {
        $this->academicPeriodRepo = app(AcademicPeriodRepository::class);
        $this->assessmentTypesRepo = app(AssessmentTypesRepo::class);
    }

    public function mount()
    {
        Gate::allowIf(Qs::userIsTeamSA());
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.class-assessments.index', [
            'openAcademicPeriods' => $this->academicPeriodRepo->getAllopen(),
            'assessmentTypes' => $this->assessmentTypesRepo->getAll()
        ]);
    }
}
