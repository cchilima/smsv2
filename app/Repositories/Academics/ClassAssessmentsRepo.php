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
use App\Models\Enrollments\Enrollment;
use Illuminate\Database\Eloquent\Builder;
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
                    $query->where('course_id', $ac->course_id)
                        ->where('assessment_type_id', $assess_id)
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

    public function getClassAssessmentsDatatableQuery($class_id, $assess_id): Builder
    {
        $user = Auth::user();

        if ($user->userType->title == 'instructor') {
            return Enrollment::with([
                'class.class_assessments.assessment_type',
                'student',
                'user',
                'class.academicPeriod',
                'class.instructor',
                'class.course',
            ])->whereHas('class.class_assessments', function ($query) use ($assess_id) {
                $query->where('assessment_type_id', $assess_id);
            });
        } else {

            $ac = AcademicPeriodClass::find($class_id);

            return Enrollment::with([
                'class.academicPeriod',
                'class.class_assessments.assessment_type',
                'class.instructor',
                'class.course',
                'user',

                'student.grades' => function ($query) use ($ac, $assess_id) {
                    $query->where('course_id', $ac->course_id)
                        ->where('assessment_type_id', $assess_id)
                        ->where('academic_period_id', $ac->academic_period_id);
                },

                'class.class_assessments' => function ($query) use ($assess_id) {
                    $query->where('assessment_type_id', $assess_id);
                },
            ])->where('academic_period_class_id', $class_id);
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

        $studentIds = Grade::where('academic_period_id', $id)->whereNot('assessment_type_id', 1)
            ->distinct('student_id')
            ->pluck('student_id');

        $programs = Program::with('qualification')->whereIn('id', function ($query) use ($courseIdsWithGrades) {
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
                'qualifications' => $program->qualification->name,
                'students' => 0, // Initialize total students count for the program
                'status' => 1, // Default status is published (status 1)
                'levels' => [],
            ];
            $studentsCount = Student::whereIn('id', $studentIds)->where('program_id', $program->id)->distinct('id')->count();

            $programData['students'] += $studentsCount;
            foreach ($program->programCourses as $programCourse) {
                $course = $programCourse->course;
                // Add students count to total students for the program

                // Check if publication status is 0 for any student in the course
                $publicationStatus = Grade::where('academic_period_id', $id)->whereNot('assessment_type_id', 1)
                    ->where('course_id', $course->id)
                    ->where('publication_status', 0)
                    ->exists();

                if ($publicationStatus) {
                    // If any student in the course has publication status 0, set program status to 1
                    $programData['status'] = 0;
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

            // Only add the program to the array if it has students
            if ($programData['students'] > 0) {
                $organizedData[] = $programData;
            }
        }

        return array_values($organizedData);
    }

    public function publishAvailablePrograms($id)
    {
        $courseIdsWithGrades = Grade::where('academic_period_id', $id)
            ->distinct('course_id')
            ->pluck('course_id');
        $studentIds = Grade::where('academic_period_id', $id)->where('assessment_type_id', 1)
            ->distinct('student_id')
            ->pluck('student_id');

        $programs = Program::with('qualification')->whereIn('id', function ($query) use ($courseIdsWithGrades) {
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
                'qualifications' => $program->qualification->name,
                'students' => 0, // Initialize total students count for the program
                'status' => 1, // Default status is published (status 1)
                'levels' => [],
            ];
            $studentsCount = Student::whereIn('id', $studentIds)
                ->where('program_id', $program->id)
                ->distinct('id')
                ->count();

            $programData['students'] += $studentsCount; // Add students count to total students for the program

            foreach ($program->programCourses as $programCourse) {
                $course = $programCourse->course;


                // Check if publication status is 0 for any student in the course
                $publicationStatus = Grade::where('academic_period_id', $id)
                    ->where('course_id', $course->id)
                    ->where('publication_status', 0)
                    ->exists();

                if ($publicationStatus) {
                    // If any student in the course has publication status 0, set program status to 0
                    $programData['status'] = 0;
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

            // Only add the program to the array if it has students
            if ($programData['students'] > 0) {
                $organizedData[] = $programData;
            }
        }

        return array_values($organizedData);
    }

    public function getCaGrades($level, $pid, $aid)
    {
        $data = Student::where('program_id', $pid)
            ->where('course_level_id', $level)
            ->whereHas('grades', function ($query) use ($aid) {
                $query->whereNot('assessment_type_id', 1)
                    ->where('academic_period_id', $aid);
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
                        ->whereNot('assessment_type_id', 1)
                        ->where('publication_status', 0)
                        ->select(
                            'course_id',
                            'academic_period_id',
                            'course_code',
                            'course_title',
                            'student_id',
                            'total',
                            'assessment_type_id'

                        )
                        ->groupBy('assessment_type_id', 'total', 'course_code', 'course_title', 'course_id', 'academic_period_id', 'student_id');
                },
                'program',
                'user',
                'level',
                'enrollments.class.course.grades.assessment_type.class_assessment'
            ])->paginate(1, ['*'], 'page', 1);

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
                                'grade' => self::calculateGrade($grade->total, $student->program->id),
                                'outof' => self::getClassAssessmentCastotal($courseId, $aid),
                                'id' => self::getGradeIDCAs($aid, $courseId, $studentId),
                            ];

                            $organizedData['students'][$studentId]['courses'][$courseId]['course_details']['student_grades'][] = $studentGrades;
                        }
                    }
                }
            }
        }

        return $organizedData;
    }

    public function getCaGradesDatatableCollection($level, $pid, $aid)
    {
        $studentGrades = Student::where('program_id', $pid)
            ->where('course_level_id', $level)
            ->whereHas('grades', function ($query) use ($aid) {
                $query->whereNot('assessment_type_id', 1)
                    ->where('academic_period_id', $aid);
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
                        ->whereNot('assessment_type_id', 1)
                        ->where('publication_status', 0)
                        ->select(
                            'course_id',
                            'academic_period_id',
                            'course_code',
                            'course_title',
                            'student_id',
                            'total',
                            'assessment_type_id'

                        )
                        ->groupBy('assessment_type_id', 'total', 'course_code', 'course_title', 'course_id', 'academic_period_id', 'student_id');
                },
                'program',
                'user',
                'level',
                'enrollments.class.course.grades.assessment_type.class_assessment'
            ])->get();

        $studentGradesArr = [];

        foreach ($studentGrades as $student) {
            $studentId = $student->id;

            // Initialize student's data if not already present
            if (!isset($studentGradesArr[$studentId])) {
                $studentGradesArr[$studentId] = [
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
                    if (!isset($studentGradesArr[$studentId]['courses'][$courseId])) {
                        $studentGradesArr[$studentId]['courses'][$courseId] = [
                            'course_id' => $courseId,
                            'class_id' => $enrollment->class->id,
                            'academic_period_id' => $aid, // Assuming academic period is the same for all courses
                            'course_code' => $enrollment->class->course->code,
                            'course_title' => $enrollment->class->course->name,
                            'grades' => [],
                        ];
                    }

                    foreach ($enrollment->class->course->grades as $grade) {
                        if ($grade->academic_period_id == $aid && $grade->student_id == $studentId) {
                            $studentGradesArr[$studentId]['courses'][$courseId]['grades'][] = [
                                'type' => $grade->assessment_type->name,
                                'total' => $grade->total,
                                'grade' => self::calculateGrade($grade->total, $student->program->id),
                                'outof' => self::getClassAssessmentCastotal($courseId, $aid),
                                'id' => self::getGradeIDCAs($aid, $courseId, $studentId),
                            ];
                        }
                    }
                }
            }
        }


        return collect($studentGradesArr);
    }

    public function getCaGradesLoad($level, $pid, $aid, $current_page, $last_page, $per_page)
    {
        $data = Student::where('program_id', $pid)
            ->where('course_level_id', $level)
            ->whereHas('grades', function ($query) use ($aid) {
                $query->whereNot('assessment_type_id', 1)
                    ->where('academic_period_id', $aid);
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
                        ->whereNot('assessment_type_id', 1)
                        ->where('publication_status', 0)
                        ->select(
                            'course_id',
                            'academic_period_id',
                            'course_code',
                            'course_title',
                            'student_id',
                            'total',
                            'assessment_type_id'

                        )
                        ->groupBy('assessment_type_id', 'total', 'course_code', 'course_title', 'course_id', 'academic_period_id', 'student_id');
                },
                'program',
                'user',
                'level',
                'enrollments.class.course.grades.assessment_type.class_assessment'
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
                                'grade' => self::calculateGrade($grade->total, $student->program->id),
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
                'program',
                'user',
                'level',
                'enrollments.class.course.grades.assessment_type.class_assessment'
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
                'program',
                'user',
                'level',
                'enrollments.class.course.grades.assessment_type.class_assessment'
            ])->paginate(3, ['*'], 'page', 1);

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
                        'grade' => self::calculateGrade(10, $student->program_id), // Assuming default grade calculation
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
                                'grade' => self::calculateGrade($grade->total_sum, $student->program->id),
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

        return $organizedData;
    }

    public function getGradesDatatableCollection($level, $pid, $aid)
    {
        $studentGrades = Student::where('program_id', $pid)
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
                'program',
                'user',
                'level',
                'enrollments.class.course.grades.assessment_type.class_assessment'
            ])->get();

        $studentGradesArr = [];

        foreach ($studentGrades as $student) {
            foreach ($student->enrollments as $enrollment) {
                if (
                    $enrollment->class &&
                    $enrollment->class->academic_period_id !== null &&
                    $enrollment->class->academic_period_id == $aid
                ) {
                    $courseId = $enrollment->class->course->id;
                    $studentId = $student->id;

                    // Initialize student's data if not already present
                    if (!isset($studentGradesArr[$studentId])) {
                        $studentGradesArr[$studentId] = [
                            'id' => $studentId,
                            'course_level_id' => $level,
                            'program_id' => $student->program->id,
                            'program_name' => $student->program->name,
                            'ac' => $aid,
                            'name' => $student->user->first_name . ' ' . $student->user->middle_name . ' ' . $student->user->last_name,
                            'calculated_grade' => self::comments($studentId, $aid, 1),
                        ];
                    }

                    // Initialize course's data if not already present
                    if (!isset($studentGradesArr[$studentId]['courses'][$courseId])) {
                        $studentGradesArr[$studentId]['courses'][$courseId] = [
                            'course_id' => $enrollment->class->course->id,
                            'class_id' => $enrollment->class->id,
                            'academic_period_id' => $aid, // Assuming academic period is the same for all courses
                            'course_code' => $enrollment->class->course->code,
                            'course_title' => $enrollment->class->course->name,

                            'grades' => [
                                'exam' => 'NE',
                                'ca' => 'NE',
                                'total_sum' => 'NE',
                                'grade' => self::calculateGrade(10, $student->program_id), // Assuming default grade calculation
                                'outof' => 'NE', //self::getClassAssessmentExams($enrollment->class->course->id, $aid),
                                'id' => 'NE', //self::getGradeID($aid, $enrollment->class->course->id, $studentId),
                            ]
                        ];
                    }


                    // If there are grades available for the course, update student grades
                    foreach ($enrollment->class->course->grades as $grade) {
                        if ($grade->academic_period_id == $aid && $grade->student_id == $studentId) {
                            $studentGradesArr[$studentId]['courses'][$courseId]['grades'] = [
                                'exam' => $grade->exam,
                                'ca' => $grade->ca,
                                'total_sum' => $grade->total_sum,
                                'grade' => self::calculateGrade($grade->total_sum, $student->program->id),
                                'outof' => self::getClassAssessmentExams($enrollment->class->course->id, $aid),
                                'id' => self::getGradeID($aid, $enrollment->class->course->id, $studentId),
                            ];
                        }
                    }

                    // $studentGradesArr[$studentId]['courses'][$courseId]['course_details']['student_grades'][] = $studentGradesArr;
                }
            }
        }

        return collect($studentGradesArr);
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
                'program',
                'user',
                'level',
                'enrollments.class.course.grades.assessment_type.class_assessment'
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
                        'grade' => self::calculateGrade(0, $student->program_id), // Assuming default grade calculation
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
                                    'grade' => self::calculateGrade($grade->total_sum, $student->program->id),
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
        return $this->extracted($result, $aid, $student->program->id);
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
        /*return Student::where('program_id', $pid)
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
            ->count();*/
       return Student::where('program_id', $pid)
            ->where('course_level_id', $level)
            ->whereHas('grades', function ($query) use ($aid) {
                $query->where('academic_period_id', $aid);
            })
            ->whereHas('enrollments.class', function ($query) use ($aid) {
                $query->where('academic_period_id', $aid);
            })
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
    public function checkStudentCourseLevel($studentId)
    {
        // Retrieve student information
        $stud = Student::with('grades', 'enrollments.class.course')->find($studentId);
        //dd($stud);

        if (!$stud) {
            return ['message' => 'Student not found'];
        }
        $matchingCourseCount = $stud->enrollments->pluck('class.course_id')->filter(
            function ($courseId) use ($stud) {
                return $stud->grades->where('publication_status', 0)->where('assessment_type_id', 1)->where('course_id', $courseId)->isNotEmpty();
            }
        )->count();
        // dd($matchingCourseCount);
        // Retrieve distinct course levels for the student's program
        $distinctCourseLevels = ProgramCourses::where('program_id', $stud->program_id)
            ->distinct('course_level_id')
            ->orderBy('course_level_id')
            ->pluck('course_level_id');

        // Determine the student's current course level
        $currentCourseLevelId = $stud->course_level_id;

        // Find the index of the current course level in the list of distinct course levels
        $currentIndex = $distinctCourseLevels->search($currentCourseLevelId);

        if ($currentIndex === false) {
            return ['message' => 'Current course level not found in program courses'];
        }

        if ($stud->study_mode_id == 3 && $matchingCourseCount > 3) {

            // Determine the next course level
            $nextCourseLevelId = $distinctCourseLevels->get($currentIndex + 1);

            if (!$nextCourseLevelId) {
                return ['message' => 'Student is already in the final year'];
            }

            // Update the student's course level to the next level
            $stud->course_level_id = $nextCourseLevelId;
            $stud->save();
        } else {
            //dd($stud->semester);

            if ($stud->semester == 1 && $matchingCourseCount > 1) {
                $stud->semester = 2;
                $stud->save();
                return [
                    'message' => 'true'
                ];
            } elseif ($matchingCourseCount > 1) {
                // Determine the next course level
                $nextCourseLevelId = $distinctCourseLevels->get($currentIndex + 1);

                if (!$nextCourseLevelId) {
                    return ['message' => 'Student is already in the final year'];
                }

                // Update the student's course level to the next level
                $stud->semester = 1;
                $stud->course_level_id = $nextCourseLevelId;
                $stud->save();
                return [
                    'message' => 'true'
                ];
            }
        }

        // Prepare the response
        $response = [
            //            'student_id' => $stud->id,
            //            'program_id' => $stud->program_id,
            //            'previous_course_level_id' => $currentCourseLevelId,
            //            'new_course_level_id' => $stud->course_level_id,
            'message' => 'true'
        ];

        return $response;
    }
    public function publishGrades($id = null, $apid, $type)
    {/*
        $id = Grade::where('academic_period_id', $apid)
            ->distinct('student_id')
            ->orderBy('student_id')
            ->pluck('student_id');
          foreach ($id as $item) {
               dd($item);
              $status = $this->TermSemesterStatus($item, $apid, $type);

              if ($status['status'] == 'new'){
                  $this->checkStudentCourseLevel($item);
              }
          }
          */


        if (!empty($id)) {
            if ($type == 1) {
                foreach ($id as $item) {
                    $status = $this->TermSemesterStatus($item, $apid, $type);

                    if ($status['status'] == 'new') {
                        $this->checkStudentCourseLevel($item);
                    }
                }
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
        } else {
            if ($type == 1) {
                $id = Grade::where('academic_period_id', $apid)
                    ->distinct('student_id')
                    ->orderBy('student_id')
                    ->pluck('student_id');
                foreach ($id as $item) {
                    $status = $this->TermSemesterStatus($item, $apid, $type);
                    // $status = $this->TermSemesterStatus($id[7], $apid, $type);
                    // dd($status);
                    if ($status['status'] === 'new') {
                        $this->checkStudentCourseLevel($item);
                    }
                }
                Grade::where('academic_period_id', $apid)->where('assessment_type_id', 1)
                    ->update([
                        'publication_status' => 1,
                    ]);
                //dd($id);
            } else {
                Grade::where('academic_period_id', $apid)->whereNot('assessment_type_id', 1)
                    ->update([
                        'publication_status' => 1,
                    ]);
            }
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

        $grades = Grade::where('student_id', $student)->where('publication_status', 1)->whereNot('assessment_type_id', 1)
            //            ->with(['academicPeriods', 'student'])
            //            ->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            //            ->selectRaw('SUM(total) as total_sum')
            //            ->groupBy('academic_period_id', 'course_code', 'course_title', 'student_id', 'course_id')
            //            ->orderBy('academic_period_id')
            //            ->orderBy('course_code')
            //            ->get();

            ->with(['academicPeriods', 'student'])
            ->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->selectRaw('SUM(total) as total_sum')
            ->groupBy('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
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

        $grades = Grade::where('student_id', $student)->where('publication_status', 1)
            ->with(['academicPeriods', 'student'])
            ->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->selectRaw('SUM(total) as total_sum')
            ->groupBy('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->orderBy('academic_period_id')
            ->orderBy('course_code')
            ->get();
        //        $grades = Grade::where('student_id', $student)
        //            ->where('publication_status', 1)
        //            ->whereIn('course_id', function($query) use ($student) {
        //                $query->select('course_id')
        //                    ->from('grades')
        //                    ->where('student_id', $student)
        //                    ->where('assessment_type_id', 1);
        //            })
        //            ->with(['academicPeriods', 'student'])
        //            ->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
        //            ->selectRaw('SUM(total) as total_sum')
        //            ->groupBy('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
        //            ->orderBy('academic_period_id')
        //            ->orderBy('course_code')
        //            ->get();

        $grades = Grade::where('student_id', $student)
            ->where('publication_status', 1)
            ->with(['academicPeriods', 'student'])
            ->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->selectRaw('SUM(total) as total_sum')
            ->groupBy('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->whereExists(function ($query) use ($student) {
                $query->select('id')
                    ->from('grades')
                    ->where('student_id', $student)
                    ->where('publication_status', 1)
                    ->where('assessment_type_id', 1);
            })
            ->orderBy('academic_period_id')
            ->orderBy('course_code')
            ->get();

        /* $grades = Grade::where('student_id', $student)->where('publication_status', 1)
//            ->with(['academicPeriods', 'student'])
//            ->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
//            ->selectRaw('SUM(total) as total_sum')
//            ->groupBy('academic_period_id', 'course_code', 'course_title', 'student_id', 'course_id')
//            ->orderBy('academic_period_id')
//            ->orderBy('course_code')
//            ->get();

            ->with(['academicPeriods', 'student'])
            ->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->selectRaw('SUM(total) as total_sum')
            ->groupBy('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->orderBy('academic_period_id')
            ->orderBy('course_code')
            ->get();*/

        $organizedResults = [];

        foreach ($grades as $grade) {
            if ($grade->student_id == $student) {
                $academicPeriodId = $grade->academic_period_id;
                //$academicPeriodId = 'courses';
                $course = [
                    'grade_id' => $grade->id,
                    'course_code' => $grade->course_code,
                    'course_title' => $grade->course_title,
                    'student_id' => $grade->student_id,
                    'total' => $grade->total_sum,
                    'grade' => $this->calculateGrade($grade->total_sum, $student_id->program_id)
                ];

                if (!isset($organizedResults[$academicPeriodId])) {
                    $organizedResults[$academicPeriodId] = [
                        'academic_period_name' => $grade->academicPeriods->name,
                        'academic_period_code' => $grade->academicPeriods->code,
                        'academic_period_id' => $grade->academicPeriods->id,
                        'academic_period_start_date' => $grade->academicPeriods->ac_start_date,
                        'academic_period_end_date' => $grade->academicPeriods->ac_end_date,
                        'comments' => $this->comments($student, $grade->academicPeriods->id, 1),
                    ];
                    // dd($this->comments($student, $grade->academicPeriods->id, 1));
                }

                $organizedResults[$academicPeriodId]['grades'][] = $course;
            }
        }
        //dd($organizedResults);
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

    //exams semester term level

    public function TermSemesterStatus($student_id = null, $academicPeriodID, $type)
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
            ->where('academic_period_id', $academicPeriodID)
            ->with(['academicPeriods', 'student'])
            ->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->selectRaw('SUM(total) as total_score')
            ->groupBy('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
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
            ->where('academic_period_id', $academicPeriodID)
            ->with(['academicPeriods', 'student'])
            ->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->selectRaw('SUM(total) as total_score')
            ->groupBy('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
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
            $coursesToRepeat = implode(",", array_column($courseFailedArray, 'course_code'));
            $comment = 'Part time ' . $coursesToRepeat;
        } elseif ($passedCourse == $courseCount) {
            $comment = 'Clear Pass';
            $status = 'new';
        } elseif ($courseCount - 1 == $passedCourse || $courseCount - 2 == $passedCourse) {
            $coursesToRepeat = implode(", ", array_column($courseFailedArray, 'course_code'));
            $comment = 'Proceed & Repeat ' . $coursesToRepeat;
            $status = 'new';
        } elseif ($courseCount - 3 >= $passedCourse) {
            $coursesToRepeat = implode(",", array_column($courseFailedArray, 'course_code'));
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
    }
    public
    static function calculateGrade($total, $program)
    {
        // Define your grade thresholds and corresponding values here
        $programsuser = [6, 32];
        if (!in_array($program, $programsuser))
            if ($program) {
                if ($total == 0) {
                    return 'Not Examined';
                }
                if ($total == -1) {
                    return 'Exempted';
                }
                if ($total == -2) {
                    return 'Withdrew with Permission';
                }
                if ($total == -3) {
                    return 'Disqualified';
                }
                if ($total == -4) {
                    return 'Deferred';
                }
                if ($total == -5) {
                    return 'Changed Mode of Study';
                } else if ($total == 0) {
                    $grade = 'NE';
                } else if ($total >= 1 && $total <= 29) {
                    return 'D';
                } else if ($total >= 30 && $total <= 39) {
                    return 'D+';
                } else if ($total >= 40 && $total <= 45) {
                    return 'C';
                } else if ($total >= 46 && $total <= 55) {
                    return 'C+';
                } else if ($total >= 56 && $total <= 65) {
                    return 'B';
                } else if ($total >= 66 && $total <= 75) {
                    return 'B+';
                } else if ($total >= 76 && $total <= 85) {
                    return 'A';
                } else if ($total >= 86 && $total <= 100) {
                    return 'A+';
                }
            } else {
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
    }
    /**
     * @param $result
     * @param $aid
     * @return mixed
     */
    public function extracted($result, $aid, $program): mixed
    {
        $result->getCollection()->transform(function ($item) use ($program, $aid) {
            $item['calculated_grade'] = $this->comments($item['id'], $aid, 1);
            $item->enrollments->transform(function ($enrollment) use ($program, $item, $aid) {
                // dd($enrollment);
                $enrollment->class->course->grades->transform(function ($grade) use ($program, $item, $aid) {
                    // Perform calculations here based on the data retrieved
                    $grade['grade'] = $this->calculateGrade($grade->total_sum, $program);
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
    public function GetStudentExamGrades($id)
    {
        // Fetch students from the given user IDs
        $students = Student::where('program_id', $id)->get();

        // Create a map of user_id to student_id
        $studentIds = $students->pluck('id')->toArray();

        // Fetch grades for all the students
        $grades = Grade::whereIn('student_id', $studentIds)
            ->where('publication_status', 1)
            ->with(['academicPeriods', 'student'])
            ->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->selectRaw('SUM(total) as total_sum')
            ->groupBy('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->whereExists(function ($query) {
                $query->select('id')
                    ->from('grades')
                    ->whereColumn('student_id', 'grades.student_id')
                    ->where('publication_status', 1)
                    ->where('assessment_type_id', 1);
            })
            ->orderBy('academic_period_id')
            ->orderBy('course_code')
            ->get();

        $organizedResults = [];

        foreach ($grades as $grade) {
            $studentId = $grade->student_id;
            $academicPeriodId = $grade->academic_period_id;
            $pro = Student::find($studentId);

            $course = [
                'grade_id' => $grade->id,
                'course_code' => $grade->course_code,
                'course_title' => $grade->course_title,
                'student_id' => $grade->student_id,
                'total' => $grade->total_sum,
                'grade' => $this->calculateGrade($grade->total_sum, $pro->program_id)
            ];

            if (!isset($organizedResults[$studentId])) {
                $organizedResults[$studentId] = [
                    'student_id' => $studentId,
                    'grades_by_period' => []
                ];
            }

            if (!isset($organizedResults[$studentId]['grades_by_period'][$academicPeriodId])) {
                $organizedResults[$studentId]['grades_by_period'][$academicPeriodId] = [
                    'academic_period_name' => $grade->academicPeriods->name,
                    'academic_period_code' => $grade->academicPeriods->code,
                    'academic_period_id' => $grade->academicPeriods->id,
                    'academic_period_start_date' => $grade->academicPeriods->ac_start_date,
                    'academic_period_end_date' => $grade->academicPeriods->ac_end_date,
                    'comments' => $this->comments($studentId, $grade->academicPeriods->id, 1),
                    'grades' => []
                ];
            }

            $organizedResults[$studentId]['grades_by_period'][$academicPeriodId]['grades'][] = $course;
        }

        return $organizedResults;
    }
}
