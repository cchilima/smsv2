<?php

namespace App\Http\Controllers\Admissions;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accomodation\BedSpace;
use App\Http\Requests\Accomodation\Booking;
use App\Repositories\Accommodation\BedSpaceRepository;
use App\Repositories\Accommodation\BookingRepository;
use App\Repositories\Accommodation\HostelRepository;
use App\Repositories\Accommodation\RoomRepository;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\Custom\{SuperAdmin, TeamSA, TeamSAT};
use App\Http\Requests\Students\{Student, StudentUpdate, UserInfo, PersonalInfo, NextOfKinInfo, AcademicInfo, ResetPasswordInfo};
use App\Repositories\Academics\{ClassAssessmentsRepo, StudentRegistrationRepository};
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Accounting\InvoiceRepository;
use App\Repositories\Enrollments\EnrollmentRepository;
use App\Repositories\Users\{userNextOfKinRepository, UserPersonalInfoRepository, UserRepository};
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    protected $studentRepo;
    protected $registrationRepo;
    protected $userPersonalInfoRepo;
    protected $userRepo;
    protected $invoiceRepo;
    protected $userNextOfKinRepo, $enrollmentRepo, $classaAsessmentRepo;
    protected $booking_repository, $bed_space_repository, $rooms_repository, $hostel_repository;

    public function __construct(
        StudentRepository $studentRepo,
        StudentRegistrationRepository $registrationRepo,
        UserPersonalInfoRepository $userPersonalInfoRepo,
        UserRepository $userRepo,
        userNextOfKinRepository $userNextOfKinRepo,
        EnrollmentRepository $enrollmentRepo,
        ClassAssessmentsRepo $classaAsessmentRepo,
        InvoiceRepository $invoiceRepo,
        BookingRepository $booking_repository,
        HostelRepository $hostel_repository,
        RoomRepository $rooms_repository,
        BedSpaceRepository $bed_space_repository
    ) {
        //$this->middleware(TeamSA::class, ['except' => ['destroy']]);
        //$this->middleware(SuperAdmin::class, ['only' => ['destroy']]);
        $this->middleware(TeamSAT::class, ['except' => ['destroy',]]);

        $this->studentRepo = $studentRepo;
        $this->registrationRepo = $registrationRepo;
        $this->userPersonalInfoRepo = $userPersonalInfoRepo;
        $this->userRepo = $userRepo;
        $this->userNextOfKinRepo = $userNextOfKinRepo;
        $this->enrollmentRepo = $enrollmentRepo;
        $this->classaAsessmentRepo = $classaAsessmentRepo;
        $this->invoiceRepo = $invoiceRepo;
        $this->bed_space_repository = $bed_space_repository;
        $this->booking_repository = $booking_repository;
        $this->rooms_repository = $rooms_repository;
        $this->hostel_repository = $hostel_repository;
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

    public function list()
    {
        return view('pages.students.list');
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

            $passportPhotoObject = $personalData['passport_photo_path'] ?? null;

            // Upload passport photo
            if ($passportPhotoObject) {
                $personalData['passport_photo_path'] = $this->userPersonalInfoRepo->uploadPassportPhoto($passportPhotoObject, $user->id);
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
            return redirect(\route('students.index'));
            //return Qs::goWithSuccess('students.index','stored successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error or handle it accordingly
            // return redirect(\route('students.index'));
            return Qs::json('Failed to create record => ' . $e->getMessage(), false);
        }
        //return Qs::jsonStoreOk();
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
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $userData = null;
            $personalData = null;
            $nextOfKinDataWithPrefix = null;
            $studentData = null;

            // Instantiate request objects
            $userInfoRequest = new UserInfo();
            $personalInfoRequest = new PersonalInfo();
            $nextOfKinInfoRequest = new NextOfKinInfo();
            $academicInfoRequest = new AcademicInfo();

            // Determine the type of request and validate accordingly
            if ($request->email && $request->gender) {
                $userData = $request->validate($userInfoRequest->rules());
            } elseif ($request->nrc) {
                $personalData = $request->validate($personalInfoRequest->rules());
            } elseif ($request->kin_relationship_id) {
                $nextOfKinDataWithPrefix = $request->validate($nextOfKinInfoRequest->rules());
            } elseif ($request->program_id) {
                $studentData = $request->validate($academicInfoRequest->rules());
            } elseif ($request->passport_photo_path) {
                // Validate passport photo for photo update operations
                $personalData = $request->validate([
                    'passport_photo_path' => $personalInfoRequest->rules()['passport_photo_path']
                ]);
            }

            if ($nextOfKinDataWithPrefix) {
                // Remove the "kin_" prefix from keys
                $nextOfKinData = array_combine(
                    array_map(function ($key) {
                        return preg_replace('/^kin_/', '', $key);
                    }, array_keys($nextOfKinDataWithPrefix)),
                    $nextOfKinDataWithPrefix
                );
            }

            // Check if the user already exists
            $user = $this->studentRepo->findUser($id);

            // Determine what user info to update

            if ($userData) {
                // Update the user data
                $user->update($userData);
            } elseif ($personalData) {
                // Update or create UserPersonalInfo
                $passportPhotoObject = $personalData['passport_photo_path'] ?? null;

                // Upload passport photo
                if ($passportPhotoObject) {
                    $personalData['passport_photo_path'] = $this->userPersonalInfoRepo->uploadPassportPhoto($passportPhotoObject, $user->id);
                }

                $user->userPersonalInfo()->update($personalData);
            } elseif ($nextOfKinDataWithPrefix) {
                // Update or create NextOfKin
                $nextOfKin = $user->userNextOfKin()->update($nextOfKinData);
            } elseif ($studentData) {
                // Update or create Student
                $student = $user->student()->update($studentData);
            }

            DB::commit();

            return Qs::jsonStoreOk('Student record updated successfully');
        } catch (ValidationException $e) {
            return Qs::json($e->getMessage(), false);
        } catch (\Throwable $th) {
            DB::rollBack();

            // Log the error or handle it accordingly
            return Qs::jsonError('Failed to update student record: ' . $th->getMessage());
        }
    }

    public function resetAccountPassword(ResetPasswordInfo $request)
    {
        $resetPasswordData = $request->validated();

        try {
            $this->studentRepo->resetPassword($resetPasswordData);
            return Qs::jsonStoreOk('Password reset successfully');
        } catch (\Exception $e) {
            return Qs::jsonError('Failed to reset password: ' . $e->getMessage());
        }
    }

    public function destroy($studentId)
    {
        try {
            DB::beginTransaction();

            $this->studentRepo->destroy($studentId);

            DB::commit();

            return Qs::goBackWithSuccess('Student deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;

            return Qs::goBackWithError('Failed to delete student: '  . $e->getMessage());
        }
    }

    //student accommodation
    public function getAppliedBedSpaces()
    {
        $id = Auth::user();
        $student_id = \App\Models\Admissions\Student::where('user_id', $id->id)->first();
        $data['hostel'] = $this->hostel_repository->getAll();
        $data['open'] = $this->booking_repository->getOpenBookings();
        $data['closed'] = $this->booking_repository->getClosedBookingsOne($student_id->id);
        return view('pages.students.accommodation', $data);
    }

    public function applyBedSpace(Booking $request)
    {
        try {
            $data = $request->only(['student_id', 'bed_space_id']);
            $data['booking_date'] = date('Y-m-d', strtotime(now()));
            $data['expiration_date'] = date('Y-m-d', strtotime('+1 day', time()));

            $dataB['is_available'] = 'false';
            $data = $this->booking_repository->create($data);
            $this->bed_space_repository->update($data['bed_space_id'], $dataB);

            return Qs::jsonStoreOk('Booking created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create booking: ' . $th->getMessage());
        }
    }

    public function getRooms(string $id)
    {
        try {
            return $this->rooms_repository->getSpecificRooms($id);
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to get rooms: ' . $th->getMessage());
        }
    }

    public function getBedSpaces(string $ids)
    {
        try {
            $id = Auth::user();
            $student_id = \App\Models\Admissions\Student::where('user_id', $id->id)->first();
            $data['students'] = $this->bed_space_repository->getActiveStudentOne($student_id->id);
            $data['spaces'] = $this->bed_space_repository->getAvailable($ids);

            return $data;
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to get bed spaces: ' . $th->getMessage());
        }
    }
}
