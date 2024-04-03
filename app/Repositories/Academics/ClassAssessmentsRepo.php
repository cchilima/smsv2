<?php

namespace App\Repositories\Academics;

use App\Models\Academics\AcademicPeriod;
use App\Models\Academics\AcademicPeriodClass;
use App\Models\Academics\ClassAssessment;
use App\Models\Academics\Course;
use App\Models\Academics\CourseLevel;
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
                ->with('class_assessments.assessment_type', 'enrollments.student.user', 'enrollments.user.student', 'academicPeriod', 'instructor', 'course')
                ->first();
        } else {
            //            return AcademicPeriodClass::with([
            //                'class_assessments' => function ($query) use ($assess_id) {
            //                    $query->where('assessment_type_id', $assess_id);
            //                },
            //                'class_assessments.assessment_type',
            //                'enrollments.student.grades' => function ($query) use ($assess_id) {
            //                    $query->where('assessment_type_id', $assess_id);
            //                },
            //                'enrollments.student.user',
            //                'academicPeriod',
            //                'instructor',
            //                'course',
            //            ])->find($class_id);
            $ac = AcademicPeriodClass::find($class_id);
            return AcademicPeriodClass::with([
                'class_assessments' => function ($query) use ($assess_id) {
                    $query->where('assessment_type_id', $assess_id);
                },
                'class_assessments.assessment_type',
                'enrollments.student.grades' => function ($query) use ($ac, $assess_id) {
                    $query->where('assessment_type_id', $assess_id)
                        ->where('academic_period_id', $ac->academic_period_id);
                },
                'enrollments.student.user',
                'academicPeriod',
                'instructor',
                'course',
            ])->find($class_id);
            //   ->first();//->where('id', $class_id)

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

    public function publishAvailableProgramsCas($id)
    {
        $courseIdsWithGrades = Grade::where('academic_period_id', $id)->whereNot('assessment_type_id', 1)
            ->distinct('course_id')
            ->pluck('course_id');

        $programs = Program::whereIn('id', function ($query) use ($courseIdsWithGrades) {
            $query->select('program_id')
                ->from('program_courses')
                ->whereIn('course_id', $courseIdsWithGrades)
                ->distinct();
        })->with(['programCourses' => function ($query) use ($courseIdsWithGrades) {
            $query->whereIn('course_id', $courseIdsWithGrades);
        }])->get();

        $organizedData = [];

        foreach ($programs as $program) {
            $programData = [
                'program_code' => $program->code,
                'name' => $program->name,
                'id' => $program->id,
                'qualifications' => '',
                'students' => 0, // Initialize total students count for the program
                'status' => 0, // Default status is published (status 1)
                'levels' => [],
            ];

            foreach ($program->programCourses as $programCourse) {
                $course = $programCourse->course;

                $studentsCount = Grade::where('academic_period_id', $id)
                    ->where('course_id', $course->id)
                    ->distinct('student_id')
                    ->count();

                $programData['students'] += $studentsCount; // Add students count to total students for the program

                // Check if publication status is 0 for any student in the course
                $publicationStatus = Grade::where('academic_period_id', $id)
                    ->where('course_id', $course->id)
                    ->where('publication_status', 1)
                    ->exists();

                if ($publicationStatus) {
                    // If any student in the course has publication status 0, set program status to 0
                    $programData['status'] = 1;
                }
                $courseLevel = $programCourse->courseLevel;
                if (!isset($programData['levels'][$courseLevel->id])) {
                    $programData['levels'][$courseLevel->id] = [
                        'id' => $courseLevel->id,
                        'name' => $courseLevel->name,
                        'courses' => [],
                    ];
                }

                if ($studentsCount > 0) {
                    $courseLevel = $programCourse->courseLevel;
                    $courseData = [
                        'id' => $course->id,
                        'name' => $course->name,
                        'code' => $course->code,
                        'students' => $studentsCount,
                    ];

                    // Add course to the course level's data
                    //$programData['levels'][] = $courseData;
                }
            }

            $organizedData[] = $programData;
        }
        //dd($organizedData);
        return array_values($organizedData);
    }

    public function publishAvailablePrograms($id)
    {
        $courseIdsWithGrades = Grade::where('academic_period_id', $id)
            ->distinct('course_id')
            ->pluck('course_id');

        $programs = Program::whereIn('id', function ($query) use ($courseIdsWithGrades) {
            $query->select('program_id')
                ->from('program_courses')
                ->whereIn('course_id', $courseIdsWithGrades)
                ->distinct();
        })->with(['programCourses' => function ($query) use ($courseIdsWithGrades) {
            $query->whereIn('course_id', $courseIdsWithGrades);
        }])->get();

        $organizedData = [];

        foreach ($programs as $program) {
            $programData = [
                'program_code' => $program->code,
                'name' => $program->name,
                'id' => $program->id,
                'qualifications' => '',
                'students' => 0, // Initialize total students count for the program
                'status' => 0, // Default status is published (status 1)
                'levels' => [],
            ];

            foreach ($program->programCourses as $programCourse) {
                $course = $programCourse->course;

                $studentsCount = Grade::where('academic_period_id', $id)
                    ->where('course_id', $course->id)
                    ->distinct('student_id')
                    ->count();

                $programData['students'] += $studentsCount; // Add students count to total students for the program

                // Check if publication status is 0 for any student in the course
                $publicationStatus = Grade::where('academic_period_id', $id)
                    ->where('course_id', $course->id)
                    ->where('publication_status', 1)
                    ->exists();

                if ($publicationStatus) {
                    // If any student in the course has publication status 0, set program status to 0
                    $programData['status'] = 1;
                }
                $courseLevel = $programCourse->courseLevel;
                if (!isset($programData['levels'][$courseLevel->id])) {
                    $programData['levels'][$courseLevel->id] = [
                        'id' => $courseLevel->id,
                        'name' => $courseLevel->name,
                        'courses' => [],
                    ];
                }

                if ($studentsCount > 0) {
                    $courseLevel = $programCourse->courseLevel;
                    $courseData = [
                        'id' => $course->id,
                        'name' => $course->name,
                        'code' => $course->code,
                        'students' => $studentsCount,
                    ];

                    // Add course to the course level's data
                    //$programData['levels'][] = $courseData;
                }
            }

            $organizedData[] = $programData;
        }
        //dd($organizedData);
        return array_values($organizedData);
    }

    public function getCaGrades($level, $pid, $aid)
    {
        $data = Student::where('program_id', $pid)
            ->where('course_level_id', $level)
            ->whereHas('grades', function ($query) use ($aid) {
                $query->where('academic_period_id', $aid);
            })
            ->whereHas('enrollments.class', function ($query) use ($aid) {
                $query->where('academic_period_id', $aid);
            })
            // Exclude students with null or different academic period enrollments
            ->with([
                'enrollments.class' => function ($query) use ($aid) {
                    $query->where('academic_period_id', $aid);
                },
                'enrollments.class.course.grades' => function ($query) use ($aid) {
                    $query->where('academic_period_id', $aid)
                        ->where('publication_status', 0)
                        ->where('assessment_type_id', '!=', 1)
                        ->select(
                            'course_id',
                            'academic_period_id',
                            'course_code',
                            'course_title',
                            'student_id',
                            'total',
                            'assessment_type_id',

                        )
                        ->groupBy('assessment_type_id', 'total', 'course_code', 'course_title', 'course_id', 'academic_period_id', 'student_id');
                },
                'program', 'user', 'level', 'enrollments.class.course.grades.assessment_type.class_assessment'
            ])->paginate(3, ['*'], 'page', 4);

        $organizedData = [
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'per_page' => $data->perPage(),
            'students' => []
        ];

        foreach ($data as $student) {
            $studentId = $student->id;

            // Initialize student's data if not already present
            if (!isset($organizedData['students'][$studentId])) {
                $organizedData['students'][$studentId] = [
                    'id' => $studentId,
                    'course_level_id' => $level,
                    'program_id' => $student->program->id,
                    'program_name' => $student->program->name,
                    'ac' => $aid,
                    'name' => $student->user->first_name . ' ' . $student->user->middle_name . ' ' . $student->user->last_name,
                    'calculated_grade' => self::comments($studentId, $aid, 1),
                    'courses' => [],
                ];
            }

            foreach ($student->enrollments as $enrollment) {
                if (
                    $enrollment->class &&
                    $enrollment->class->academic_period_id !== null &&
                    $enrollment->class->academic_period_id == $aid
                ) {
                    $courseId = $enrollment->class->course->id;

                    // Initialize course's data if not already present
                    if (!isset($organizedData['students'][$studentId]['courses'][$courseId])) {
                        $organizedData['students'][$studentId]['courses'][$courseId] = [
                            'course_details' => [
                                'course_id' => $courseId,
                                'class_id' => $enrollment->class->id,
                                'academic_period_id' => $aid, // Assuming academic period is the same for all courses
                                'course_code' => $enrollment->class->course->code,
                                'course_title' => $enrollment->class->course->name,
                                'student_grades' => [],
                            ],
                        ];
                    }

                    foreach ($enrollment->class->course->grades as $grade) {
                        if ($grade->academic_period_id == $aid && $grade->student_id == $studentId) {

                            $studentGrades = [
                                //                            'exam' => $grade->exam,
                                'type' => $grade->assessment_type->name,
                                'total' => $grade->total,
                                'grade' => self::calculateGrade($grade->total),
                                'outof' => self::getClassAssessmentCastotal($courseId, $aid),
                                'id' => self::getGradeIDCAs($aid, $courseId, $studentId),
                            ];

                            $organizedData['students'][$studentId]['courses'][$courseId]['course_details']['student_grades'][] = $studentGrades;
                        }
                    }
                }
            }
        }


        // dd($organizedData);
        return $organizedData;
    }

    public function getCaGradesLoad($level, $pid, $aid, $current_page, $last_page, $per_page)
    {
        //        return Student::where('program_id', $pid)->where('course_level_id', $level)
        //            ->with(['enrollments.class' => function ($query) use ($aid) {
        //                $query->where('academic_period_id', $aid);
        //            }, 'enrollments.class.course.grades' => function ($query) use ($aid) {
        //                $query->whereNot('assessment_type_id', 1)->where('academic_period_id', $aid);
        //            }, 'program', 'user', 'level', 'enrollments.class.course.grades.assessment_type.class_assessment'])->paginate($per_page, ['*'], 'page', $current_page + 1);


        $data = Student::where('program_id', $pid)
            ->where('course_level_id', $level)
            ->whereHas('grades', function ($query) use ($aid) {
                $query->where('academic_period_id', $aid);
            })
            ->whereHas('enrollments.class', function ($query) use ($aid) {
                $query->where('academic_period_id', $aid);
            })
            // Exclude students with null or different academic period enrollments
            ->with([
                'enrollments.class' => function ($query) use ($aid) {
                    $query->where('academic_period_id', $aid);
                },
                'enrollments.class.course.grades' => function ($query) use ($aid) {
                    $query->where('academic_period_id', $aid)
                        ->where('publication_status', 0)
                        ->where('assessment_type_id', '!=', 1)
                        ->select(
                            'course_id',
                            'academic_period_id',
                            'course_code',
                            'course_title',
                            'student_id',
                            'total',
                            'assessment_type_id',

                        )
                        ->groupBy('assessment_type_id', 'total', 'course_code', 'course_title', 'course_id', 'academic_period_id', 'student_id');
                },
                'program', 'user', 'level', 'enrollments.class.course.grades.assessment_type.class_assessment'
            ])->paginate($per_page, ['*'], 'page', $current_page + 1);

        $organizedData = [
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'per_page' => $data->perPage(),
            'students' => []
        ];

        foreach ($data as $student) {
            $studentId = $student->id;

            // Initialize student's data if not already present
            if (!isset($organizedData['students'][$studentId])) {
                $organizedData['students'][$studentId] = [
                    'id' => $studentId,
                    'course_level_id' => $level,
                    'program_id' => $student->program->id,
                    'program_name' => $student->program->name,
                    'ac' => $aid,
                    'name' => $student->user->first_name . ' ' . $student->user->middle_name . ' ' . $student->user->last_name,
                    'calculated_grade' => self::comments($studentId, $aid, 1),
                    'courses' => [],
                ];
            }

            foreach ($student->enrollments as $enrollment) {
                if (
                    $enrollment->class &&
                    $enrollment->class->academic_period_id !== null &&
                    $enrollment->class->academic_period_id == $aid
                ) {
                    $courseId = $enrollment->class->course->id;

                    // Initialize course's data if not already present
                    if (!isset($organizedData['students'][$studentId]['courses'][$courseId])) {
                        $organizedData['students'][$studentId]['courses'][$courseId] = [
                            'course_details' => [
                                'course_id' => $courseId,
                                'class_id' => $enrollment->class->id,
                                'academic_period_id' => $aid, // Assuming academic period is the same for all courses
                                'course_code' => $enrollment->class->course->code,
                                'course_title' => $enrollment->class->course->name,
                                'student_grades' => [],
                            ],
                        ];
                    }

                    foreach ($enrollment->class->course->grades as $grade) {
                        if ($grade->academic_period_id == $aid && $grade->student_id == $studentId) {

                            $studentGrades = [
                                //                            'exam' => $grade->exam,
                                'type' => $grade->assessment_type->name,
                                'total' => $grade->total,
                                'grade' => self::calculateGrade($grade->total),
                                'outof' => self::getClassAssessmentCastotal($courseId, $aid),
                                'id' => self::getGradeIDCAs($aid, $courseId, $studentId),
                            ];

                            $organizedData['students'][$studentId]['courses'][$courseId]['course_details']['student_grades'][] = $studentGrades;
                        }
                    }
                }
            }
        }


        // dd($organizedData);
        return $organizedData;
    }

    public function getGrades($level, $pid, $aid)
    {
        /*$result = Student::where('program_id', $pid)->where('course_level_id', $level)
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
            ])->paginate(1, ['*'], 'page', 1);*/
        $studentsCount = Student::where('program_id', $pid)
            ->where('course_level_id', $level)->whereHas('enrollments.class', function ($query) use ($aid) {
                $query->where('academic_period_id', $aid);
            })->with([
                'enrollments.class' => function ($query) use ($aid) {
                    $query->where('academic_period_id', $aid);
                }
            ])->paginate(1, ['*'], 'page', 1);
        $result = Student::where('program_id', $pid)
            ->where('course_level_id', $level)
            ->whereHas('enrollments.class', function ($query) use ($aid) {
                $query->where('academic_period_id', $aid);
            })
            ->with([
                'enrollments.class' => function ($query) use ($aid) {
                    $query->where('academic_period_id', $aid);
                },
                'enrollments.class.course.grades' => function ($query) use ($aid) {
                    $query->select(
                        'course_id',
                        'academic_period_id',
                        'course_code',
                        'course_title',
                        'student_id',
                        DB::raw('SUM(CASE WHEN assessment_type_id = 1 THEN total ELSE 0 END) as exam'),
                        DB::raw('SUM(CASE WHEN assessment_type_id != 1 THEN total ELSE 0 END) as ca'),
                        DB::raw('SUM(total) as total_sum')
                    )
                        ->where('academic_period_id', $aid) // Ensure grades are for the specified academic period
                        ->groupBy('course_code', 'course_title', 'course_id', 'academic_period_id', 'student_id');
                },
                'program', 'user', 'level', 'enrollments.class.course.grades.assessment_type.class_assessment'
            ])->paginate(1, ['*'], 'page', 1);

        //start

        $result = Student::where('program_id', $pid)
            ->where('course_level_id', $level)
            ->whereHas('grades', function ($query) use ($aid) {
                $query->where('academic_period_id', $aid);
            })
            ->whereHas('enrollments.class', function ($query) use ($aid) {
                $query->where('academic_period_id', $aid);
            })
            // Exclude students with null or different academic period enrollments
            ->with([
                'enrollments.class' => function ($query) use ($aid) {
                    $query->where('academic_period_id', $aid);
                },
                'enrollments.class.course.grades' => function ($query) use ($aid) {
                    $query->where('academic_period_id', $aid)
                        ->where('publication_status', 0)
                        ->select(
                            'course_id',
                            'academic_period_id',
                            'course_code',
                            'course_title',
                            'student_id',
                            DB::raw('SUM(CASE WHEN assessment_type_id = 1 THEN total ELSE 0 END) as exam'),
                            DB::raw('SUM(CASE WHEN assessment_type_id != 1 THEN total ELSE 0 END) as ca'),
                            DB::raw('SUM(total) as total_sum')
                        )
                        ->groupBy('course_code', 'course_title', 'course_id', 'academic_period_id', 'student_id');
                },
                'program', 'user', 'level', 'enrollments.class.course.grades.assessment_type.class_assessment'
            ])->paginate(3, ['*'], 'page', 1);

        $organizedData = [
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'per_page' => $result->perPage(),
            'students' => []
        ];
        /*
                foreach ($result as $student) {
                    foreach ($student->enrollments as $enrollment) {
                        if (
                            $enrollment->class &&
                            $enrollment->class->academic_period_id !== null &&
                            $enrollment->class->academic_period_id == $aid
                        ) {
                            foreach ($enrollment->class->course->grades as $grade) {
                                if ($grade->academic_period_id == $aid){
                                $courseId = $grade->course_id;
                                $studentId = $grade->student_id;

                                // Initialize student's data if not already present
                                if (!isset($organizedData[$studentId])) {
                                    $organizedData[$studentId] = [
                                        'id' => $studentId,
                                        'name' => $student->user->first_name,
                                        'calculated_grade' =>  self::comments($studentId, $aid, 1),
                                        'courses' => [],
                                    ];
                                }

                                // Initialize course's data if not already present
                                if (!isset($organizedData[$studentId]['courses'][$courseId])) {
                                    $organizedData[$studentId]['courses'][$courseId] = [
                                        'course_details' => [
                                            'course_id' => $grade->course_id,
                                            'academic_period_id' => $grade->academic_period_id,
                                            'course_code' => $grade->course_code,
                                            'course_title' => $grade->course_title,
                                            'student_grades' => [],
                                        ],

                                    ];
                                }

                                // Add student's grade details to the course
                                $organizedData[$studentId]['courses'][$courseId]['course_details']['student_grades'][] = [
                                    'exam' => $grade->exam,
                                    'ca' => $grade->ca,
                                    'total_sum' => $grade->total_sum,
                                    'grade' => self::calculateGrade($grade->total_sum),
                                    'outof' => self::getClassAssessmentExams($grade->course_id, $aid),
                                    'id' => self::getGradeID($aid, $grade->course_id, $studentId),
                                ];
                            }
                            }
                        }
                    }
                }
        */
        foreach ($result as $student) {
            foreach ($student->enrollments as $enrollment) {
                if (
                    $enrollment->class &&
                    $enrollment->class->academic_period_id !== null &&
                    $enrollment->class->academic_period_id == $aid
                ) {
                    //dd($enrollment);
                    //   foreach ($enrollment->class->course as $course) {
                    // dd($course);
                    $courseId = $enrollment->class->course->id;
                    $studentId = $student->id;

                    // Initialize student's data if not already present
                    if (!isset($organizedData['students'][$studentId])) {
                        $organizedData['students'][$studentId] = [
                            'id' => $studentId,
                            'course_level_id' => $level,
                            'program_id' => $student->program->id,
                            'program_name' => $student->program->name,
                            'ac' => $aid,
                            'name' => $student->user->first_name . ' ' . $student->user->middle_name . ' ' . $student->user->last_name,
                            'calculated_grade' => self::comments($studentId, $aid, 1),
                            'courses' => [],
                        ];
                    }

                    // Initialize course's data if not already present
                    if (!isset($organizedData['students'][$studentId]['courses'][$courseId])) {
                        $organizedData['students'][$studentId]['courses'][$courseId] = [
                            'course_details' => [
                                'course_id' => $enrollment->class->course->id,
                                'class_id' => $enrollment->class->id,
                                'academic_period_id' => $aid, // Assuming academic period is the same for all courses
                                'course_code' => $enrollment->class->course->code,
                                'course_title' => $enrollment->class->course->name,
                                'student_grades' => [],
                            ],
                        ];
                    }

                    // Add student's grade details to the course
                    $studentGrades = [
                        'exam' => 'NE',
                        'ca' => 'NE',
                        'total_sum' => 'NE',
                        'grade' => self::calculateGrade(10), // Assuming default grade calculation
                        'outof' => 'NE', //self::getClassAssessmentExams($enrollment->class->course->id, $aid),
                        'id' => 'NE', //self::getGradeID($aid, $enrollment->class->course->id, $studentId),
                    ];

                    // If there are grades available for the course, update student grades
                    foreach ($enrollment->class->course->grades as $grade) {
                        if ($grade->academic_period_id == $aid && $grade->student_id == $studentId) {
                            $studentGrades = [
                                'exams' => $grade->exam,
                                'exam' => $grade->exam,
                                'ca' => $grade->ca,
                                'total_sum' => $grade->total_sum,
                                'grade' => self::calculateGrade($grade->total_sum),
                                'outof' => self::getClassAssessmentExams($enrollment->class->course->id, $aid),
                                'id' => self::getGradeID($aid, $enrollment->class->course->id, $studentId),
                            ];
                        }
                    }

                    $organizedData['students'][$studentId]['courses'][$courseId]['course_details']['student_grades'][] = $studentGrades;
                }
                // }
            }
        }


        //        $item['calculated_grade'] = $this->comments($item['id'], $aid, 1);
        //        $item->enrollments->transform(function ($enrollment) use ($item, $aid) {
        //            // dd($enrollment);
        //            $enrollment->class->course->grades->transform(function ($grade) use ($item, $aid) {
        //                // Perform calculations here based on the data retrieved
        //                $grade['grade'] = $this->calculateGrade($grade->total_sum);
        //                $grade['outof'] = $this->getClassAssessmentExams($grade->course_id, $aid);
        //                $grade['id'] = $this->getGradeID($aid, $grade->course_id, $item['id']);


        // $student = Student::with(['program', 'user', 'level', 'enrollments.class.course', 'enrollments.class.academicPeriod'])->find($student_id);
        //        $courses = [];
        //        foreach ($result as $student) {
        //            foreach ($student->enrollments as $enrollment) {
        //
        //                if (
        //                    isset($enrollment->class->academic_period_id) && // Check if academic_period_id is set
        //                    !empty($enrollment->class->academic_period_id) && // Check if academic_period_id is not empty
        //                    $enrollment->class->academic_period_id != null // Check if academic_period_id is null
        //                ){
        //                    $courses[] = $result;
        //                }
        //            }
        //        }
        //dd($result);
        //dd($organizedData);
        return $organizedData;
        //return $this->extracted($result, $aid);
    }

    public function getExamTotal($student_id, $api, $course_id)
    {
        $student = Grade::where('academic_period_id', $api)->where('student_id', $student_id)->where('course_id', $course_id)
            ->select(
                'course_id',
                'academic_period_id',
                'course_code',
                'course_title',
                'student_id',
                DB::raw('SUM(CASE WHEN assessment_type_id = 1 THEN total ELSE 0 END) as exam'),
                DB::raw('SUM(CASE WHEN assessment_type_id != 1 THEN total ELSE 0 END) as ca'),
                DB::raw('SUM(total) as total_sum')
            )
            ->groupBy('course_code', 'course_title', 'course_id', 'academic_period_id', 'student_id');

        return $student->exam;
    }

    public function getGradeID($aid, $course_id, $student)
    {
        $gradeID = Grade::where('assessment_type_id', 1)->where('academic_period_id', $aid)->where('course_id', $course_id)->where('student_id', $student)->first();
        //return $gradeID->id;
        return $gradeID ? $gradeID->id : '';
    }
    public function getGradeIDCAs($aid, $course_id, $student)
    {
        $gradeID = Grade::whereNot('assessment_type_id', 1)->where('academic_period_id', $aid)->where('course_id', $course_id)->where('student_id', $student)->first();
        //return $gradeID->id;
        return $gradeID ? $gradeID->id : '';
    }

    public function getGradesLoad($level, $pid, $aid, $current_page, $last_page, $per_page)
    {
        //start

        $result = Student::where('program_id', $pid)
            ->where('course_level_id', $level)
            ->whereHas('grades', function ($query) use ($aid) {
                $query->where('academic_period_id', $aid);
            })
            ->whereHas('enrollments.class', function ($query) use ($aid) {
                $query->where('academic_period_id', $aid);
            })
            // Exclude students with null or different academic period enrollments
            ->with([
                'enrollments.class' => function ($query) use ($aid) {
                    $query->where('academic_period_id', $aid);
                },
                'enrollments.class.course.grades' => function ($query) use ($aid) {
                    $query->where('academic_period_id', $aid)
                        ->where('publication_status', 0)
                        ->select(
                            'course_id',
                            'academic_period_id',
                            'course_code',
                            'course_title',
                            'student_id',
                            DB::raw('SUM(CASE WHEN assessment_type_id = 1 THEN total ELSE 0 END) as exam'),
                            DB::raw('SUM(CASE WHEN assessment_type_id != 1 THEN total ELSE 0 END) as ca'),
                            DB::raw('SUM(total) as total_sum')
                        )
                        ->groupBy('course_code', 'course_title', 'course_id', 'academic_period_id', 'student_id');
                },
                'program', 'user', 'level', 'enrollments.class.course.grades.assessment_type.class_assessment'
            ])->paginate($per_page, ['*'], 'page', $current_page + 1);

        $organizedData = [
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'per_page' => $result->perPage(),
            'students' => []
        ];


        foreach ($result as $student) {
            foreach ($student->enrollments as $enrollment) {
                if (
                    $enrollment->class &&
                    $enrollment->class->academic_period_id !== null &&
                    $enrollment->class->academic_period_id == $aid
                ) {

                    $courseId = $enrollment->class->course->id;
                    $studentId = $student->id;

                    // Initialize student's data if not already present
                    if (!isset($organizedData['students'][$studentId])) {
                        $organizedData['students'][$studentId] = [
                            'id' => $studentId,
                            'course_level_id' => $level,
                            'program_id' => $student->program->id,
                            'program_name' => $student->program->name,
                            'ac' => $aid,
                            'name' => $student->user->first_name . ' ' . $student->user->middle_name . ' ' . $student->user->last_name,
                            'calculated_grade' => self::comments($studentId, $aid, 1),
                            'courses' => [],
                        ];
                    }

                    // Initialize course's data if not already present
                    if (!isset($organizedData['students'][$studentId]['courses'][$courseId])) {
                        $organizedData['students'][$studentId]['courses'][$courseId] = [
                            'course_details' => [
                                'course_id' => $enrollment->class->course->id,
                                'class_id' => $enrollment->class->id,
                                'academic_period_id' => $aid, // Assuming academic period is the same for all courses
                                'course_code' => $enrollment->class->course->code,
                                'course_title' => $enrollment->class->course->name,
                                'student_grades' => [],
                            ],
                        ];
                    }

                    // Add student's grade details to the course
                    //                    $studentGrades = [
                    //                        'exam' => 'NE',
                    //                        'ca' => 'NE',
                    //                        'total_sum' => 'NE',
                    //                        'grade' => self::calculateGrade(0), // Assuming default grade calculation
                    //                        'outof' => 'NE',//self::getClassAssessmentExams($enrollment->class->course->id, $aid),
                    //                        'id' => 'NE',//self::getGradeID($aid, $enrollment->class->course->id, $studentId),
                    //                    ];

                    // If there are grades available for the course, update student grades
                    //                    foreach ($enrollment->class->course->grades as $grade) {
                    //                        if ($grade->academic_period_id == $aid && $grade->student_id == $studentId) {
                    //                            $studentGrades = [
                    //                                'exam' => $grade->exam,
                    //                                'ca' => $grade->ca,
                    //                                'total_sum' => $grade->total_sum,
                    //                                'grade' => self::calculateGrade($grade->total_sum),
                    //                                'outof' => self::getClassAssessmentExams($enrollment->class->course->id, $aid),
                    //                                'id' => self::getGradeID($aid, $enrollment->class->course->id, $studentId),
                    //                            ];
                    //                        }
                    //                    }
                    // Initialize 'student_grades' array if not already present
                    if (!isset($organizedData['students'][$studentId]['courses'][$courseId]['course_details']['student_grades'])) {
                        $organizedData['students'][$studentId]['courses'][$courseId]['course_details']['student_grades'] = [];
                    }

                    // Add student's grade details to the course
                    $studentGrades = [
                        'exam' => 'NE',
                        'ca' => 'NE',
                        'total_sum' => 'NE',
                        'grade' => self::calculateGrade(0), // Assuming default grade calculation
                        'outof' => 'NE', //self::getClassAssessmentExams($enrollment->class->course->id, $aid),
                        'id' => 'NE', //self::getGradeID($aid, $enrollment->class->course->id, $studentId),
                    ];

                    // If there are grades available for the course, update student grades
                    //print_r($enrollment->class->course->grades);
                    if (isset($enrollment->class->course->grades) && !empty($enrollment->class->course->grades)) {
                        foreach ($enrollment->class->course->grades as $grade) {
                            if ($grade->academic_period_id == $aid && $grade->student_id == $studentId) {
                                $studentGrades = [
                                    'exam' => $grade->exam,
                                    'ca' => $grade->ca,
                                    'total_sum' => $grade->total_sum,
                                    'grade' => self::calculateGrade($grade->total_sum),
                                    'outof' => self::getClassAssessmentExams($enrollment->class->course->id, $aid),
                                    'id' => self::getGradeID($aid, $enrollment->class->course->id, $studentId),
                                ];
                            }
                        }
                    }

                    $organizedData['students'][$studentId]['courses'][$courseId]['course_details']['student_grades'][] = $studentGrades;
                    //$organizedData['students'][$studentId]['courses'][$courseId]['course_details']['student_grades'][] = $studentGrades;
                }
            }
        }
        //dd($organizedData);
        return $organizedData;
        return $this->extracted($result, $aid);
        /*$result = Student::where('program_id', $pid)->where('course_level_id', $level)
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

        return $this->extracted($result, $aid);*/
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
            ->distinct('students.id') // Assuming 'id' is the primary key of the students table
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
        $class = AcademicPeriodClass::where('course_id', $course_id)->where('academic_period_id', $apid)->first();
        $class_assessments = ClassAssessment::where('academic_period_class_id', $class->id)->where('assessment_type_id', 1)->first();
        // dd($class);
        return $class_assessments->total;
        return $class->class_assessments[0]->total;
    }
    public function getClassAssessmentCastotal($course_id, $apid)
    {
        $class = AcademicPeriodClass::where('course_id', $course_id)->where('academic_period_id', $apid)->first();
        $class_assessments = ClassAssessment::where('academic_period_class_id', $class->id)->whereNot('assessment_type_id', 1)->first();
        // dd($class);
        return $class_assessments->total;
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
            ->with(['academicPeriods', 'student'])->select('id', 'course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->selectRaw('SUM(total) as total_sum')
            ->groupBy('id', 'academic_period_id', 'course_code', 'course_title', 'student_id', 'course_id')
            ->orderBy('academic_period_id')
            ->orderBy('course_code')
            ->get();
        $organizedResults = [];

        foreach ($grades as $grade) {

            $academicPeriodId = $grade->academic_period_id;
            //$academicPeriodId = 'courses';
            $course = [
                'grade_id' => $grade->id,
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
                    'academic_period_start_date' => $grade->academicPeriods->ac_start_date,
                    'academic_period_end_date' => $grade->academicPeriods->ac_end_date,
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
    public function comments($student_id, $academicPeriodID, $type)
    {
        // Fetching all courses associated with the student's enrollments
        $student = Student::with(['enrollments.class.course'])->find($student_id);
        $courses = [];

        foreach ($student->enrollments as $enrollment) {
            if ($academicPeriodID == $enrollment->class->academic_period_id) {
                $courses[$enrollment->class->course->code] = [
                    'course_code' => $enrollment->class->course->code,
                    'course_title' => $enrollment->class->course->name,
                    'total_score' => 0 // Initialize total_score to 0 for courses not found in grades
                ];
            }
        }

        // Fetching grades for the specified student and academic period
        $grades = Grade::where('student_id', $student_id)
            ->where('assessment_type_id', 1) // Assuming assessment_type_id 1 indicates a type that should not be counted
            ->where('academic_period_id', $academicPeriodID)
            ->with(['academicPeriods', 'student'])
            ->select('academic_period_id', 'course_code', 'course_title', 'student_id')
            ->selectRaw('SUM(total) as total_score')
            ->groupBy('academic_period_id', 'course_code', 'course_title', 'student_id')
            ->orderBy('academic_period_id')
            ->orderBy('course_code')
            ->get();

        foreach ($grades as $grade) {
            if (isset($courses[$grade->course_code])) {
                // Update total_score for courses found in grades
                $courses[$grade->course_code]['total_score'] = $grade->total_score;
                $courses[$grade->course_code]['course_code'] = $grade->course_code;
                $courses[$grade->course_code]['course_title'] = $grade->course_title;
            }
        }
        //dd($courses);

        // Count the courses
        $courseCount = count($courses);
        $passedCourse = 0;
        $failedCourse = 0;
        $coursesPassedArray = [];
        $courseFailedArray = [];

        // Determine pass/fail status and populate passed/failed courses array
        foreach ($courses as $course) {
            if ($course['total_score'] >= 40 || $course['total_score'] == -1) { // Adjust the pass threshold as necessary
                $passedCourse++;
                $coursesPassedArray[] = $course;
            } else {
                $failedCourse++;
                $courseFailedArray[] = $course;
            }
        }

        // Determine comment and status based on pass/fail counts
        $comment = '';
        $status = 'same';

        if ($courseCount == $failedCourse) {
            $comment = 'Part Time';
        } elseif ($passedCourse == $courseCount) {
            $comment = 'Clear Pass';
            $status = 'new';
        } elseif ($courseCount - 1 == $passedCourse || $courseCount - 2 == $passedCourse) {
            $coursesToRepeat = implode(", ", array_column($courseFailedArray, 'course_code'));
            $comment = 'Proceed, RPT ' . $coursesToRepeat;
            $status = 'new';
        } elseif ($courseCount - 3 >= $passedCourse) {
            $coursesToRepeat = implode(", ", array_column($courseFailedArray, 'course_code'));
            $comment = 'Part time ' . $coursesToRepeat;
            $status = 'same';
        }

        // Prepare and return the data
        return [
            'student_id' => $student_id,
            'comment' => $comment,
            'coursesPassed' => $coursesPassedArray,
            'coursesPassedCount' => $passedCourse,
            'coursesFailed' => $courseFailedArray,
            'coursesFailedCount' => $failedCourse,
            'status' => $status,
        ];

        /*
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
                */
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
                // dd($enrollment);
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

    public function updatetotaGrade($id, $newTotal)
    {
        Grade::find($id)->update([
            'total' => $newTotal,
        ]);
    }
}
