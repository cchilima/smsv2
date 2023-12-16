<?php

namespace App\Repositories\Academics;

use App\Models\Academics\studyMode;

class StudyModeRepository
{
    public function create($data)
    {
        return studyMode::create($data);
    }
    public function getAll($order = 'name')
    {
        return studyMode::orderBy($order)->get();
    }
    public function getStudyMode($data)
    {
        return studyMode::where($data)->get();
    }
    public function update($id, $data)
    {
        return studyMode::find($id)->update($data);
    }
    public function find($id)
    {
        return studyMode::find($id);
    }
}
