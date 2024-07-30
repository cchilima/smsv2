<?php

namespace App\Repositories\Academics;

use App\Models\Academics\PeriodType;

class PeriodTypeRepository
{
    public function create($data)
    {
        return PeriodType::create($data);
    }

    public function getAll($order = 'name')
    {
        return PeriodType::orderBy($order)->get();
    }

    public function update($id, $data)
    {
        return PeriodType::find($id)->update($data);
    }

    public function find($id)
    {
        return PeriodType::find($id);
    }

}
