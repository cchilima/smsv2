<?php

namespace App\Repositories\Academics;

use App\Models\Admissions\AcademicPeriodIntake;

class AcademicPeriodIntakeRepository
{
    public function create($data)
    {
        return AcademicPeriodIntake::create($data);
    }

    public function getAll($order = 'name')
    {
        return AcademicPeriodIntake::orderBy($order)->get();
    }

    public function getPeriodType($data)
    {
        return AcademicPeriodIntake::where($data)->get();
    }

    public function update($id, $data)
    {
        return AcademicPeriodIntake::find($id)->update($data);
    }

    public function find($id)
    {
        return AcademicPeriodIntake::find($id);
    }
}
