<?php

namespace App\Repositories\Admissions;

use App\Models\Academics\{ Program, CourseLevel, StudyMode, PeriodType};
use App\Models\Admissions\{Student, AcademicPeriodIntake };
use App\Models\Profile\{ MaritalStatus, Relationship };
use App\Models\Residency\{ Town, Province, Country };
use App\Models\Users\{ User };

class StudentRepository
{
    public function createUser($data)
    {
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

    public function generateStudentId()
{
    $year = date("y");
    
    // Fetch last ID
    
    $lastID = Student::latest('id')->first();

    $finalID = ($lastID) ? $lastID->id + 1 : 1;

    $studentNumber = str_pad($finalID, 3, '0', STR_PAD_LEFT);

    $concatStudentNumber = ($studentNumber < 10) ? "000$studentNumber" : (($studentNumber > 99) ? "0$studentNumber" : "00$studentNumber");

    $semester = (date("m") <= 6) ? 1 : 2;

    return $year . $semester . $concatStudentNumber;
}
}
