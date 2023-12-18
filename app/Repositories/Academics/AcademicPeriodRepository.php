<?php

namespace App\Repositories\Academics;

use App\Models\Academics\{AcademicPeriod, PeriodType, StudyMode};
use App\Models\Admissions\{AcademicPeriodIntake};

class AcademicPeriodRepository
{
    public function create($data)
    {
        return AcademicPeriod::create($data);
    }

    public function getAll($order = 'registration_date')
    {
        return AcademicPeriod::orderBy($order)->get();
    }


    public function update($id, $data)
    {
        return AcademicPeriod::find($id)->update($data);
    }

    public function find($id)
    {
        return AcademicPeriod::find($id);
    }

    public function getPeriodTypes()
    {
        return PeriodType::pluck('id', 'name');
    }

    public function getStudyModes()
    {
        return StudyMode::pluck('id', 'name');
    }

    public function getIntakes()
    {
        return AcademicPeriodIntake::pluck('id', 'name');
    }
}
