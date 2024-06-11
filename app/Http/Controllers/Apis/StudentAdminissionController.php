<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Students\Student;
use App\Repositories\Academics\ClassAssessmentsRepo;
use App\Repositories\Academics\QualificationsRepository;
use App\Repositories\Academics\StudentRegistrationRepository;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Enrollments\EnrollmentRepository;
use App\Repositories\Users\userNextOfKinRepository;
use App\Repositories\Users\UserPersonalInfoRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentAdminissionController extends Controller
{
    protected $studentRepo;
    protected $registrationRepo;
    protected $userPersonalInfoRepo;
    protected $userRepo,$qualifications;
    protected $userNextOfKinRepo, $enrollmentRepo, $classaAsessmentRepo;

    public function __construct(
        StudentRepository $studentRepo,
        StudentRegistrationRepository $registrationRepo,
        UserPersonalInfoRepository $userPersonalInfoRepo,
        UserRepository $userRepo,
        userNextOfKinRepository $userNextOfKinRepo,
        QualificationsRepository $qualifications
    ) {
       // $this->middleware(TeamSA::class, ['except' => ['destroy']]);
       // $this->middleware(SuperAdmin::class, ['only' => ['destroy']]);

        $this->studentRepo = $studentRepo;
        $this->registrationRepo = $registrationRepo;
        $this->userPersonalInfoRepo = $userPersonalInfoRepo;
        $this->userRepo = $userRepo;
        $this->userNextOfKinRepo = $userNextOfKinRepo;
        $this->qualifications = $qualifications;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // return $this->qualifications->getAll();
        try {

            DB::beginTransaction();

            $userData = $request->only(['first_name', 'middle_name', 'last_name', 'gender', 'email', 'user_type_id']);
            $personalData = $request->only(['date_of_birth', 'street_main', 'post_code', 'telephone', 'mobile', 'marital_status_id', 'town_id', 'province_id', 'country_id', 'nrc', 'passport_number', 'passport_photo_path']);
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

            // Upload passport photo
            if ($passportPhotoPath = $personalData['passport_photo_path'] ?? null) {
                $personalData['passport_photo_path'] = $this->userPersonalInfoRepo->uploadPassportPhoto($passportPhotoPath);
            }

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

            return "success";
        } catch (\Exception $e) {

            DB::rollBack();
            // Log the error or handle it accordingly
            return 'msg.create_failed => ' . $e->getMessage();
        }
    }


}
