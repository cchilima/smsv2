<?php

namespace App\Repositories\Academics;

use App\Models\Academics\Course;

class CourseRepository
{
    public function create($data)
    {
        return Course::create($data);
    }

    public function getAll($order = 'code')
    {
        return Course::orderBy($order,'asc')->get();
    }
    public function getPeriodType($data)
    {
        return Course::where($data)->get();
    }

    public function update($id, $data)
    {
        return Course::find($id)->update($data);
    }

    public function find($id)
    {
        return Course::find($id);
    }
}
