<?php

namespace App\Http\Controllers\Applications;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Applications\{ApplicationStep1};
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Applications\ApplicantRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ApplicantController extends Controller
{
    protected $applicantRepo;
    protected $studentRepo;

    public function __construct(ApplicantRepository $applicantRepo, StudentRepository $studentRepo)
    {
        $this->applicantRepo = $applicantRepo;
        $this->studentRepo = $studentRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Application step 1
        return view('pages.applications.initiate_application');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        dd('update hit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Initiate application process.
     */
    public function startApplication(ApplicationStep1 $request)
    {
        $data = $request->only(['nrc', 'passport']);

        // check if applicant has incomplete applications
        $applications = $this->applicantRepo->checkApplications($data);


        if(count($applications) == 0){

            $application = $this->applicantRepo->initiateApplication($data);

            if ($application) {

                  return redirect()->route('application.complete_application', $application->id);

              } else {
                  return Qs::json(false, 'msg.create_failed');
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
    public function saveApplication(Request $request, $id)
    {

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

        $application = $this->applicantRepo->saveApplication($data , $id);

        if ($application) {

         return Qs::jsonApplicationUpdateOk('Application details saved successfully, but come back to complete your application.');

       //  return redirect()->route('application.complete_application', $application);


        } else {
            return Qs::json(false, 'msg.create_failed');
        }
    }
}
