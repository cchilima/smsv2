<?php

namespace App\Repositories\Academics;

use App\Models\Academics\CourseLevel;

class CourseLevelsRepository
{
    public function create($data)
    {
        return CourseLevel::create($data);
    }

    public function getAll($order = 'name')
    {
        return CourseLevel::orderBy($order,'asc')->get();
    }

    public function update($id, $data)
    {
        return CourseLevel::find($id)->update($data);
    }

    public function find($id)
    {
        return CourseLevel::find($id);
    }
}
