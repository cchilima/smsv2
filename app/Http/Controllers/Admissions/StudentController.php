<?php

namespace App\Http\Controllers\Admissions;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Students\Student;
use App\Http\Requests\Students\StudentUpdate;
use App\Repositories\Admissions\StudentRepository;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

    protected $studentRepo;

    public function __construct(StudentRepository $studentRepo)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->studentRepo = $studentRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dropdownData = $this->getDropdownData();
        $students = $this->studentRepo->getAll();

        return view('pages.students.index', compact('students'), $dropdownData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dropdownData = $this->getDropdownData();

        return view('pages.students.create', $dropdownData);
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
     * Store a newly created resource in storage.
     */
    public function store(Student $request)
    {
        try {

            DB::beginTransaction();

            $userData = $request->only(['first_name', 'middle_name', 'last_name', 'gender', 'email', 'user_type_id']);
            $personalData = $request->only(['date_of_birth', 'street_main', 'post_code', 'telephone', 'mobile', 'marital_status_id', 'town_id', 'province_id', 'country_id', 'nrc', 'passport_number']);
            $studentData = $request->only(['program_id', 'study_mode_id', 'period_type_id', 'academic_period_intake_id', 'course_level_id', 'graduated', 'admission_year']);

            // Extract nextOfKinData with the "kin_" prefix
            $nextOfKinDataWithPrefix = $request->only(['kin_full_name', 'kin_mobile', 'kin_telephone', 'kin_town_id', 'kin_province_id', 'kin_country_id', 'kin_relationship_id']);

            // Remove kin_ prefixes
            $nextOfKinData = $this->studentRepo->removePrefixes($nextOfKinDataWithPrefix);

            // Create user and obtain the instance
            $user = $this->studentRepo->createUser($userData);

            // Use the created user instance to associate and create UserPersonalInfo

            // Change DOB date format
            $personalData = $this->studentRepo->changeDBOFromat($personalData);

            // Create personal info record
            $userPersonalInfo = $user->userPersonalInfo()->create($personalData);

            // Use the created user instance to associate and create NextOfKin
            $nextOfKin = $user->userNextOfKin()->create($nextOfKinData);

            // Use the created user instance to associate and create Student

            // Add student id to the data we have
            $studentData = $this->studentRepo->addStudentId($studentData);

            // Create the student record

            $student = $user->student()->create($studentData);


            DB::commit();

            return Qs::jsonStoreOk();

        } catch (\Exception $e) {

            DB::rollBack();
            // Log the error or handle it accordingly
            return Qs::json(false, 'msg.create_failed');
        }
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
        $dropdownData = $this->getDropdownData();

        // Returns a student object
        $student = $this->studentRepo->find($id);

        // Returns a user object
        $user = $this->studentRepo->findUser($student->user_id);

        // Methods to get userNextOfKin and userPersonalInfo from the User model
        $nextOfKin = $user->userNextOfKin;
        $personalInfo = $user->userPersonalInfo;

        // Pass all relevant variables to the view
        return view('pages.students.edit', compact('student', 'user', 'nextOfKin', 'personalInfo'), $dropdownData);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Student $request, $id)
    {
        try {

            DB::beginTransaction();

            $userData = $request->only(['first_name', 'middle_name', 'last_name', 'gender', 'email', 'user_type_id']);
            $personalData = $request->only(['date_of_birth', 'street_main', 'post_code', 'telephone', 'mobile', 'marital_status_id', 'town_id', 'province_id', 'country_id', 'nrc', 'passport_number']);
            $studentData = $request->only(['program_id', 'study_mode_id', 'period_type_id', 'academic_period_intake_id', 'course_level_id', 'graduated']);

            // Extract nextOfKinData with the "kin_" prefix
            $nextOfKinDataWithPrefix = $request->only(['kin_full_name', 'kin_mobile', 'kin_telephone', 'kin_town_id', 'kin_province_id', 'kin_country_id', 'kin_relationship_id']);

            // Remove the "kin_" prefix from keys
            $nextOfKinData = array_map(function ($key) {
                return preg_replace('/^kin_/', '', $key);
            }, array_flip($nextOfKinDataWithPrefix));

            // Check if the user already exists
            $user = $this->studentRepo->findUser($id);

            // Update the user data
            $user->update($userData);

            // Update or create UserPersonalInfo
            $userPersonalInfo = $user->userPersonalInfo()->update($personalData);

            // Update or create NextOfKin
            $nextOfKin = $user->userNextOfKin()->update($nextOfKinData);

            // Update or create Student
            $student = $user->student()->update($studentData);

            DB::commit();

            return Qs::jsonStoreOk();

        } catch (\Exception $e) {

            DB::rollBack();
            // Log the error or handle it accordingly
            return Qs::jsonError(__('msg.update_failed'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function search(Request $request)
    {
        if (isset($request['query']) && !$request['query'] == '') {
            $searchText = $request['query'];
            $users['users'] = $this->studentRepo->studentSearch($searchText);
            return view('pages.students.student_search', $users);

        } else {
            return view('pages.students.student_search');
        }
    }

    //student management controller
    public function studentShow($id)
    {
        $data['student'] = $this->studentRepo->getStudentInfor($id);
        $data['countries'] = $this->studentRepo->getCountries();
        $data['programs'] = $this->studentRepo->getPrograms();
        $data['towns'] = $this->studentRepo->getTowns();
        $data['provinces'] = $this->studentRepo->getProvinces();
        $data['course_levels'] = $this->studentRepo->getCourseLevels();
        $data['periodIntakes'] = $this->studentRepo->getIntakes();
        $data['studyModes'] = $this->studentRepo->getStudyModes();
        $data['periodTypes'] = $this->studentRepo->getPeriodTypes();
        $data['relationships'] = $this->studentRepo->getRelationships();
        $data['maritalStatuses'] = $this->studentRepo->getMaritalStatuses();
        //'student','countries','programs','towns','provinces','course_levels','periodIntakes','studyModes','periodTypes','relationships','maritalStatuses'
        return view('pages.students.show', $data);
    }
}
