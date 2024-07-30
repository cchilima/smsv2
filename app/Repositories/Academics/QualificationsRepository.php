<?php

namespace App\Repositories\Academics;

use App\Models\Academics\Qualification;

class QualificationsRepository
{
    public function create($data)
    {
        return Qualification::create($data);
    }

    public function getAll($order = 'name')
    {
        return Qualification::orderBy($order)->get();
    }

    public function getPeriodType($data)
    {
        return Qualification::where($data)->get();
    }

    public function update($id, $data)
    {
        return Qualification::find($id)->update($data);
    }

    public function find($id)
    {
        return Qualification::find($id);
    }
}
