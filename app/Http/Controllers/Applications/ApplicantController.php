<?php

namespace App\Http\Controllers\Applications;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Applications\{Application as ApplicationRequest, ApplicationPayment, ApplicationStep1};
use App\Models\Applications\ApplicantPayment;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Applications\ApplicantAttachmentRepository;
use App\Repositories\Applications\ApplicantRepository;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Redirect;

class ApplicantController extends Controller
{
    protected $applicantRepo;
    protected $studentRepo;
    protected $attachmentRepo;

    public function __construct(
        ApplicantRepository $applicantRepo,
        StudentRepository $studentRepo,
        ApplicantAttachmentRepository $attachmentRepo
    ) {
        $this->applicantRepo = $applicantRepo;
        $this->studentRepo = $studentRepo;
        $this->attachmentRepo = $attachmentRepo;
    }

    public function initiate()
    {
        return view('pages.applications.initiate_application');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $application_id)
    {
        $application = $this->applicantRepo->getApplication($application_id);

        return view('pages.applications.show', compact('application'));
    }

    /**
     * Collect application fee
     */
    public function collectFee(ApplicationPayment $request)
    {
        try {
            if (Qs::userIsTeamSAT() || Qs::userIsSuperAdmin()) {

                $data = $request;

                $collected = $this->applicantRepo->collectApplicantFee($data);

                return Qs::jsonStoreOk('Fee collected successfully');
            }

            return redirect(route('home'));
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to collect fee: ' . $th->getMessage());
        }
    }

    /**
     * Initiate application process.
     */
    public function startApplication(ApplicationStep1 $request)
    {
        $data = $request->only(['nrc', 'passport']);

        // check if applicant has incomplete applications
        $applications = $this->applicantRepo->checkApplications($data);


        if (count($applications) == 0) {

            $application = $this->applicantRepo->initiateApplication($data);

            if ($application) {

                return redirect()->route('application.complete_application', $application->id);
            } else {
                return Qs::jsonError('Failed to initiate application');
            }
        } else {
            return view('pages.applications.my_applications', compact('applications'));
        }
    }

    /**
     * Initiate application process.
     */
    public function completeApplication($application_id)
    {
        // Dropdown data
        $dropdownData = $this->getDropdownData();

        // Application
        $application = $this->applicantRepo->getApplication($application_id);

        // Redirect if application was already completed
        if ($application->status !== 'incomplete') {
            return redirect(route('application.initiate'))->with('flash_info', 'Application already completed');
        }

        // Application step 2
        return view('pages.applications.complete_application', array_merge($dropdownData, ['application_id' => $application_id, 'application' => $application]));
    }

    /**
     * Get dropdown data for the create form.
     */
    private function getDropdownData()
    {
        $residencyData = [
            'towns' => $this->studentRepo->getTowns(),
            'provinces' => $this->studentRepo->getProvinces(),
            'countries' => $this->studentRepo->getCountries(),
        ];

        $profileData = [
            'maritalStatuses' => $this->studentRepo->getMaritalStatuses(),
            'relationships' => $this->studentRepo->getRelationships(),
        ];

        $academicData = [
            'programs' => $this->studentRepo->getPrograms(),
            'periodIntakes' => $this->studentRepo->getPeriodIntakes(),
            'studyModes' => $this->studentRepo->getStudyModes(),
            'courseLevels' => $this->studentRepo->getCourseLevels(),
            'periodTypes' => $this->studentRepo->getPeriodTypes(),
        ];

        return array_merge($residencyData, $profileData, $academicData);
    }

    /**
     * Initiate application process.
     */
    public function saveApplication(ApplicationRequest $request, $id)
    {
        try {
            $data = $request;

            /*   $data = $request->only([
    
                'nrc',
                'passport',
                'first_name',
                'middle_name',
                'last_name',
                'date_of_birth',
                'gender',
                'address',
                'postal_code',
                'email',
                'phone_number',
                'application_date',
                'status',
                'town_id',
                'province_id',
                'country_id',
                'program_id',
                'period_type_id',
                'study_mode_id',
                'academic_period_intake_id',
                'attachment'
    
            ]); */

            $data = $this->applicantRepo->changeDBOFromat($data);

            $application = $this->applicantRepo->saveApplication($data, $id);

            $isComplete = $this->applicantRepo->checkApplicationCompletion($id);

            if ($isComplete) {
                return redirect(route('application.start_application', $id))->with('flash_success', 'Application completed successfully.');
            } else {
                return Qs::jsonUpdateOk('Application progress saved. Fill out the rest of the form to complete your application.');
            }
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to save application: ' . $th->getMessage());
        }
    }

    public function downloadAttachment($attachment_id)
    {
        try {
            // Retrieve attachment details
            $attachment = $this->attachmentRepo->find($attachment_id);

            // Full path to the file
            $filePath = storage_path('app/public/uploads/attachments/applications/' . $attachment->attachment);

            // Return download response
            return response()->download($filePath, $attachment->attachment);
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to download attachment');
            // throw $th;
        }
    }

    public function ApplicationsSummary()
    {

        $data['not_paid'] = $this->applicantRepo->getNotPaidCount();
        $data['paid'] = $this->applicantRepo->getPaidCount();
        $data['programs'] = $this->applicantRepo->getProgramsCount();
        $data['girls'] = $this->applicantRepo->getGirlsCount();
        $data['boys'] = $this->applicantRepo->getBoysCount();
        $data['declined'] = $this->applicantRepo->getDeclinedCount();
        $data['completed'] = $this->applicantRepo->getCompletedCount();
        $data['incomplete'] = $this->applicantRepo->getIncompleteCount();
        $data['processed'] = $this->applicantRepo->getProcessedCount();
        $data['pending'] = $this->applicantRepo->getProcessedCount();
        $data['applicants'] = $this->applicantRepo->getApplicantsCount();
        $data['app_apps'] = $this->applicantRepo->getLastFiveAppsCount();

        return view('pages.applications.applications_summary_index', $data);
    }

    public function ApplicationsStatus(string $status, $id)
    {
        $id = Qs::decodeHash($id);
        $applications = [];
        if ($id == 1) {
            $applications = $this->applicantRepo->getApplicationStatus($status);
        } else if ($id == 2) {
            $applications = $this->applicantRepo->getGender($status);
        } else if ($id == 3) {
            $applications = $this->applicantRepo->getPaymentStatus($status);
        }

        return view('pages.applications.index', compact('applications'));
        //enum('incomplete', 'pending', 'complete', 'accepted', 'rejected')
        //enum('Male', 'Female')
    }


    /**
     * Download registration summary.
     */
    public function provisional(Request $request)
    {
        try {
            $applicant = $this->applicantRepo->getApplication($request->applicant_id);

            $fileName = $applicant->applicant_code . '-provisional letter-' . now()->format('d-m-Y-His') . '.pdf';

            $pdf = Pdf::loadView('templates.pdf.provisional-letter', compact('applicant'));

            return $pdf->download($fileName);
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to generate provisional letter: ' . $th->getMessage());
        }
    }
}
