<?php

use App\Http\Controllers\Reports\Accounts\AccountReportsController;
use App\Http\Controllers\Reports\Enrollments\EnrollmentReportsController;
use App\Http\Controllers\Users\MyAccountController;
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
use App\Http\Controllers\Accounting\{InvoiceController, PaymentMethodController, StatementController};
use App\Http\Controllers\Academics\AcademicPeriodController;
use App\Http\Controllers\Academics\AcademicPeriodClassController;
use App\Http\Controllers\Academics\StudentRegistrationController;

use App\Http\Controllers\Admissions\StudentController;

use App\Http\Controllers\Applications\ApplicantController;

use App\Http\Controllers\Profile\MaritalStatusController;

use App\Http\Controllers\Accounting\FeeController;

use App\Http\Controllers\Users\UserController;

use App\Http\Controllers\Enrollments\EnrollmentController;
use App\Http\Controllers\Residency\{CountryController, ProvinceController, TownController};
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Users\StudentController as UsersStudentController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/staff-login', function () {
    return view('auth.staff_login');
})->name('staff');

Auth::routes(['register' => false]);
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::group(['prefix' => 'students'], function () {
    Route::get('/search', [StudentController::class, 'search'])->name('search');
    Route::post('/search', [StudentController::class, 'search'])->name('students.lists');
    Route::get('/profile/{id}', [StudentController::class, 'studentShow'])->name('show.student');
});
Route::group(['prefix' => 'assess'], function () {
    Route::get('/classes/{id}', [ClassAssessmentsController::class, 'getClasses'])->name('class-names');
    Route::post('/updateExams/{id}', [ClassAssessmentsController::class, 'UpdateTotalResultsExams'])->name('assessmentUpdate');
    Route::get('/class-list/{id}', [ClassAssessmentsController::class, 'getClassesToPublish'])->name('class-list');
    Route::get('/student-list/{class}/{assessid}', [ClassAssessmentsController::class, 'StudentListResults'])->name('myClassStudentList');
    Route::post('/process', [ClassAssessmentsController::class, 'ProcessUploadedResults'])->name('import.process');
    Route::post('/results-upload-template', [ClassAssessmentsController::class, 'DownloadResultsTemplate'])->name('template.download');
    Route::post('/get-results-update', [ClassAssessmentsController::class, 'getAssessToUpdate'])->name('update.assessments');
    Route::post('/board-exam-update', [ClassAssessmentsController::class, 'BoardofExaminersUpdateResults'])->name('BoardofExaminersUpdateResults');
    Route::post('/publish-program-results', [ClassAssessmentsController::class, 'PublishProgramResults'])->name('publishProgramResults');
    Route::post('/post-results', [ClassAssessmentsController::class, 'PostStudentResults'])->name('postedResults.process');

    Route::group(['prefix' => 'cas'], function () {
        Route::get('/publish-cas-program-list/{id}', [ClassAssessmentsController::class, 'GetProgramsToPublishCas'])->name('getPublishProgramsCas');
        Route::get('/program-results-levels', [ClassAssessmentsController::class, 'GetProgramResultsLevelCas'])->name('getPramResultsLevelCas');
        Route::post('/load-more', [ClassAssessmentsController::class, 'LoadMoreResultsCas'])->name('load.more.results.board.Cas');
    });

    Route::group(['prefix' => 'exams'], function () {
        Route::get('/publish-program-list/{id}', [ClassAssessmentsController::class, 'GetProgramsToPublish'])->name('getPublishPrograms');
        Route::get('/program-results/{aid}/{pid}', [ClassAssessmentsController::class, 'GetProgramResults'])->name('getPramResults');
        Route::get('/program-results-levels', [ClassAssessmentsController::class, 'GetProgramResultsLevel'])->name('getPramResultsLevel');
        Route::get('/results', [ClassAssessmentsController::class, 'MyResults'])->name('student-exam_results');
        Route::post('/load-more', [ClassAssessmentsController::class, 'LoadMoreResults'])->name('load.more.results.board');
    });
});


Route::group(['prefix' => 'reports'], function () {
    Route::group(['prefix' => 'accounts'], function () {
        Route::get('/revenue-analysis', [AccountReportsController::class, 'RevenueAnalysis'])->name('revenue.analysis');
        Route::post('/revenue-analysis', [AccountReportsController::class, 'RevenueAnalysis'])->name('revenue-revenue-result');

        Route::get('/invoices', [AccountReportsController::class, 'invoices'])->name('invoices');
        Route::post('/invoices', [AccountReportsController::class, 'invoices'])->name('invoices-results');

        Route::get('/transactions', [AccountReportsController::class, 'Transactions'])->name('transactions');
        Route::post('/transactions', [AccountReportsController::class, 'Transactions'])->name('transaction-results');

        Route::get('/aged-receivables', [AccountReportsController::class, 'AgedReceivables'])->name('aged.receivables');
        Route::post('/aged-receivables', [AccountReportsController::class, 'AgedReceivables'])->name('aged.receivables.post');

        Route::get('/failed-transactions', [AccountReportsController::class, 'FailedPayments'])->name('failed.transaction');

        Route::get('/student-list', [AccountReportsController::class, 'StudentList'])->name('student.list');
        Route::post('/student-list', [AccountReportsController::class, 'StudentList'])->name('student.list.post');

        Route::get('/credit-notes', [AccountReportsController::class, 'CreditNotes'])->name('credit.notes');
    });

    Route::group(['prefix' => 'enrollments'], function () {
        Route::get('/enrollments', [EnrollmentReportsController::class, 'index'])->name('enrollments.reports');
        Route::get('/exam-registers', [EnrollmentReportsController::class, 'ExamRegisters'])->name('registers.reports');
        Route::get('/student-list-reports', [EnrollmentReportsController::class, 'StudentList'])->name('student.list.reports');
        Route::get('/audit-trail', [EnrollmentReportsController::class, 'AuditTrailReports'])->name('audit.trail.reports');
    });
});

Route::group(['prefix' => 'accounts'], function () {
    Route::get('/results', [ClassAssessmentsController::class, 'MyResults'])->name('student-exam_results');
    Route::get('/ca-results', [ClassAssessmentsController::class, 'MyCAResults'])->name('student_ca_results');
    Route::get('/exam-registration', [ClassAssessmentsController::class, 'ExamRegistration'])->name('student-exam_registration');
});

/*Route::group(['prefix' => 'application'], function () { */
Route::get('/initiate-application', [ApplicantController::class, 'index'])->name('application.index');
Route::post('/application/step-1', [ApplicantController::class, 'startApplication'])->name('application.start_application');
Route::get('/application/step-2/{application_id}', [ApplicantController::class, 'completeApplication'])->name('application.complete_application');
Route::post('/application/step-3', [ApplicantController::class, 'saveApplication'])->name('application.save_application');
/*}); */


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

Route::resource('statements', StatementController::class);

Route::resource('invoices', InvoiceController::class);
Route::post('custom-invoice', [InvoiceController::class, 'customInvoice'])->name('invoices.custom-invoice');
Route::post('batch-invoice-process', [InvoiceController::class, 'batchInvoicing'])->name('invoices.batchInvoicing');
Route::post('student-invoice-process', [InvoiceController::class, 'invoice'])->name('invoices.invoice');

Route::resource('enrollments', EnrollmentController::class);
Route::get('summary', [StudentRegistrationController::class, 'summary'])->name('registration.summary');

// Residency Routes
Route::get('/countries/{countryId}/provinces/', [CountryController::class, 'getProvincesByCountry'])->name('provinces.getProvincesByCountry');
Route::resource('countries', CountryController::class);
Route::get('/provinces/{provinceId}/towns', [ProvinceController::class, 'getTownsByProvince'])->name('towns.getTownsByProvince');
Route::resource('provinces', ProvinceController::class);
Route::resource('towns', TownController::class);

// System Settings Routes
Route::resource('settings', SettingsController::class);

// Payment Methods Routes
Route::resource('payment-methods', PaymentMethodController::class);

//my account
Route::group(['prefix' => 'my_account'], function () {
    Route::get('/', [MyAccountController::class, 'index'])->name('my_account');
    // Route::put('/', 'MyAccountController@update_profile')->name('my_account.update');
    Route::put('/change_password', [MyAccountController::class, 'change_pass'])->name('my_account.change_pass');
});

Route::put('reset-password', [StudentController::class, 'resetAccountPassword'])->name('students.resetAccountPassword');

// Student-specific Routes
Route::group(['prefix' => 'student'], function () {
    Route::get('/enrollments', [UsersStudentController::class, 'enrollments'])->name('student.enrollments');
    Route::get('/finances', [UsersStudentController::class, 'finances'])->name('student.finances');

    // Financial statements generator/download routes
    Route::get('/invoices/{invoice}/download/', [InvoiceController::class, 'downloadInvoice'])->name('student.download-invoice');
    Route::get('/invoices/{student}/export/', [InvoiceController::class, 'exportInvoices'])->name('student.export-invoices');
    Route::get('/statements/{invoice}/download/', [StatementController::class, 'downloadStatement'])->name('student.download-statement');
    Route::get('/statements/{student}/export/', [StatementController::class, 'exportStatements'])->name('student.export-statements');
});
