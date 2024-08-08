<?php

namespace App\Livewire\Applications;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Repositories\Applications\ApplicantRepository;

class InitiateApplication extends Component
{
    private ApplicantRepository $applicationRepo;

    public $nrc;
    public $passport;

    public function boot(ApplicantRepository $applicantRepo)
    {
        $this->applicantRepo = $applicantRepo;
    }

    private function buildData()
    {

          // Check if at least one of nrc or passport has a value
          if (empty($this->nrc) && empty($this->passport)) {
            // Add an error message or handle the validation error
            session()->flash('error', 'Either NRC or Passport must be provided.');
            return;
        }

        $data = ['nrc' => $this->nrc, 'passport' => $this->passport];
        
        return $data;
    }

    public function saveAndProceed()
    {
        // check if applicant has incomplete applications
        $applications = $this->applicantRepo->checkApplications($this->buildData());
    
        if (count($applications) == 0) {

            $application = $this->applicantRepo->initiateApplication($this->buildData());
    
            if ($application) {
                return redirect()->route('application.complete_application', $application->id);
            } else {
                session()->flash('error', 'Failed to start application.');
            }

        } else {  

           return redirect()->route('application.my-applications', $applications[0]['id']); //view('pages.applications.my_applications', compact('applications'));
        }
    }    

    #[Layout('components.layouts.administrator')]
    public function render()
    {
        return view('livewire.applications.initiate-application');
    }
}
