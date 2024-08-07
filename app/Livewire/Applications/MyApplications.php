<?php

namespace App\Livewire\Applications;

use Livewire\Component;
use App\Models\Applications\Applicant;
use Livewire\Attributes\Layout;
use App\Repositories\Applications\ApplicantRepository;

class MyApplications extends Component
{

    private ApplicantRepository $applicantRepo;

    public $application;

    public function mount($id)
    {
        $this->application = Applicant::find($id);
    }

    public function boot(ApplicantRepository $applicantRepo)
    {
        $this->applicantRepo = $applicantRepo;
    }

    public function buildData()
    {
        return ['nrc' => $this->application->nrc, 'passport' => $this->application->passport];
    }

    public function startNewApplication()
    {
        $application = $this->applicantRepo->initiateApplication($this->buildData());

        return redirect()->route('application.complete_application', $application->id);
    }

    #[Layout('components.layouts.administrator')]
    public function render()
    {
        return view('livewire.applications.my-applications', ['applications' => $this->applicantRepo->checkApplications($this->buildData())]);
    }
}
