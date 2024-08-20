<?php

namespace App\Livewire\Pages\Academics\AcademicPeriodClasses;

use App\Helpers\Qs;
use App\Repositories\Academics\AcademicPeriodClassRepository;
use App\Repositories\Academics\AcademicPeriodRepository;
use App\Traits\CanRefreshDataTable;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    use CanRefreshDataTable;

    public array $data;

    protected AcademicPeriodClassRepository $academicPeriodClassRepo;
    protected AcademicPeriodRepository $academicPeriodRepository;

    public function boot()
    {
        $this->academicPeriodClassRepo = app(AcademicPeriodClassRepository::class);
        $this->academicPeriodRepository = app(AcademicPeriodRepository::class);
    }

    public function mount()
    {
        Gate::allowIf(Qs::userIsTeamSA());

        $this->data['periodClasses'] = $this->academicPeriodClassRepo->getAll();
        $this->data['courses'] = $this->academicPeriodClassRepo->getCourses();
        $this->data['instructors'] = $this->academicPeriodClassRepo->getInstructors();
        $this->data['academicPeriods'] = $this->academicPeriodRepository->getAllOpenedAc();
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.academics.academic-period-classes.index', $this->data);
    }
}
