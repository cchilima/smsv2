<?php

namespace App\Livewire\Pages\Admissions\Students;

use App\Helpers\Qs;
use App\Repositories\Accounting\StudentFinancesRepository;
use App\Traits\CanRefreshDataTable;
use App\Traits\CanShowAlerts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Finances extends Component
{
    use CanRefreshDataTable, CanShowAlerts;

    public array $data;
    protected $studentFinancesRepo;

    public function boot()
    {
        $this->studentFinancesRepo = app(StudentFinancesRepository::class);
    }

    public function mount()
    {
        Gate::allowIf(Qs::userIsStudent());

        $student = Auth::user()->student;
        $this->data = $this->studentFinancesRepo->getStudentFinancialInfo($student);
        $this->data['student'] = $student;
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.admissions.students.finances', $this->data);
    }
}
