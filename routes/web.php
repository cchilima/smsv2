<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Academics\APFeesController;
use App\Http\Controllers\Academics\APManagementController;
use App\Http\Controllers\Academics\AssessmentsTypesController;
use App\Http\Controllers\Academics\ClassAssessmentsController;
use App\Http\Controllers\Academics\CourseLevelController;
use App\Http\Controllers\Academics\DepartmentController;
use App\Http\Controllers\Academics\IntakeController;
use App\Http\Controllers\Academics\PeriodTypeController;
use App\Http\Controllers\Academics\PrerequisiteController;
use App\Http\Controllers\Academics\ProgramController;
use App\Http\Controllers\Academics\ProgramCoursesController;
use App\Http\Controllers\Academics\QualificationController;
use App\Http\Controllers\Academics\SchoolController;
use App\Http\Controllers\Academics\StudyModeController;
use App\Http\Controllers\Academics\CourseController;
use App\Http\Controllers\Academics\AcademicPeriodController;
use App\Http\Controllers\Academics\AcademicPeriodClassController;
use App\Http\Controllers\Academics\StudentRegistrationController;

use App\Http\Controllers\Admissions\StudentController;

use App\Http\Controllers\Profile\MaritalStatusController;

use App\Http\Controllers\Accounting\FeeController;

use App\Http\Controllers\Users\UserController;

use App\Http\Controllers\Enrollments\EnrollmentController;


Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false]);
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::group(['prefix' => 'students'], function () {
    Route::get('/search', [StudentController::class, 'search'])->name('search');
    Route::post('/search', [StudentController::class, 'search'])->name('students.lists');
    Route::get('/profile/{id}', [StudentController::class, 'studentShow'])->name('show.student');
});
Route::group(['prefix' => 'assess'], function (){
    Route::get('/classes/{id}',[ClassAssessmentsController::class,'getClasses'])->name('class-names');
    Route::post('/updateExams/{id}',[ClassAssessmentsController::class,'UpdateTotalResultsExams'])->name('assessmentUpdate');
    Route::get('/class-list/{id}',[ClassAssessmentsController::class,'getClassesToPublish'])->name('class-list');
    Route::get('/student-list/{class}/{assessid}',[ClassAssessmentsController::class,'StudentListResults'])->name('myClassStudentList');
    Route::post('/process',[ClassAssessmentsController::class,'ProcessUploadedResults'])->name('import.process');
    Route::post('/results-upload-template',[ClassAssessmentsController::class,'DownloadResultsTemplate'])->name('template.download');

    Route::group(['prefix' => 'cas'], function (){
        Route::get('/publish-cas-program-list/{id}',[ClassAssessmentsController::class,'GetProgramsToPublishCas'])->name('getPublishProgramsCas');
        Route::get('/program-results-levels',[ClassAssessmentsController::class,'GetProgramResultsLevelCas'])->name('getPramResultsLevelCas');
        Route::post('/load-more',[ClassAssessmentsController::class,'LoadMoreResultsCas'])->name('load.more.results.board.Cas');
    });

});

Route::resource('courses', CourseController::class);
Route::resource('programs', ProgramController::class);
Route::resource('study-modes', StudyModeController::class);
Route::resource('period-types', PeriodTypeController::class);
Route::resource('departments', DepartmentController::class);
Route::resource('qualifications', QualificationController::class);
Route::resource('levels', CourseLevelController::class);
Route::resource('intakes', IntakeController::class);
Route::resource('schools', SchoolController::class);
Route::resource('prerequisites', PrerequisiteController::class);
Route::resource('program-courses', ProgramCoursesController::class);
Route::resource('classAssessments', ClassAssessmentsController::class);
Route::resource('assessments', AssessmentsTypesController::class);
Route::resource('registration', StudentRegistrationController::class);

Route::resource('fees', FeeController::class);
Route::resource('marital-statuses', MaritalStatusController::class);
Route::resource('academic-periods', AcademicPeriodController::class);
Route::resource('academic-period-classes', AcademicPeriodClassController::class);
Route::resource('academic-period-management', APManagementController::class);
Route::resource('academic-period-fees', APFeesController::class);
Route::resource('students', StudentController::class);
Route::resource('users', UserController::class);

Route::resource('enrollments', EnrollmentController::class);
Route::get('summary', [StudentRegistrationController::class, 'summary'])->name('registration.summary');

Route::put('reset-password', [StudentController::class, 'resetAccountPassword'])->name('students.resetAccountPassword');

