<?php

namespace App\Livewire\Pages\Academics\AssessmentTypes;

use App\Helpers\Qs;
use App\Repositories\Academics\AssessmentTypesRepo;
use App\Traits\CanRefreshDataTable;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    use CanRefreshDataTable;

    protected AssessmentTypesRepo $assessmentTypesRepo;

    public function boot()
    {
        $this->assessmentTypesRepo = app(AssessmentTypesRepo::class);
    }

    public function mount()
    {
        Gate::allowIf(Qs::userIsTeamSA());
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.academics.assessment-types.index', [
            'assessments' => $this->assessmentTypesRepo->getAll()
        ]);
    }
}
