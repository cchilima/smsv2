<?php

namespace App\Repositories\Admissions;

use App\Models\Academics\{AcademicPeriod, Program, CourseLevel, StudyMode, PeriodType};
use App\Models\Accounting\{Fee, Quotation, Invoice, PaymentMethod};
use App\Models\Admissions\{Student, AcademicPeriodIntake};
use App\Models\Profile\{MaritalStatus, Relationship};
use App\Models\Residency\{Town, Province, Country};
use App\Models\Users\{User, UserType};
use App\Repositories\Users\UserPersonalInfoRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Type\Integer;

class StudentRepository
{
    const DEFAULT_STUDENT_PASSWORD = 'secret';

    public UserPersonalInfoRepository $userPersonalInfoRepo;

    public function __construct(UserPersonalInfoRepository $userPersonalInfoRepo)
    {
        $this->userPersonalInfoRepo = $userPersonalInfoRepo;
    }

    public function createUser($data)
    {
        $data['password'] = $this->encryptPassword(self::DEFAULT_STUDENT_PASSWORD);
        $data['user_type_id'] = $this->getStudentUserType();

        return User::create($data);
    }

    public function getAll($executeQuery = true)
    {
        $query = Student::with(['user', 'user.userPersonalInfo', 'program', 'level',]);

        return $executeQuery ? $query->get() : $query;
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

    public function getUserByStudentId($id)
    {
        return Student::find($id)->user;
    }

    public function getProvinceTowns($id)
    {
        return Town::where('province_id', $id)->get();
    }

    public function getTowns()
    {
        return Town::all(['id', 'name']);
    }

    public function getCountryProvinces($id)
    {
        return Province::where('country_id', $id)->get();
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

    public function getSubjects()
    {

        $subjects = [

            ["id" => 1, "name" => "English Language"],
            ["id" => 2, "name" => "Mathematics"],
            ["id" => 3, "name" => "Biology"],
            ["id" => 4, "name" => "Chemistry"],
            ["id" => 5, "name" => "Physics"],
            ["id" => 6, "name" => "Civic Education"],
            ["id" => 7, "name" => "Agricultural Science"],
            ["id" => 8, "name" => "Business Studies"],
            ["id" => 9, "name" => "Computer Studies"],
            ["id" => 10, "name" => "Geography"],
            ["id" => 11, "name" => "History"],
            ["id" => 12, "name" => "Literature in English"],
            ["id" => 13, "name" => "Religious Education"],
            ["id" => 14, "name" => "Design and Technology"],
            ["id" => 15, "name" => "Art"],
            ["id" => 16, "name" => "Music"],
            ["id" => 17, "name" => "Physical Education"],
            ["id" => 18, "name" => "Home Economics"],
            ["id" => 19, "name" => "Commerce"],
            ["id" => 20, "name" => "Principles of Accounts"],
            ["id" => 21, "name" => "French"],
            ["id" => 22, "name" => "Additional Mathematics"],
            ["id" => 23, "name" => "Technical Drawing"]
        ];


        return $subjects;
    }


    public function getSchools()
    {

        $schools = [

            ["id" => 1, "name" => "Kitwe boys secondary"],
            ["id" => 2, "name" => "Alim secondary"],
            ["id" => 3, "name" => "Hellen Kaunda secondary"],
            ["id" => 4, "name" => "Faith christian school"],
        ];


        return $schools;
    }

    public function addStudentId($studentData)
    {
        //        $year = date("y");
        //        $semester = (date("m") <= 6) ? 1 : 2;
        //
        //        // Get the latest student ID from the database
        //        $lastID = Student::latest('id')->first();
        //
        //        if ($lastID) {
        //            //$afterRemovingFirstThree = intval(substr($lastID->id, 3));
        //            //$addonw = $afterRemovingFirstThree + 1;
        //            $firstTwoValues = substr($lastID->student_id, 0, 2);
        //            $afterRemovingFirstThree = intval(substr($lastID->student_id, 3));
        //            if (intval($firstTwoValues) === intval($year)) {
        //                $addonw = $afterRemovingFirstThree + 1;
        //            }else{
        //                $addonw = 000 + 1;
        //            }
        //           // $studentNumber = str_pad($addonw, 3, '0', STR_PAD_LEFT);
        //
        //            $studentNumber = str_pad($addonw, 3, '0', STR_PAD_LEFT);
        //        } else {
        //            // If there is no last ID, use an ID starting with the current year and zeros
        //            $studentNumber = '0001';
        //        }
        //
        //        $finalID = strlen($studentNumber);
        //
        //        if ($finalID == 1) {
        //            $concatStudentNumber = "000$studentNumber";
        //        } else if ($finalID == 2) {
        //            $concatStudentNumber = "00$studentNumber";
        //        } else if ($finalID == 3) {
        //            $concatStudentNumber = "0$studentNumber";
        //        } else if ($finalID == 4) {
        //            $concatStudentNumber = "$studentNumber";
        //        }
        //
        //        $semester = (date("m") <= 6) ? 1 : 2;

        $year = date('y');
        $semester = (date('m') <= 6) ? 1 : 2;

        // Get the latest student ID from the database
        $lastID = Student::latest('id')->first();

        if ($lastID) {
            $firstTwoValues = substr($lastID->id, 0, 2);
            $afterRemovingFirstThree = intval(substr($lastID->id, 3));
            if (intval($firstTwoValues) === intval($year)) {
                $addonw = $afterRemovingFirstThree + 1;
            } else {
                $addonw = 0 + 1;
            }
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

        $semester = (date('m') <= 6) ? 1 : 2;

        // $studentData =  $year . $semester . $concatStudentNumber;

        // echo $finalID;
        // echo $studentData['id'];

        // return $studentData;

        $studentData['id'] = $year . $semester . $concatStudentNumber;

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

    public function studentSearch($searchText)
    {
        return User::where('first_name', 'LIKE', '%' . $searchText . '%')
            ->orWhere('last_name', 'LIKE', '%' . $searchText . '%')
            ->orWhere('id', 'LIKE', '%' . $searchText . '%')
            ->get();
    }

    public function getFees($student_id)
    {

        // get student
        $student = $this->getStudentInfor($student_id);

        // get current academic period fees
        $academic_period_fees = $student->academic_info ? $student->academic_info->academic_period->academic_period_fees : [];

        // extract only the fee ids
        $academic_period_fee_ids = $academic_period_fees ? $academic_period_fees->pluck('fee_id') : [];

        // get all fees minus the fees in current academic period
        $fees = Fee::whereNotIn('id', $academic_period_fee_ids)->get();

        return $fees;
    }

    public function getPaymentMethods()
    {
        return PaymentMethod::all(['id', 'name']);
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

    public function getStudentInfor($id)
    {
        return Student::with(
            'period_type',
            'level',
            'intake',
            'study_mode',
            'program',
            'user.userPersonalInfo',
            'user.userNextOfKin.relationship',
            'user.userPersonalInfo.userMaritalStatus',
            'user.userPersonalInfo.province',
            'user.userPersonalInfo.country',
            'user.userPersonalInfo.town',
            'sponsors'
        )->where('user_id', $id)->get()->first();
    }

    public function getStudentInforByID($id)
    {
        return Student::with(
            'period_type',
            'level',
            'intake',
            'study_mode',
            'program',
            'user.userPersonalInfo',
            'user.userNextOfKin.relationship',
            'user.userPersonalInfo.userMaritalStatus',
            'user.userPersonalInfo.province',
            'user.userPersonalInfo.country',
            'user.userPersonalInfo.town'
        )->find($id);
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

    /**
     * Get all invoices for a student.
     *
     * @param  string  $studentId The ID of the student
     * @param  bool  $executeQuery Whether to return a collection or the query builder
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection
     */
    public function getInvoicesByStudent(string $studentId, bool $executeQuery = true)
    {
        $query = Invoice::with(['period', 'raisedBy', 'details'])
            ->where('student_id', $studentId);

        return $executeQuery ? $query->get() : $query;
    }


    /**
     * Get all quotations for a student.
     *
     * @param  string  $studentId The ID of the student
     * @param  bool  $executeQuery Whether to return a collection or the query builder
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection
     */
    public function getQuotationsByStudent(string $studentId, bool $executeQuery = true)
    {
        $query = Quotation::with(['period', 'raisedBy', 'details'])
            ->where('student_id', $studentId);

        return $executeQuery ? $query->get() : $query;
    }

    // /**
    //  * Delete a student record and all associated records.
    //  *
    //  * @param  string  $id The ID of the student
    //  * @return bool
    //  */
    // public function destroy($id)
    // {
    //     $user = $this->findUser($id);
    //     $student = $user->student;

    //     // Delete uploaded passport photo
    //     $passportPhotoPath = $user->userPersonalInfo?->passport_photo_path ?? null;

    //     if ($passportPhotoPath) {
    //         $this->userPersonalInfoRepo->deletePassportPhoto($passportPhotoPath);
    //     }

    //     // Delete user personal information
    //     $user->userPersonalInfo->delete();

    //     // Delete financial records
    //     $student->statements->each->delete();
    //     $student->statementsWithoutInvoice->each->delete();
    //     $student->receipts->each->delete();
    //     $student->receiptsNonInvoiced->each->delete();
    //     $student->invoicesDetails->each->delete();
    //     $student->invoices->each->delete();

    //     // Delete academic records
    //     $student->grades->each->delete();
    //     $student->enrollments->each->delete();

    //     // Delete next of kin record
    //     $user->userNextOfKin->delete();

    //     // Delete student record
    //     $student->delete();

    //     // Delete user
    //     $user->delete();

    //     $student = Student::find($id);
    //     $user->userPersonalInfo->delete();
    //     $user->userNextOfKin->delete();
    //     $user->delete();
    // }
}
