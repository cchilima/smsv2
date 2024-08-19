<?php

namespace App\Livewire\Pages\Academics\AcademicPeriodClasses;

use App\Repositories\Academics\AcademicPeriodClassRepository;
use App\Repositories\Academics\AcademicPeriodRepository;
use App\Traits\CanRefreshDataTable;
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
        $this->academicPeriodClassRepo = new AcademicPeriodClassRepository();
        $this->academicPeriodRepository = new AcademicPeriodRepository();
    }

    public function mount()
    {
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
