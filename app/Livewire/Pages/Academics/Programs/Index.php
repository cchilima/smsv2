<?php

namespace App\Livewire\Pages\Academics\Programs;

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

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.academics.programs.index', [
            'departments' => $this->departmentRepo->getAll(),
            'qualifications' => $this->qualificationRepo->getAll(),
        ]);
    }
}
