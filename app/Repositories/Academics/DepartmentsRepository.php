<?php

namespace App\Repositories\Academics;

use App\Models\Academics\Department;
use App\Models\Academics\Program;
use App\Models\Academics\School;

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
    public function findBySlug($slug)
    {
        return Department::where('slug', $slug)->first();
    }

    //GET SCHOOLS
    public function getSchools()
    {
        return School::get();
    }

    public function getProgramsByDepartment($departmentId, $executeQuery = true)
    {
        $query = Program::with(['qualification', 'department'])
            ->where('department_id', $departmentId);

        return $executeQuery ? $query->get() : $query;
    }
}
