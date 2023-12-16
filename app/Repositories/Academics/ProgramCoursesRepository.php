<?php

namespace App\Repositories\Academics;

use App\Models\Academics\ProgramCourses;

class ProgramCoursesRepository
{
    public function create($data)
    {
        return ProgramCourses::create($data);
    }

    public function getAll($order = 'name')
    {
        return ProgramCourses::with('department','qualification')->orderBy($order)->get();
    }

    public function getPeriodType($data)
    {
        return ProgramCourses::where($data)->get();
    }

    public function update($id, $data)
    {
        return ProgramCourses::find($id)->update($data);
    }

    public function find($id)
    {
        return ProgramCourses::with('courses','programs','levels')->find($id);
    }
    public function findProgramCourses($id)
    {
        return ProgramCourses::where('programID',$id)->with('courses','levels')->orderBy('level_id')->get();
    }
    public function findProgramlevels($id)
    {
        return ProgramCourses::where('programID',$id)->with('levels')->orderBy('level_id')->get();
    }
    public function findProgramlevelCourses($id,$level)
    {
        return ProgramCourses::where('programID',$id)->where('id',$level)->with('courses')->get();
    }
    public function findProgramlevelCoursesDelete($program,$level,$course)
    {
        return ProgramCourses::where('level_id',$level)->where('courseID',$course)->where('programID',$program);
    }
}
