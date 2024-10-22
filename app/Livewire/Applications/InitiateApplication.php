<?php

namespace App\Livewire\Applications;

use App\Repositories\Applications\ApplicantRepository;
use App\Traits\CanShowAlerts;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

class InitiateApplication extends Component
{
    use CanShowAlerts;

    private ApplicantRepository $applicantRepo;

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


        try {
            $this->validate();

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
                return redirect()->route('application.my-applications', $applications[0]['id']);
            }
        } catch (ValidationException $ve) {
            $errorMessage = Str::before($ve->getMessage(), '(and');
            $this->flash(message: $errorMessage, type: 'error');
        } catch (\Throwable $th) {
            $this->flash('Failed to initialise application.', 'error');
        }
    }

    public function rules()
    {
        return [
            'nrc' => 'required_without:passport|min:11|max:11',
            'passport' => 'required_without:nrc|max:12',
        ];
    }

    public function messages()
    {
        return [
            'nrc.required_without' => 'Enter an NRC or passport number to proceed',
            'passport.required_without' => 'Enter an NRC or passport number to proceed',
        ];
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.applications.initiate-application');
    }
}
