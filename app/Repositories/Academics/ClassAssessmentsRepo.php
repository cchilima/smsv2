<?php

namespace App\Repositories\Academics;

use App\Models\Academics\AcademicPeriod;
use App\Models\Academics\AcademicPeriodClass;
use App\Models\Academics\ClassAssessment;
use App\Models\Academics\Course;
use App\Models\Academics\Grade;
use App\Models\Academics\Program;
use App\Models\Academics\ProgramCourses;
use App\Models\Admissions\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassAssessmentsRepo
{
    public function create($data)
    {
        return ClassAssessment::create($data);
    }

    public function getAll()
    {
        return ClassAssessment::with('classes', 'assessments')->get();
    }

    public function getPeriodType($data)
    {
        return ClassAssessment::where($data)->get();
    }

    public function update($id, $data)
    {
        return ClassAssessment::find($id)->update($data);
    }

    public function find($id)
    {
        return ClassAssessment::with('classes', 'assessments')->find($id);
    }

    public function getClassAssessments($class_id, $assess_id)
    {
        $user = Auth::user();

        if ($user->userType->title == 'instructor') {
            // If the authenticated user is an instructor, only get AcademicPeriods with related classes where the user is the instructor
//            return AcademicPeriodClass::where('instructor_id', $user->id)->with('class_assessments.assessment_type', 'instructor', 'course')
//                ->find($class_id);
            return AcademicPeriodClass::where('id', $class_id)
                ->whereHas('class_assessments', function ($query) use ($assess_id) {
                    $query->where('assessment_type_id', $assess_id);
                })
                ->with('class_assessments.assessment_type', 'enrollments.student.user','enrollments.user.student', 'academicPeriod', 'instructor', 'course')
                ->first();
        } else {
            return AcademicPeriodClass::with([
                'class_assessments' => function ($query) use ($assess_id) {
                    $query->where('assessment_type_id', $assess_id);
                },
                'class_assessments.assessment_type',
                'enrollments.student.grades' => function ($query) use ($assess_id) {
                    $query->where('assessment_type_id', $assess_id);
                },
                'enrollments.student.user',
                'academicPeriod',
                'instructor',
                'course',
            ])
                ->where('id', $class_id)
                ->first();

            //return AcademicPeriodClass::with('class_assessments.assessment_type','enrollments.user.student', 'academicPeriod', 'instructor', 'course')->find($id);
        }
    }

    public static function getAllReadyPublish($order = 'created_at')
    {
        $user = Auth::user();
        if ($user->userType->title == 'instructor') {
            return AcademicPeriod::whereHas('classes', function ($query) use ($user) {
                $query->where('instructor_id', $user->id);
            })->whereHas('grades')->get();

        } else {
            //$data = now();
            return AcademicPeriod::whereHas('grades')
                ->where('ac_end_date', '>=', now())
                ->get();

        }
    }

    public function publishAvailablePrograms($id)
    {
        $result = Program::join('program_courses', 'programs.id', '=', 'program_courses.program_id')
            ->join('course_levels', 'course_levels.id', '=', 'program_courses.course_level_id')
            ->join('grades', 'grades.course_id', '=', 'program_courses.course_id')
            ->join('qualifications', 'programs.qualification_id', '=', 'qualifications.id')
            ->groupBy('programs.name', 'programs.code', 'programs.id', 'grades.student_id', 'grades.publication_status', 'qualification', 'course_levels.name', 'course_levels.id')
            ->select(
                'programs.name as program_name',
                'programs.code as program_code',
                'programs.id as program_id',
                'course_levels.name as course_level_name',
                'course_levels.id as course_level_id',
                'qualifications.name as qualification',
                'grades.publication_status as status',
                DB::raw('COUNT(DISTINCT grades.student_id) as students')
            )
            ->where('grades.academic_period_id', $id)
            ->get();

        $programsData = [];
//        foreach ($result as $item) {
//            $programId = $item->program_id;
//            if (!isset($programsData[$programId])) {
//                $programsData[$programId] = [
//                    'name' => $item->program_name,
//                    'code' => $item->program_code,
//                    'id' => $item->program_id,
//                    'qualifications' => $item->qualification,
//                    'status' => $item->status,
//                    'students' => $item->students,
//                    'levels' => [],
//                ];
//            }
//            $programsData[$programId]['levels'][] = [
//                'name' => $item->course_level_name,
//                'id' => $item->course_level_id,
//            ];
//        }
        foreach ($result as $item) {
            $programId = $item->program_id;

            if (!isset($programsData[$programId])) {
                $programsData[$programId] = [
                    'name' => $item->program_name,
                    'code' => $item->program_code,
                    'id' => $item->program_id,
                    'qualifications' => $item->qualification,
                    'status' => $item->status,
                    'students' => $item->students,
                    'levels' => [],
                ];
            }

            $levelId = $item->course_level_id;
            $levelName = $item->course_level_name;

            // Check if the level with the same id already exists for the program
            if (!isset($programsData[$programId]['levels'][$levelId])) {
                $programsData[$programId]['levels'][$levelId] = [
                    'name' => $levelName,
                    'id' => $levelId,
                ];
            }
        }
        // dd($programsData);
        return array_values($programsData);
    }

    public function getCaGrades($level, $pid, $aid)
    {
        return Student::where('program_id', $pid)->where('course_level_id', $level)
            ->with(['enrollments.class' => function ($query) use ($aid) {
                $query->where('academic_period_id', $aid);
            }, 'enrollments.class.course.grades' => function ($query) use ($aid) {
                $query->whereNot('assessment_type_id', 1)->where('academic_period_id', $aid);
            }, 'program', 'user', 'level', 'enrollments.class.course.grades.assessment_type.class_assessment'])->paginate(1, ['*'], 'page', 1);

    }

    public function getCaGradesLoad($level, $pid, $aid, $current_page, $last_page, $per_page)
    {
        return Student::where('program_id', $pid)->where('course_level_id', $level)
            ->with(['enrollments.class' => function ($query) use ($aid) {
                $query->where('academic_period_id', $aid);
            }, 'enrollments.class.course.grades' => function ($query) use ($aid) {
                $query->whereNot('assessment_type_id', 1)->where('academic_period_id', $aid);
            }, 'program', 'user', 'level', 'enrollments.class.course.grades.assessment_type.class_assessment'])->paginate($per_page, ['*'], 'page', $current_page + 1);

    }

    public function getGrades($level, $pid, $aid)
    {
        $result = Student::where('program_id', $pid)->where('course_level_id', $level)
            ->with([
                'enrollments.class' => function ($query) use ($aid) {
                    $query->where('academic_period_id', $aid);
                },
                'enrollments.class.course.grades' => function ($query) use ($aid) {
                    $query->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id',
                        DB::raw('SUM(CASE WHEN assessment_type_id = 1 THEN total ELSE 00 END) as exam'),
                        DB::raw('SUM(CASE WHEN assessment_type_id != 1 THEN total ELSE 00 END) as ca'),
                        DB::raw('SUM(total) as total_sum')
                    )
                        ->where('academic_period_id', $aid)
                        ->groupBy('course_code', 'course_title', 'course_id', 'academic_period_id', 'student_id');
                },
                'program', 'user', 'level', 'enrollments.class.course.grades.assessment_type.class_assessment'
            ])->paginate(1, ['*'], 'page', 1);
        return $this->extracted($result, $aid);
    }

    public function getGradeID($aid, $course_id, $student)
    {
        $gradeID = Grade::where('assessment_type_id', 1)->where('academic_period_id', $aid)->where('course_id', $course_id)->where('student_id', $student)->first();
        //return $gradeID->id;
        return $gradeID ? $gradeID->id : '';
    }

    public function getGradesLoad($level, $pid, $aid, $current_page, $last_page, $per_page)
    {
        $result = Student::where('program_id', $pid)->where('course_level_id', $level)
            ->with([
                'enrollments.class' => function ($query) use ($aid) {
                    $query->where('academic_period_id', $aid);
                },
                'enrollments.class.course.grades' => function ($query) use ($aid) {
                    $query->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id',
                        DB::raw('SUM(CASE WHEN assessment_type_id = 1 THEN total ELSE 00 END) as exam'),
                        DB::raw('SUM(CASE WHEN assessment_type_id != 1 THEN total ELSE 00 END) as ca'),
                        DB::raw('SUM(total) as total_sum')
                    )
                        ->where('academic_period_id', $aid)
                        ->groupBy('course_code', 'course_title', 'course_id', 'academic_period_id', 'student_id');
                },
                'program', 'user', 'level', 'enrollments.class.course.grades.assessment_type.class_assessment'
            ])->paginate($per_page, ['*'], 'page', $current_page+1);

        return $this->extracted($result, $aid);
    }

    public function total_students($level, $pid, $aid)
    {
        return Student::where('program_id', $pid)
            ->where('course_level_id', $level)
            ->with([
                'grades' => function ($query) use ($aid) {
                    $query->whereNot('assessment_type_id', 1)->where('academic_period_id', $aid);
                },
                'program',
                'user',
                'level',
                'grades.assessment_type'
            ])
            ->count();
    }

    public function getGrade($id)
    {
        $grade = Grade::find($id);
        return $grade->total;
    }

    public function getGradeAll($assessmentID, $code, $academicPeriodID, $studentID)
    {
        return Grade::where([
            'assessment_type_id' => $assessmentID,
            'course_code' => $code,
            'academic_period_id' => $academicPeriodID,
            'student_id' => $studentID
        ])->value('total');
    }

    public function updateGrade($id, $total)
    {
        return Grade::find($id)->update($total);
    }

    public function getClassAssessmentCas($id, $exam)
    {
        //dd($exam);
        if ($exam == 1) {
            return AcademicPeriodClass::with(['class_assessments' => function ($query) {
                $query->where('assessment_type_id', 1);
            }, 'course', 'class_assessments.assessment_type'])->find($id);

        } else {
            ///return AcademicPeriodClass::with('class_assessments.assessment_type', 'course')->find($id);
            //return AcademicPeriodClass::with('class_assessments.assessment_type', 'course')->find($id);
            return AcademicPeriodClass::with(['class_assessments' => function ($query) {
                $query->whereNot('assessment_type_id', 1);
            }, 'course', 'class_assessments.assessment_type'])->find($id);
        }

    }

    public function getClassAssessmentExams($course_id, $apid)
    {
        $class = AcademicPeriodClass::where('course_id', $course_id)->where('academic_period_id', $apid)->with(['class_assessments'
        => function ($query) use ($apid) {
                $query->where('assessment_type_id', 1);
            }, 'course'])->first();
       // dd($course_id);
        return $class->class_assessments[0]->total;
    }

    public function getStudentId($code, $assessment_type_id, $academicPeriodID)
    {
        $studentTotals = Grade::where([
            'course_code' => $code,
            'assessment_type_id' => $assessment_type_id,
            'academic_period_id' => $academicPeriodID
        ])->select('student_id')->distinct()->get();

        return $studentTotals->pluck('student_id')->toArray();
    }

    public function UpdateGradeAll($assessmentID, $code, $academicPeriodID, $studentID, $newTotal)
    {
        Grade::where([
            'assessment_type_id' => $assessmentID,
            'course_code' => $code,
            'academic_period_id' => $academicPeriodID,
            'student_id' => $studentID
        ])->update([
            'total' => $newTotal,
        ]);
    }

    public function publishGrades($id, $apid, $type)
    {
        if ($type == 1) {
            Grade::whereIn('student_id', $id)->where('academic_period_id', $apid)->where('assessment_type_id', 1)
                ->update([
                    'publication_status' => 1,
                ]);
        } else {
            Grade::whereIn('student_id', $id)->where('academic_period_id', $apid)->whereNot('assessment_type_id', 1)
                ->update([
                    'publication_status' => 1,
                ]);
        }
    }

    public function getStudentDetails($id)
    {
        return Student::where('user_id', $id)->with('user', 'program')->first();
    }

    public function GetCaStudentGrades($id)
    {
        $student_id = Student::where('user_id', $id)->first();
        $student = $student_id->id;

        $grades = Grade::where('student_id', $student)->whereNot('assessment_type_id', 1)
            ->with(['academicPeriods', 'student'])->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->selectRaw('SUM(total) as total_sum')
            ->groupBy('academic_period_id', 'course_code', 'course_title', 'student_id', 'course_id')
            ->orderBy('academic_period_id')
            ->orderBy('course_code')
            ->get();
        $organizedResults = [];

        foreach ($grades as $grade) {

            $academicPeriodId = $grade->academic_period_id;
            //$academicPeriodId = 'courses';
            $course = [
                'course_code' => $grade->course_code,
                'course_title' => $grade->course_title,
                'student_id' => $grade->student_id,
                'total' => $grade->total_sum,
                'outof' => $this->getoutOfTotal($grade->course_id, $grade->academic_period_id)
            ];

            if (!isset($organizedResults[$academicPeriodId])) {
                $organizedResults[$academicPeriodId] = [
                    'academic_period_name' => $grade->academicPeriods->name,
                    'academic_period_code' => $grade->academicPeriods->code,
                    'academic_period_id' => $grade->academicPeriods->id,
                    'comments' => $this->comments($grade->student_id, $grade->academicPeriods->id, 0),
                ];
            }

            $organizedResults[$academicPeriodId]['grades'][] = $course;
        }

        return $organizedResults;
    }

    public function GetExamGrades($id)
    {
        $student_id = Student::where('user_id', $id)->first();
        $student = $student_id->id;

        $grades = Grade::where('student_id', $student)
            ->with(['academicPeriods', 'student'])->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->selectRaw('SUM(total) as total_sum')
            ->groupBy('academic_period_id', 'course_code', 'course_title', 'student_id', 'course_id')
            ->orderBy('academic_period_id')
            ->orderBy('course_code')
            ->get();
        $organizedResults = [];

        foreach ($grades as $grade) {

            $academicPeriodId = $grade->academic_period_id;
            //$academicPeriodId = 'courses';
            $course = [
                'course_code' => $grade->course_code,
                'course_title' => $grade->course_title,
                'student_id' => $grade->student_id,
                'total' => $grade->total_sum,
                'grade' => $this->calculateGrade($grade->total_sum)
            ];

            if (!isset($organizedResults[$academicPeriodId])) {
                $organizedResults[$academicPeriodId] = [
                    'academic_period_name' => $grade->academicPeriods->name,
                    'academic_period_code' => $grade->academicPeriods->code,
                    'academic_period_id' => $grade->academicPeriods->id,
                    'comments' => $this->comments($grade->student_id, $grade->academicPeriods->id, 1),
                ];
            }

            $organizedResults[$academicPeriodId]['grades'][] = $course;
        }

        return $organizedResults;
    }

    public function getoutOfTotal($course_id, $apid)
    {
        $class_id = AcademicPeriodClass::where('academic_period_id', $apid)->where('course_id', $course_id)->first();
        if ($class_id) {
            $grades = ClassAssessment::where('academic_period_class_id', $class_id->id)->whereNot('assessment_type_id', 1)
                ->select('academic_period_class_id')
                ->selectRaw('SUM(total) as total_sum')
                ->groupBy('academic_period_class_id')
                ->orderBy('academic_period_class_id')
                ->first();
            return $grades->total_sum;
        } else {
            return 0;
        }
    }


    //for exams
    public function comments($student, $academicPeriodID, $type)
    {

        // check all couses failed if they have passed in the current academic period.
        if ($type == 1) {
            $courses = Grade::where('student_id', $student)->where('academic_period_id', $academicPeriodID)
                ->with(['academicPeriods', 'student'])->select('academic_period_id', 'course_code', 'course_title', 'student_id')
                ->selectRaw('SUM(total) as total_score')
                ->groupBy('academic_period_id', 'course_code', 'course_title', 'student_id')
                ->orderBy('academic_period_id')
                ->orderBy('course_code')
                ->get();

        } else {
            $courses = Grade::where('student_id', $student)->whereNot('assessment_type_id', 1)->where('academic_period_id', $academicPeriodID)
                ->with(['academicPeriods', 'student'])->select('academic_period_id', 'course_code', 'course_title', 'student_id')
                ->selectRaw('SUM(total) as total_score')
                ->groupBy('academic_period_id', 'course_code', 'course_title', 'student_id')
                ->orderBy('academic_period_id')
                ->orderBy('course_code')
                ->get();

        }

        $courseCount = count($courses);
        //$courses = (object)$courses;

        # Filter passed and failed courses
        $passedCourse = 0;
        $failedCourse = 0;

        //  dd($courses);

        /*----*/
        foreach ($courses as $course) {
            //dd($course->total_score);
            //    if ($course['gradeType'] == 1) {

            if ($course->total_score >= 50 || $course->total_score == -1) { # -1 is for exeptions

                $coursePassed = $course->course_code;
                $passedCourse = $passedCourse + 1;

                $coursesPassed[] = $coursePassed;
                $coursesPassedArray[] = $course;

                $passedCourses[] = $passedCourse;
            } else {

                // Check if course was taken before and has been cleared now

                $courseFailed = $course->course_code;
                $courseFailedArray[] = $course;
                $failedCourse = $failedCourse + 1;
                $coursesFailed[] = $courseFailed;
            }
            // will be a grading for CBU based on the program
//            } else {
//
//                if ($course['total_score'] >= 40 || $course['total_score'] == -1) { # -1 is for exeptions
//
//                    $coursePassed = $course['course_code'];
//                    $passedCourse = $passedCourse + 1;
//
//                    $coursesPassed[]      = $coursePassed;
//                    $coursesPassedArray[] = $course;
//
//                    $passedCourses[]      = $passedCourse;
//                } else {
//
//                    $courseFailed                   = $course['course_code'];
//                    $courseFailedArray[]            = $course;
//                    $failedCourse                   = $failedCourse + 1;
//                    $coursesFailed[]                = $courseFailed;
//                }
//            }
        }

        if ($courseCount == $failedCourse) {

            foreach ($courses as $course) {
                if ($course['total_score'] == 0) {
                    $comment = '';
                } else {
                    $comment = 'Part Time';
                    //$comment = 'Repeat year';
                }
            }
        }
        $status = '';
        if ($passedCourse == $courseCount) {
            $comment = "Clear Pass";
            $status = 'new';
        }
        if ($courseCount - 1 == $passedCourse) {
            $coursesToRepeat = implode(", ", $coursesFailed);
            $comment = 'Proceed, RPT ' . $coursesToRepeat;
            $status = 'new';
        }
        if ($courseCount - 2 == $passedCourse) {
            $coursesToRepeat = implode(", ", $coursesFailed);
            $comment = 'Proceed, RPT ' . $coursesToRepeat;
            $status = 'new';
        }

        if ($courseCount - 3 == $passedCourse) {
            $coursesToRepeat = implode(", ", $coursesFailed);
            $comment = 'Part time ' . $coursesToRepeat;
            $status = 'same';
        }

        if ($courseCount - 4 == $passedCourse) {
            $coursesToRepeat = implode(", ", $coursesFailed);
            //$coursesToRepeat = array_merge($data['coursesFailed'],$data['coursesPassed']); //with comment Repeat year
            // $comment = 'Repeat Year ';
            $comment = 'Part time ' . $coursesToRepeat;
            $status = 'same';
        }


        if (empty($comment)) {
            $comment = '';
            $status = 'same';
        }


        if (empty($courseFailedArray)) {
            $courseFailedArray = [];
        }
        if (empty($coursesPassedArray)) {
            $coursesPassedArray = [];
        }

        return $data = [
            'student_id' => $student,
            'comment' => $comment,
            'coursesPassed' => $coursesPassedArray,
            'coursesPassedCount' => $passedCourse,
            'coursesFailed' => $courseFailedArray,
            'coursesFailedCount' => $failedCourse,
            'status' => $status,
        ];
    }

    public
    static function calculateGrade($total)
    {
        // Define your grade thresholds and corresponding values here
        if ($total == 0) {
            return 'Not Examined';
        } else if ($total == -1) {
            return 'Exempted';
        } else if ($total == -2) {
            return 'Withdrew with Permission';
        } else if ($total == -3) {
            return 'Disqualified';
        } else if ($total == 0) {
            return 'NE';
        } else if ($total >= 1 && $total <= 39) {
            return 'D';
        } else if ($total >= 40 && $total <= 49) {
            return 'D+';
        } else if ($total >= 50 && $total <= 55) {
            return 'C';
        } else if ($total >= 56 && $total <= 61) {
            return 'C+';
        } else if ($total >= 62 && $total <= 67) {
            return 'B';
        } else if ($total >= 68 && $total <= 75) {
            return 'B+';
        } else if ($total >= 76 && $total <= 85) {
            return 'A';
        } else if ($total >= 86 && $total <= 100) {
            return 'A+';
        }
    }

    /**
     * @param $result
     * @param $aid
     * @return mixed
     */
    public function extracted($result, $aid): mixed
    {
        $result->getCollection()->transform(function ($item) use ($aid) {
            $item['calculated_grade'] = $this->comments($item['id'], $aid, 1);
            $item->enrollments->transform(function ($enrollment) use ($item, $aid) {
                $enrollment->class->course->grades->transform(function ($grade) use ($item, $aid) {
                    // Perform calculations here based on the data retrieved
                    $grade['grade'] = $this->calculateGrade($grade->total_sum);
                    $grade['outof'] = $this->getClassAssessmentExams($grade->course_id, $aid);
                    $grade['id'] = $this->getGradeID($aid, $grade->course_id, $item['id']);
                    return $grade;
                });
                return $enrollment;
            });

            return $item;
        });

        //dd($result);
        return $result;
    }
    public function updatetotaGrade($id,$newTotal){
        Grade::find($id)->update([
            'total' => $newTotal,
        ]);
    }

}
