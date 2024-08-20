<?php

namespace App\Livewire\Pages\Academics\Programs;

use App\Helpers\Qs;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Repositories\Academics\DepartmentsRepository;
use App\Repositories\Academics\QualificationsRepository;
use App\Traits\CanRefreshDataTable;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    use CanRefreshDataTable;

    protected DepartmentsRepository $departmentRepo;
    protected QualificationsRepository $qualificationRepo;

    public function boot()
    {
        $this->departmentRepo = app(DepartmentsRepository::class);
        $this->qualificationRepo = app(QualificationsRepository::class);
    }

    public function mount()
    {
        Gate::allowIf(Qs::userIsTeamSA());
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.academics.programs.index', [
            'departments' => $this->departmentRepo->getAll(),
            'qualifications' => $this->qualificationRepo->getAll(),
        ]);
    }
}
