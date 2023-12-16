<?php

namespace App\Repositories\Academics;

use App\Models\Academics\Department;

class DepartmentsRepository
{
    public function create($data)
    {
        return Department::create($data);
    }

    public function getAll($order = 'name')
    {
        return Department::orderBy($order)->get();
    }

    public function getPeriodType($data)
    {
        return Department::where($data)->get();
    }

    public function update($id, $data)
    {
        return Department::find($id)->update($data);
    }

    public function find($id)
    {
        return Department::with('programs')->find($id);
    }
}
