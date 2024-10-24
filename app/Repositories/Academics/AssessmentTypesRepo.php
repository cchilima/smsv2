<?php

namespace App\Repositories\Academics;

use App\Models\Academics\AssessmentType;

class AssessmentTypesRepo
{
    public function create($data)
    {
        return AssessmentType::create($data);
    }

    public function getAll($order = 'name', bool $executeQuery = true)
    {
        $query = AssessmentType::orderBy($order, 'asc');
        return $executeQuery ? $query->get() : $query;
    }
    public function getPeriodType($data)
    {
        return AssessmentType::where($data)->get();
    }

    public function update($id, $data)
    {
        return AssessmentType::find($id)->update($data);
    }

    public function find($id)
    {
        return AssessmentType::find($id);
    }
}
