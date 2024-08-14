<?php

namespace App\Repositories\Academics;

use App\Models\Academics\CourseLevel;
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
        return Program::with('department', 'qualification')->orderBy($order)->get();
    }
    public function update($id, $data)
    {
        return Program::find($id)->update($data);
    }
    public function find($id)
    {
        return Program::find($id)->with('department', 'qualification', 'courses')->get();
    }
    public function findOne($id)
    {
        return Program::find($id);
    }
    public function findOneP($id)
    {

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

    public function getAllWithCourse($id)
    {
        $programsData = Program::where('department_id', $id)->with(['department', 'qualification', 'programCourses.courseLevel', 'programCourses.course'])->get();

        $organizedData = [];

        foreach ($programsData as $programData) {
            $organizedProgramData = [
                'program_code' => $programData->code,
                'program_name' => $programData->name,
                'program_id' => $programData->id,
                'course_levels' => [],
            ];

            foreach ($programData->programCourses as $programCourse) {
                $courseLevel = $programCourse->courseLevel;

                // Add course level if not present in the program's data
                if (!isset($organizedProgramData['course_levels'][$courseLevel->id])) {
                    $organizedProgramData['course_levels'][$courseLevel->id] = [
                        'id' => $courseLevel->id,
                        'name' => $courseLevel->name,
                        'courses' => [],
                    ];
                }

                // Add course to the course level's data
                $course = $programCourse->course;
                $organizedProgramData['course_levels'][$courseLevel->id]['courses'][] = [
                    'id' => $course->id,
                    'name' => $course->name,
                    'code' => $course->code,
                ];
            }

            // Store the organized data for this program
            $organizedData[] = $organizedProgramData;
        }

        return $organizedData;
    }

    public function getCoursesByProgram(string $programId, bool $executeQuery = true)
    {
        $query = ProgramCourses::with('courseLevel', 'course')
            ->where('program_id', $programId);

        return $executeQuery ? $query->get() : $query;
    }

    public function getCourseLevelsByProgram(string $programId, bool $executeQuery = true)
    {
        $query = CourseLevel::with('program')
            ->whereHas('program', function ($query) use ($programId) {
                $query->where('program_id', $programId);
            })
            ->orderBy('name');

        return $executeQuery ? $query->get() : $query;
    }
}
