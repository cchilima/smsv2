<?php

namespace App\Http\Controllers\Applications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Applications\ApplicantRepository;
use App\Http\Requests\Applications\{ApplicationStep1};

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
        //
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
        $data = $request->only(['nrc','passport']);

        $application = $this->applicantRepo->initiateApplication($data);

        if ($application) {
            return redirect()->route('application.complete_application', $application->id);
        } else {
            return Qs::json(false,'msg.create_failed');
        }

        
    }


    /**
     * Initiate application process.
     */
    public function completeApplication()
    {
        // Dropdown data
        $dropdownData = $this->getDropdownData();

         // Application step 2
         return view('pages.applications.complete_application', $dropdownData);
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
}
