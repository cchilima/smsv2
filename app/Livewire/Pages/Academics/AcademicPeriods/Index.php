<?php

namespace App\Livewire\Pages\Academics\AcademicPeriods;

use App\Repositories\Academics\AcademicPeriodRepository;
use App\Traits\CanRefreshDataTable;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{

    use CanRefreshDataTable;

    public $data;

    protected AcademicPeriodRepository $academicPeriodRepo;

    public function boot()
    {
        $this->academicPeriodRepo = new AcademicPeriodRepository();
    }

    public function mount()
    {
        $this->data['periodTypes'] = $this->academicPeriodRepo->getPeriodTypes();
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.academics.academic-periods.index', $this->data);
    }
}
