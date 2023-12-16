<?php

namespace App\Repositories\Academics;

use App\Models\Academics\Course;
use App\Models\Academics\Prerequisite;
use Illuminate\Support\Facades\DB;

class PrerequisiteRepository
{

    public function create($data)
    {
        return Prerequisite::create($data);
    }
    public function update($id, $data)
    {
        return Prerequisite::find($id)->update($data);
    }
    public function getAll()
    {
        return Course::with('prerequisites')->get();
    }
    public function getAllCoursesWithPrerequisites()
    {
        return Course::with('prerequisites')->get();
    }
    public function find($id)
    {
        $course = Course::find($id);
        return $course->prerequisites;
    }
    public function findOne($id)
    {
        return Course::find($id);
    }
    public function updateOrInsert($id,$courseID){
        Prerequisite::updateOrInsert(
            ['course_id' => $id],
            [
                'prerequisite_course_id' => $courseID,
                'course_id' => $id,
            ]
        );
    }
}
