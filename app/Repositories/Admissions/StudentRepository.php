<?php

namespace App\Repositories\Admissions;


use Ramsey\Uuid\Type\Integer;
use Illuminate\Support\Facades\Hash;
use App\Models\Users\{User, UserType};
use App\Models\Residency\{Town, Province, Country};
use App\Models\Profile\{MaritalStatus, Relationship};
use App\Models\Admissions\{Student, AcademicPeriodIntake};
use App\Models\Academics\{AcademicPeriod, Program, CourseLevel, StudyMode, PeriodType};

class StudentRepository
{

    const DEFAULT_STUDENT_PASSWORD = 'secret';

    public function createUser($data)
    {

        $data['password'] = $this->encryptPassword(self::DEFAULT_STUDENT_PASSWORD);
        $data['user_type_id'] = $this->getStudentUserType();

        return User::create($data);
    }

    public function getAll()
    {
        return Student::paginate(20);
    }

    public function update($id, $data)
    {
        return Student::find($id)->update($data);
    }

    public function find($id)
    {
        return Student::find($id);
    }

    public function findUser($id)
    {
        return User::find($id);
    }

    public function getTowns()
    {
        return Town::all(['id', 'name']);
    }

    public function getProvinces()
    {
        return Province::all(['id', 'name']);
    }

    public function getCountries()
    {
        return Country::all(['id', 'country']);
    }

    public function getMaritalStatuses()
    {
        return MaritalStatus::all(['id', 'status']);
    }

    public function getRelationships()
    {
        return Relationship::all(['id', 'relationship']);
    }

    public function getPrograms()
    {
        return Program::all(['id', 'name', 'code']);
    }

    public function getPeriodIntakes()
    {
        return AcademicPeriodIntake::all(['id', 'name']);
    }

    public function getStudyModes()
    {
        return StudyMode::all(['id', 'name']);
    }

    public function getPeriodTypes()
    {
        return PeriodType::all(['id', 'name']);
    }

    public function getCourseLevels()
    {
        return CourseLevel::all(['id', 'name']);
    }
    public function getIntakes()
    {
        return AcademicPeriodIntake::all(['id', 'name']);
    }

    public function addStudentId($studentData)
    {

        $year = date("y");
        $semester = (date("m") <= 6) ? 1 : 2;

        // Get the latest student ID from the database
        $lastID = Student::latest('id')->first();

        if ($lastID) {
            //$afterRemovingFirstThree = intval(substr($lastID->id, 3));
            //$addonw = $afterRemovingFirstThree + 1;
            $firstTwoValues = substr($lastID->student_id, 0, 2);
            $afterRemovingFirstThree = intval(substr($lastID->student_id, 3));
            if (intval($firstTwoValues) === intval($year)) {
                $addonw = $afterRemovingFirstThree + 1;
            }else{
                $addonw = 000 + 1;
            }
           // $studentNumber = str_pad($addonw, 3, '0', STR_PAD_LEFT);

            $studentNumber = str_pad($addonw, 3, '0', STR_PAD_LEFT);
        } else {
            // If there is no last ID, use an ID starting with the current year and zeros
            $studentNumber = '0001';
        }

        $finalID = strlen($studentNumber);

        if ($finalID == 1) {
            $concatStudentNumber = "000$studentNumber";
        } else if ($finalID == 2) {
            $concatStudentNumber = "00$studentNumber";
        } else if ($finalID == 3) {
            $concatStudentNumber = "0$studentNumber";
        } else if ($finalID == 4) {
            $concatStudentNumber = "$studentNumber";
        }

        $semester = (date("m") <= 6) ? 1 : 2;

        $studentData['id'] =  $year . $semester . $concatStudentNumber;

        return $studentData;

    }

    public function removePrefixes($nextOfKinDataWithPrefix)
    {
        // Remove the "kin_" prefix from keys
        $nextOfKinData = array_combine(

        array_map(function ($key) {
            return preg_replace('/^kin_/', '', $key);
        }, array_keys($nextOfKinDataWithPrefix)),

            $nextOfKinDataWithPrefix
        );

        return $nextOfKinData;

    }

    public function changeDBOFromat($personalData)
    {
        $personalData['date_of_birth'] = date('Y-m-d', strtotime($personalData['date_of_birth']));

        return $personalData;
    }

    public function studentSearch($searchText){

        return User::where('first_name','LIKE','%'.$searchText.'%')
            ->orWhere('last_name','LIKE','%'.$searchText.'%')
            ->orWhere('id','LIKE','%'.$searchText.'%')->get();
    }

    private function getStudentUserType()
    {
        $userTypeId = UserType::where('name', 'Student')->value('id');

        return $userTypeId;

    }

    private function encryptPassword($password)
    {
         // Hash the password before creating the user
         $hashedPassword = Hash::make($password);

         return $hashedPassword;
    }

    public function getStudentInfor($id){

        return Student::with('period_type','level','intake','study_mode','program',
            'user.userPersonalInfo','user.userNextOfKin.relationship','user.userPersonalInfo.userMaritalStatus',
            'user.userPersonalInfo.province','user.userPersonalInfo.country','user.userPersonalInfo.town')->where('user_id',$id)->get()->first();
    }

    public function resetPassword($resetPasswordData)
    {
        // encrypt password
        $resetPasswordData['password'] = $this->encryptPassword($resetPasswordData['password']);

        // get user account
        $user = $this->findUser($resetPasswordData['user_id']);

        // remove non essential array properties
        unset($resetPasswordData['user_id']);
        unset($resetPasswordData['password_confirmation']);

        // handle checkbox value
        $resetPasswordData['force_password_reset'] = ($resetPasswordData['force_password_reset'] == 'on') ? 1 : 0;

        // update user account
        $passwordResetted = $user->update($resetPasswordData);

        // give user feedback
        if ($passwordResetted) {
            return true;
        }
    }

}
