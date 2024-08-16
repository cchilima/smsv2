<?php

namespace App\Livewire\Pages\Academics\Programs;

use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Repositories\Academics\DepartmentsRepository;
use App\Repositories\Academics\QualificationsRepository;
use App\Traits\CanRefreshDataTable;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    use CanRefreshDataTable;

    protected DepartmentsRepository $departmentRepo;
    protected QualificationsRepository $qualificationRepo;

    public function boot()
    {
        $this->departmentRepo = new DepartmentsRepository();
        $this->qualificationRepo = new QualificationsRepository();
    }

    public function mount()
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy']]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy']]);
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
