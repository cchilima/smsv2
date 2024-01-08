<?php

namespace App\Repositories\Academics;

use App\Models\Academics\Program;
use App\Models\Academics\ProgramCourses;
use Illuminate\Support\Facades\DB;

class ProgramsRepository
{
    public function create($data)
    {
        return Program::create($data);
    }

    public function getAll($order = 'name')
    {
        return Program::with('department','qualification')->orderBy($order)->get();
    }
    public function update($id, $data)
    {
        return Program::find($id)->update($data);
    }
    public function find($id)
    {
        return Program::find($id)->with('department','qualification','courses')->get();
    }
    public function findOne($id)
    {
        return Program::find($id);
    }
    public function findOneP($id){

        $programData = Program::with(['programCourses.courseLevel', 'programCourses.course'])
            ->find($id);

// Organize the data into the desired format
        $organizedData = [];

        $organizedData = [
            'program_code' => $programData->code,
            'program_name' => $programData->name,
            'program_id' => $id,
            'course_levels' => [],
        ];

        foreach ($programData->programCourses as $programCourse) {
            $courseLevel = $programCourse->courseLevel;

            // Add course level if not present in the program's data
            if (!isset($organizedData['course_levels'][$courseLevel->id])) {
                $organizedData['course_levels'][$courseLevel->id] = [
                    'id' => $courseLevel->id,
                    'name' => $courseLevel->name,
                    'courses' => [],
                ];
            }

            // Add course to the course level's data
            $course = $programCourse->course;
            $organizedData['course_levels'][$courseLevel->id]['courses'][] = [
                'id' => $course->id,
                'name' => $course->name,
                'code' => $course->code,
            ];
        }

        return $organizedData;
    }

}
