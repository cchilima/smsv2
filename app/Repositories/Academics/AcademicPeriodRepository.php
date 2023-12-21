<?php

namespace App\Repositories\Academics;

use App\Models\Academics\{AcademicPeriod, AcademicPeriodFee, AcademicPeriodInformation, PeriodType, StudyMode};
use App\Models\Admissions\{AcademicPeriodIntake};
use App\Models\Accounting\Fee;

class AcademicPeriodRepository
{
    public function create($data)
    {
        return AcademicPeriod::create($data);
    }

    public function getAll($order = 'ac_start_date')
    {
        return AcademicPeriod::with('period_types')->orderBy($order)->get();
    }


    public function update($id, $data)
    {
        return AcademicPeriod::find($id)->update($data);
    }

    public function find($id)
    {
        return AcademicPeriod::with('period_types')->find($id);
    }
    public function findOne($id)
    {
        return AcademicPeriod::find($id);
    }
    public function getPeriodTypes()
    {
        return PeriodType::all(['id', 'name'])->paginate(10);
    }

    public function getStudyModes()
    {
        return StudyMode::all(['id', 'name'])->paginate(10);
    }

    public function getIntakes()
    {
        return AcademicPeriodIntake::all(['id', 'name'])->paginate(10);
    }
    public function getFees()
    {
        return Fee::all(['id', 'name']);
    }
    //methods for academic period information
    public function getAPInformation($id)
    {
        return AcademicPeriodInformation::with('academic_period','study_mode','intake')->where('academic_period_id',$id)->get()->first();
    }
    public function APcreate($data)
    {
        return AcademicPeriodInformation::create($data);
    }
    public function APUpdate($id,$data)
    {
        return AcademicPeriodInformation::find($id)->update($data);;
    }
    public function APFind($data)
    {
        return AcademicPeriodInformation::with('academic_period','study_mode','intake')->find($data);
    }

    //fee management

    public function APFeeCreate($data)
    {
        return AcademicPeriodFee::create($data);
    }

    public function getAPFeeInformation($id)
    {
        return AcademicPeriodFee::with('academic_period','fee')->where('academic_period_id',$id)->get();
    }

    public function getOneAPFeeInformation($id)
    {
        return AcademicPeriodFee::with('academic_period','fee')->find($id);
    }

    public function APFeeUpdate($id,$data)
    {
        return AcademicPeriodFee::find($id)->update($data);;
    }
}
