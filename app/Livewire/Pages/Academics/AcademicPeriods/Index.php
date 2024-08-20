<?php

namespace App\Livewire\Pages\Academics\AcademicPeriods;

use App\Helpers\Qs;
use App\Repositories\Academics\AcademicPeriodRepository;
use App\Traits\CanRefreshDataTable;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{

    use CanRefreshDataTable;

    public $data;

    protected AcademicPeriodRepository $academicPeriodRepo;

    public function boot()
    {
        $this->academicPeriodRepo = app(AcademicPeriodRepository::class);
    }

    public function mount()
    {
        Gate::allowIf(Qs::userIsTeamSA());
        $this->data['periodTypes'] = $this->academicPeriodRepo->getPeriodTypes();
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.academics.academic-periods.index', $this->data);
    }
}
