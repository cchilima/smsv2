<?php

use App\Http\Controllers\Academics\AcademicPeriodClassController;
use App\Http\Controllers\Academics\AcademicPeriodController;
use App\Http\Controllers\Academics\APFeesController;
use App\Http\Controllers\Academics\APManagementController;
use App\Http\Controllers\Academics\AssessmentsTypesController;
use App\Http\Controllers\Academics\ClassAssessmentsController;
use App\Http\Controllers\Academics\CourseController;
use App\Http\Controllers\Academics\CourseLevelController;
use App\Http\Controllers\Academics\DepartmentController;
use App\Http\Controllers\Academics\GradeContoller;
use App\Http\Controllers\Academics\IntakeController;
use App\Http\Controllers\Academics\PeriodTypeController;
use App\Http\Controllers\Academics\PrerequisiteController;
use App\Http\Controllers\Academics\ProgramController;
use App\Http\Controllers\Academics\ProgramCoursesController;
use App\Http\Controllers\Academics\QualificationController;
use App\Http\Controllers\Academics\SchoolController;
use App\Http\Controllers\Academics\StudentRegistrationController;
use App\Http\Controllers\Academics\StudyModeController;
use App\Http\Controllers\Accomodation\BedSpaceController;
use App\Http\Controllers\Accomodation\BookingController;
use App\Http\Controllers\Accomodation\HostelController;
use App\Http\Controllers\Accomodation\RoomController;
use App\Http\Controllers\Accounting\{InvoiceController,
    QuotationController, PaymentMethodController,
    SponsorController,
    StatementController};
use App\Http\Controllers\Accounting\FeeController;
use App\Http\Controllers\Admissions\StudentController;
use App\Http\Controllers\Applications\ApplicantController;
use App\Http\Controllers\AuditReports\AuditReportsController;
use App\Http\Controllers\Enrollments\EnrollmentController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Notices\AnnouncementController;
use App\Http\Controllers\Profile\MaritalStatusController;
use App\Http\Controllers\Reports\Accounts\AccountReportsController;
use App\Http\Controllers\Reports\Enrollments\EnrollmentReportsController;
use App\Http\Controllers\Residency\{CountryController, ProvinceController, TownController};
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Users\MyAccountController;
use App\Http\Controllers\Users\StudentController as UsersStudentController;
use App\Http\Controllers\Users\UserController;
use App\Livewire\Students\{AddDropCourse};
use App\Livewire\Accounting\{ViewInvoiceDetails, ViewQuotationDetails, ApproveCreditNotes};
use App\Livewire\Applications\{InitiateApplication, CompleteApplication, CompletedApplication, MyApplications};

// Reactive Livewire Pages
use App\Livewire\Pages\Academics\AcademicPeriods\Index as AcademicPeriodsIndex;
use App\Livewire\Pages\Academics\AcademicPeriodClasses\Index as AcademicPeriodClassesIndex;
use App\Livewire\Pages\Academics\AcademicPeriodTypes\Index as AcademicPeriodTypesIndex;
use App\Livewire\Pages\Academics\Assessments\CAResultsReviewBoard;
use App\Livewire\Pages\Academics\Assessments\ExamResultsReviewBoard;
use App\Livewire\Pages\Academics\AssessmentTypes\Index as AssessmentTypesIndex;
use App\Livewire\Pages\Academics\Courses\Index as CoursesIndex;
use App\Livewire\Pages\Academics\CourseLevels\Index as CourseLevelsIndex;
use App\Livewire\Pages\Academics\Departments\Index as DepartmentsIndex;
use App\Livewire\Pages\Academics\Intakes\Index as IntakesIndex;
use App\Livewire\Pages\Academics\Prerequisites\Index as PrerequisitesIndex;
use App\Livewire\Pages\Academics\Programs\Index as ProgramsIndex;
use App\Livewire\Pages\Academics\Programs\Show as ShowProgram;
use App\Livewire\Pages\Academics\Qualifications\Index as QualificationsIndex;
use App\Livewire\Pages\Academics\Schools\Index as SchoolsIndex;
use App\Livewire\Pages\Academics\StudyModes\Index as StudyModesIndex;
use App\Livewire\Pages\Accommodation\Hostels\Index as HostelsIndex;
use App\Livewire\Pages\Accommodation\Rooms\Index as RoomsIndex;
use App\Livewire\Pages\Accommodation\Bookings\Index as BookingsIndex;
use App\Livewire\Pages\Accommodation\BedSpaces\Index as BedSpacesIndex;
use App\Livewire\Pages\Accounting\Fees\Index as FeesIndex;
use App\Livewire\Pages\Accounting\PaymentMethods\Index as PaymentMethodsIndex;
use App\Livewire\Pages\Admissions\Applications\Index as ApplicationsIndex;
use App\Livewire\Pages\Admissions\Students\Show as ShowStudent;
use App\Livewire\Pages\Admissions\Students\UploadPhotos as UploadStudentPhotos;
use App\Livewire\Pages\ClassAssessments\Index as ClassAssessmentIndex;
use App\Livewire\Pages\Notices\Announcements\Index as AnnouncementsIndex;
use App\Livewire\Pages\Residency\Countries\Index as CountriesIndex;
use App\Livewire\Pages\Residency\Provinces\Index as ProvincesIndex;
use App\Livewire\Pages\Residency\Towns\Index as TownsIndex;
use App\Livewire\Pages\Settings\MaritalStatuses\Index as MaritalStatusesIndex;

use App\Livewire\Permissions\{ManagePermissions, AddPermission};

use App\Models\Academics\ClassAssessment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/staff-login', function () {
    return view('auth.staff_login');
})->name('staff');

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('announcement/{announcement_id}', [AnnouncementController::class, 'ShowAnnouncement'])->name('announcement.fullview');
    Route::post('announcement/{announcement_id}/dismiss', [AnnouncementController::class, 'dismissAnnouncement'])->name('announcement.dismiss');

    Route::group(['prefix' => 'students'], function () {
        Route::get('/search', [StudentController::class, 'search'])->name('search');
        Route::post('/search', [StudentController::class, 'search'])->name('students.lists');
        Route::get('/list', [StudentController::class, 'list'])->name('students.list');
        // Route::get('/profile/{id}', [StudentController::class, 'studentShow'])->name('show.student');
        Route::get('/profile/{userId}', ShowStudent::class)->name('show.student');
        Route::get('/uploads/photos', UploadStudentPhotos::class)->name('students.upload-photos');
    });
    Route::group(['prefix' => 'assess'], function () {
        Route::get('/classes/{id}', [ClassAssessmentsController::class, 'getClasses'])->name('class-names');
        Route::post('/updateExams/{id}', [ClassAssessmentsController::class, 'UpdateTotalResultsExams'])->name('assessmentUpdate');
        Route::get('/class-lists', [ClassAssessmentsController::class, 'getAssessmentClassLists'])->name('assessments.class-lists.index');
        Route::get('/class-list/{id}', [ClassAssessmentsController::class, 'getClassesToPublish'])->name('class-list');
        Route::get('/program-list/{id}', [ClassAssessmentsController::class, 'getProgramResults'])->name('program-list');
        Route::get('/program-result-list/{id}', [ClassAssessmentsController::class, 'getStudentsProgramResults'])->name('student.download.result.list');
        Route::get('/student-list/{class}/{assessid}', [ClassAssessmentsController::class, 'StudentListResults'])->name('myClassStudentList');
        Route::post('/process', [ClassAssessmentsController::class, 'ProcessUploadedResults'])->name('import.process');
        Route::post('/results-upload-template', [ClassAssessmentsController::class, 'DownloadResultsTemplate'])->name('template.download');
        Route::post('/get-results-update', [ClassAssessmentsController::class, 'getAssessToUpdate'])->name('update.assessments');
        Route::post('/board-exam-update', [ClassAssessmentsController::class, 'BoardofExaminersUpdateResults'])->name('BoardofExaminersUpdateResults');
        Route::post('/publish-program-results', [ClassAssessmentsController::class, 'PublishProgramResults'])->name('publishProgramResults');
        Route::post('/post-results', [ClassAssessmentsController::class, 'PostStudentResults'])->name('postedResults.process');
        Route::post('/add-new-results', [ClassAssessmentsController::class, 'AddStudentResult'])->name('AddResults.student');
        Route::get('/publish-all-results/{ac}/{type}', [ClassAssessmentsController::class, 'PublishForAllStudents'])->name('PublishForAllStudents');

        Route::group(['prefix' => 'cas'], function () {
            Route::get('/publish-cas-program-list/{id}', [ClassAssessmentsController::class, 'GetProgramsToPublishCas'])->name('getPublishProgramsCas');
            Route::get('/program-results-levels', CAResultsReviewBoard::class)->name('getPramResultsLevelCas');
            // Route::get('/program-results-levels', [ClassAssessmentsController::class, 'GetProgramResultsLevelCas'])->name('getPramResultsLevelCas');
            Route::post('/load-more', [ClassAssessmentsController::class, 'LoadMoreResultsCas'])->name('load.more.results.board.Cas');
        });

        Route::group(['prefix' => 'exams'], function () {
            Route::get('/publish-program-list/{id}', [ClassAssessmentsController::class, 'GetProgramsToPublish'])->name('getPublishPrograms');
            Route::get('/program-results/{aid}/{pid}', [ClassAssessmentsController::class, 'GetProgramResults'])->name('getPramResults');
            // Route::get('/program-results-levels', [ClassAssessmentsController::class, 'GetProgramResultsLevel'])->name('getPramResultsLevel');
            Route::get('/program-results-levels', ExamResultsReviewBoard::class)->name('getPramResultsLevel');
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

            // student list pdf
            Route::get('/programs-student-list/{ac}', [EnrollmentReportsController::class, 'DownloadStudentProgramList'])->name('student.program.list');
            Route::get('/program-student-list/{ac}/{pid}', [EnrollmentReportsController::class, 'DownloadStudentProgramListOne'])->name('student.one.program.list');
            Route::get('/class-student-list/{ac}', [EnrollmentReportsController::class, 'DownloadAcClassLists'])->name('student.class.list');
            Route::get('/class-student-list/{ac}/{classid}', [EnrollmentReportsController::class, 'DownloadAcOneClassLists'])->name('student.one.class.list');

            // regidters
            Route::post('/exam-registers', [EnrollmentReportsController::class, 'ExamRegistersDownload'])->name('ac.exam.registers');
            // student id
            Route::get('/student-id/{student_id}', [EnrollmentReportsController::class, 'DownloadStudentIDs'])->name('student.id.download');
            Route::get('/student-slip-id/{student_id}', [EnrollmentReportsController::class, 'DownloadStudentExamSlip'])->name('student.exam.slip.download');
            Route::get('/student-transcript-id/{student_id}', [EnrollmentReportsController::class, 'DownloadStudentTranscript'])->name('student.transcript.download');

            // csv
            Route::get('/programs-csv-student-list/{ac}', [EnrollmentReportsController::class, 'DownloadstudentProgramListCsv'])->name('student.program.list.csv');
            Route::get('/program-csv-student-list/{ac}/{pid}', [EnrollmentReportsController::class, 'DownloadStudentProgramListOneCSV'])->name('student.csv.one.program.list');

            Route::get('/class-csv-student-list/{ac}', [EnrollmentReportsController::class, 'DownloadAcClassListsCSV'])->name('student.csv.class.list');

            Route::get('/class-csv-student-list/{ac}/{classid}', [EnrollmentReportsController::class, 'DownloadAcOneClassListsCSV'])->name('student.csv.one.class.list');

            // normal reports
            Route::post('/all-enrollments', [EnrollmentReportsController::class, 'downloadAcademicPeriodEnrollmentsReport'])->name('reports.enrollments.download');
        });
    });

    Route::group(['prefix' => 'accounts'], function () {
        //  Route::get('/results', [ClassAssessmentsController::class, 'MyResults'])->name('student-exam_results');
        Route::get('/ca-results', [ClassAssessmentsController::class, 'MyCAResults'])->name('student_ca_results');
        Route::get('/exam-registration', [ClassAssessmentsController::class, 'ExamRegistration'])->name('student-exam_registration');
    });


    // Permissions
    Route::get('/manage-permissions', ManagePermissions::class)->name('manage-permissions');
    Route::get('/add-permission', AddPermission::class)->name('add-permissions');

// Accounting livewire routes
Route::get('/invoice-details/{invoice_id}', ViewInvoiceDetails::class)->name('accounting.invoice_details');
Route::get('/approve-credit-notes', ApproveCreditNotes::class)->name('accounting.approve_credit_notes');


Route::get('/quotation-details/{quotation_id}', ViewQuotationDetails::class)->name('accounting.quotation_details');

    // Add drop courses
    Route::get('/add-drop-course/{student_id}', AddDropCourse::class)->name('students.add-drop-course');

    Route::post('/collect-application-fee', [ApplicantController::class, 'collectFee'])->name('application.collect_fee');

    // Applications report
    Route::get('/applications/{status}/{id}', [ApplicantController::class, 'ApplicationsStatus'])->name('status.applications_reports');

    Route::get('/applications', ApplicationsIndex::class)->name('application.index');
    Route::get('/applications-pending-fee-collection', [ApplicantController::class, 'applicationsPendingFeeCollection'])->name('application.pending_collection');
    Route::get('/applications/summary', [ApplicantController::class, 'ApplicationsSummary'])->name('application.summary_reports');

    Route::resource('courses', CourseController::class);
    Route::get('/courses', CoursesIndex::class)->name('courses.index');

    Route::get('/programs/{id}', ShowProgram::class)->name('programs.show');
    Route::get('/programs', ProgramsIndex::class)->name('programs.index');
    Route::resource('programs', ProgramController::class)->except(['show', 'index']);

    Route::resource('study-modes', StudyModeController::class);
    Route::get('/study-modes', StudyModesIndex::class)->name('study-modes.index');

    Route::resource('period-types', PeriodTypeController::class);
    Route::get('/period-types', AcademicPeriodTypesIndex::class)->name('period-types.index');

    Route::resource('departments', DepartmentController::class);
    Route::get('/departments', DepartmentsIndex::class)->name('departments.index');

    Route::resource('announcements', AnnouncementController::class);
    Route::get('/announcements', AnnouncementsIndex::class)->name('announcements.index');

    Route::resource('qualifications', QualificationController::class);
    Route::get('/qualifications', QualificationsIndex::class)->name('qualifications.index');

    Route::resource('levels', CourseLevelController::class);
    Route::get('/levels', CourseLevelsIndex::class)->name('levels.index');

    Route::resource('intakes', IntakeController::class);
    Route::get('/intakes', IntakesIndex::class)->name('intakes.index');

    Route::resource('schools', SchoolController::class);
    Route::get('/schools', SchoolsIndex::class)->name('schools.index');

    Route::resource('prerequisites', PrerequisiteController::class);
    Route::get('/prerequisites', PrerequisitesIndex::class)->name('prerequisites.index');

    Route::resource('program-courses', ProgramCoursesController::class);

    Route::resource('classAssessments', ClassAssessmentsController::class);
    Route::get('/classAssessments', ClassAssessmentIndex::class)->name('classAssessments.index');
    Route::resource('assessments', AssessmentsTypesController::class);
    Route::get('/assessments', AssessmentTypesIndex::class)->name('assessments.index');
    Route::resource('registration', StudentRegistrationController::class);

    Route::resource('fees', FeeController::class);
    Route::get('/fees', FeesIndex::class)->name('fees.index');

    Route::resource('payment-methods', PaymentMethodController::class);
    Route::get('/payment-methods', PaymentMethodsIndex::class)->name('payment-methods.index');

    Route::resource('marital-statuses', MaritalStatusController::class);
    Route::get('/marital-statuses', MaritalStatusesIndex::class)->name('marital-statuses.index');

    Route::resource('students', StudentController::class);
    Route::resource('users', UserController::class);

    // Academic Period Routes
    Route::resource('academic-periods', AcademicPeriodController::class);
    Route::get('/academic-periods', AcademicPeriodsIndex::class)->name('academic-periods.index');

    Route::resource('academic-period-classes', AcademicPeriodClassController::class);
    Route::get('/academic-period-classes', AcademicPeriodClassesIndex::class)->name('academic-period-classes.index');

    Route::resource('academic-period-management', APManagementController::class);
    Route::resource('academic-period-fees', APFeesController::class);
    Route::resource('audits', AuditReportsController::class);

    // Accommodation Module
    Route::resource('hostels', HostelController::class);
    Route::get('/hostels', HostelsIndex::class)->name('hostels.index');

    Route::resource('rooms', RoomController::class);
    Route::get('/rooms', RoomsIndex::class)->name('rooms.index');

    Route::resource('bookings', BookingController::class);
    Route::get('/bookings', BookingsIndex::class)->name('bookings.index');

    Route::resource('bed-spaces', BedSpaceController::class);
    Route::get('/bed-spaces', BedSpacesIndex::class)->name('bed-spaces.index');

    Route::get('/hostel-rooms/{id}', [BookingController::class, 'getRooms'])->name('hostel-rooms');
    Route::get('/room-bed-spaces/{id}', [BookingController::class, 'getBedSpaces'])->name('room-bed-space');
    Route::post('/accommodation-confirm', [BookingController::class, 'ConfirmBooking'])->name('confirmation.booking');
    // accommodation module student side
    Route::post('/accommodation-apply', [StudentController::class, 'applyBedSpace'])->name('student.apply_accommodation');
    Route::get('/my-applications-rooms', [StudentController::class, 'getAppliedBedSpaces'])->name('student_applied.rooms');
    Route::get('/room-bed-spaces-student/{id}', [StudentController::class, 'getBedSpaces'])->name('room-bed-space-student');

    Route::get('/academic-period/{academicPeriodId}/programs', [AcademicPeriodController::class, 'getProgramsByAcademicPeriod'])->name('academic-periods.getProgramsByAcademicPeriod');

    Route::get('/academic-periods/{academicPeriodIds}/programs', [AcademicPeriodController::class, 'getProgramsByAcademicPeriods'])->name('academic-periods.getProgramsByAcademicPeriods');

    Route::resource('statements', StatementController::class);

    Route::resource('invoices', InvoiceController::class);
    Route::post('custom-invoice', [InvoiceController::class, 'customInvoice'])->name('invoices.custom-invoice');
    Route::post('batch-invoice-process', [InvoiceController::class, 'batchInvoicing'])->name('invoices.batchInvoicing');
    Route::post('student-invoice-process', [InvoiceController::class, 'invoice'])->name('invoices.invoice');


Route::post('student-quotation-process', [QuotationController::class, 'quotation'])->name('quotations.quotation');


    Route::resource('enrollments', EnrollmentController::class);
    
    Route::get('summary', [StudentRegistrationController::class, 'summary'])->name('registration.summary');

    // Residency Routes
    Route::get('/countries/{countryId}/provinces/', [CountryController::class, 'getProvincesByCountry'])->name('provinces.getProvincesByCountry');
    Route::resource('countries', CountryController::class);
    Route::get('/countries', CountriesIndex::class)->name('countries.index');

    Route::get('/provinces/{provinceId}/towns', [ProvinceController::class, 'getTownsByProvince'])->name('towns.getTownsByProvince');
    Route::resource('provinces', ProvinceController::class);
    Route::get('/provinces', ProvincesIndex::class)->name('provinces.index');

    Route::resource('towns', TownController::class);
    Route::get('/towns', TownsIndex::class)->name('towns.index');

    // System Settings Routes
    Route::resource('settings', SettingsController::class);

    // my account
    Route::group(['prefix' => 'my_account'], function () {
        Route::get('/', [MyAccountController::class, 'index'])->name('my_account');
        // Route::put('/', 'MyAccountController@update_profile')->name('my_account.update');
        Route::put('/change_password', [MyAccountController::class, 'change_pass'])->name('my_account.change_pass');
    });

    Route::put('reset-password', [StudentController::class, 'resetAccountPassword'])->name('students.resetAccountPassword');

    // Student-specific Routes
    Route::group(['prefix' => 'student'], function () {
        Route::get('/profile', [UsersStudentController::class, 'profile'])->name('student.profile');
        Route::get('/finances', [UsersStudentController::class, 'finances'])->name('student.finances');

        Route::group(['prefix' => 'help'], function () {
            Route::get('/how-to-make-payments', [UsersStudentController::class, 'howToMakePayments'])->name('students.help.make-payments');
        });

        // Financial statements generator/download routes
        Route::get('/invoices/{invoice}/download/', [InvoiceController::class, 'downloadInvoice'])->name('student.download-invoice');
        Route::get('/invoices/{student}/export/', [InvoiceController::class, 'exportInvoices'])->name('student.export-invoices');
        Route::get('/statements/{invoice}/download/', [StatementController::class, 'downloadStatement'])->name('student.download-statement');
        Route::get('/statements/{student}/export/', [StatementController::class, 'exportStatements'])->name('student.export-statements');
    });
});

// Application livewire routes

Route::get('/my-applications/{id}', MyApplications::class)->name('application.my-applications');
Route::get('/start-application', InitiateApplication::class)->name('start-application');
Route::get('/application/step-2/{application_id}', CompleteApplication::class)->name('application.complete_application');
Route::get('/application/{application_id}', CompletedApplication::class)->name('application.show');

//Route::get('/applications/initiate', [ApplicantController::class, 'initiate'])->name('application.initiate');
Route::post('/application/step-1', [ApplicantController::class, 'startApplication'])->name('application.start_application');
//Route::get('/application/step-2/{application_id}', [ApplicantController::class, 'completeApplication'])->name('application.complete_application');
Route::put('/application/step-3/{id}', [ApplicantController::class, 'saveApplication'])->name('application.save_application');
//Route::get('/application/{application_id}', [ApplicantController::class, 'show'])->name('application.show');
Route::get('/application/attachment/{attachment_id}/download', [ApplicantController::class, 'downloadAttachment'])->name('application.download_attachment');

Route::get('/provisional-letter', [ApplicantController::class, 'provisional'])->name('application.download_provisional');

Route::get('/applications-pending-fee-collection', [ApplicantController::class, 'applicationsPendingFeeCollection'])->name('application.pending_collection');
Route::post('/collect-application-fee', [ApplicantController::class, 'collectFee'])->name('application.collect_fee');

// Applications report
Route::get('/applications/{status}/{id}', [ApplicantController::class, 'ApplicationsStatus'])->name('status.applications_reports');
/* }); */

Route::resource('courses', CourseController::class);
Route::get('/courses', CoursesIndex::class)->name('courses.index');

Route::get('/programs/{id}', ShowProgram::class)->name('programs.show');
Route::get('/programs', ProgramsIndex::class)->name('programs.index');
Route::resource('programs', ProgramController::class)->except(['show', 'index']);

Route::resource('study-modes', StudyModeController::class);
Route::get('/study-modes', StudyModesIndex::class)->name('study-modes.index');

Route::resource('period-types', PeriodTypeController::class);
Route::get('/period-types', AcademicPeriodTypesIndex::class)->name('period-types.index');

Route::resource('departments', DepartmentController::class);
Route::get('/departments', DepartmentsIndex::class)->name('departments.index');

Route::resource('announcements', AnnouncementController::class);
Route::get('/announcements', AnnouncementsIndex::class)->name('announcements.index');

Route::resource('qualifications', QualificationController::class);
Route::get('/qualifications', QualificationsIndex::class)->name('qualifications.index');

Route::resource('levels', CourseLevelController::class);
Route::get('/levels', CourseLevelsIndex::class)->name('levels.index');

Route::resource('intakes', IntakeController::class);
Route::get('/intakes', IntakesIndex::class)->name('intakes.index');

Route::resource('schools', SchoolController::class);
Route::get('/schools', SchoolsIndex::class)->name('schools.index');

Route::resource('prerequisites', PrerequisiteController::class);
Route::get('/prerequisites', PrerequisitesIndex::class)->name('prerequisites.index');

Route::resource('program-courses', ProgramCoursesController::class);

Route::resource('classAssessments', ClassAssessmentsController::class);
Route::get('/classAssessments', ClassAssessmentIndex::class)->name('classAssessments.index');
Route::resource('assessments', AssessmentsTypesController::class);
Route::get('/assessments', AssessmentTypesIndex::class)->name('assessments.index');
Route::resource('registration', StudentRegistrationController::class);

Route::resource('fees', FeeController::class);
Route::get('/fees', FeesIndex::class)->name('fees.index');

Route::resource('payment-methods', PaymentMethodController::class);
Route::get('/payment-methods', PaymentMethodsIndex::class)->name('payment-methods.index');

Route::resource('marital-statuses', MaritalStatusController::class);
Route::get('/marital-statuses', MaritalStatusesIndex::class)->name('marital-statuses.index');

Route::resource('students', StudentController::class);
Route::resource('users', UserController::class);

// Academic Period Routes
Route::resource('academic-periods', AcademicPeriodController::class);
Route::get('/academic-periods', AcademicPeriodsIndex::class)->name('academic-periods.index');

Route::resource('academic-period-classes', AcademicPeriodClassController::class);
Route::get('/academic-period-classes', AcademicPeriodClassesIndex::class)->name('academic-period-classes.index');

Route::resource('academic-period-management', APManagementController::class);
Route::resource('academic-period-fees', APFeesController::class);
Route::resource('audits', AuditReportsController::class);

// Accommodation Module
Route::resource('hostels', HostelController::class);
Route::get('/hostels', HostelsIndex::class)->name('hostels.index');

Route::resource('rooms', RoomController::class);
Route::get('/rooms', RoomsIndex::class)->name('rooms.index');

Route::resource('bookings', BookingController::class);
Route::get('/bookings', BookingsIndex::class)->name('bookings.index');

Route::resource('bed-spaces', BedSpaceController::class);
Route::get('/bed-spaces', BedSpacesIndex::class)->name('bed-spaces.index');

Route::get('/hostel-rooms/{id}', [BookingController::class, 'getRooms'])->name('hostel-rooms');
Route::get('/room-bed-spaces/{id}', [BookingController::class, 'getBedSpaces'])->name('room-bed-space');
Route::post('/accommodation-confirm', [BookingController::class, 'ConfirmBooking'])->name('confirmation.booking');
// accommodation module student side
Route::post('/accommodation-apply', [StudentController::class, 'applyBedSpace'])->name('student.apply_accommodation');
Route::get('/my-applications-rooms', [StudentController::class, 'getAppliedBedSpaces'])->name('student_applied.rooms');
Route::get('/room-bed-spaces-student/{id}', [StudentController::class, 'getBedSpaces'])->name('room-bed-space-student');

Route::get('/academic-period/{academicPeriodId}/programs', [AcademicPeriodController::class, 'getProgramsByAcademicPeriod'])->name('academic-periods.getProgramsByAcademicPeriod');

Route::get('/academic-periods/{academicPeriodIds}/programs', [AcademicPeriodController::class, 'getProgramsByAcademicPeriods'])->name('academic-periods.getProgramsByAcademicPeriods');

Route::resource('statements', StatementController::class);

Route::resource('invoices', InvoiceController::class);
Route::post('custom-invoice', [InvoiceController::class, 'customInvoice'])->name('invoices.custom-invoice');
Route::post('batch-invoice-process', [InvoiceController::class, 'batchInvoicing'])->name('invoices.batchInvoicing');
Route::post('student-invoice-process', [InvoiceController::class, 'invoice'])->name('invoices.invoice');

Route::resource('enrollments', EnrollmentController::class);
Route::get('summary', [StudentRegistrationController::class, 'summary'])->name('registration.summary');
//sponsors
Route::resource('sponsors', SponsorController::class);
// Residency Routes
Route::get('/countries/{countryId}/provinces/', [CountryController::class, 'getProvincesByCountry'])->name('provinces.getProvincesByCountry');
Route::resource('countries', CountryController::class);
Route::get('/countries', CountriesIndex::class)->name('countries.index');

Route::get('/provinces/{provinceId}/towns', [ProvinceController::class, 'getTownsByProvince'])->name('towns.getTownsByProvince');
Route::resource('provinces', ProvinceController::class);
Route::get('/provinces', ProvincesIndex::class)->name('provinces.index');

Route::resource('towns', TownController::class);
Route::get('/towns', TownsIndex::class)->name('towns.index');

// System Settings Routes
Route::resource('settings', SettingsController::class);

// my account
Route::group(['prefix' => 'my_account'], function () {
    Route::get('/', [MyAccountController::class, 'index'])->name('my_account');
    // Route::put('/', 'MyAccountController@update_profile')->name('my_account.update');
    Route::put('/change_password', [MyAccountController::class, 'change_pass'])->name('my_account.change_pass');
});

Route::put('reset-password', [StudentController::class, 'resetAccountPassword'])->name('students.resetAccountPassword');

// Student-specific Routes
Route::group(['prefix' => 'student'], function () {
    Route::get('/profile', [UsersStudentController::class, 'profile'])->name('student.profile');
    Route::get('/finances', [UsersStudentController::class, 'finances'])->name('student.finances');

    Route::group(['prefix' => 'help'], function () {
        Route::get('/how-to-make-payments', [UsersStudentController::class, 'howToMakePayments'])->name('students.help.make-payments');
    });

    // Financial statements generator/download routes
    Route::get('/invoices/{invoice}/download/', [InvoiceController::class, 'downloadInvoice'])->name('student.download-invoice');
    Route::get('/invoices/{student}/export/', [InvoiceController::class, 'exportInvoices'])->name('student.export-invoices');
    Route::get('/statements/{invoice}/download/', [StatementController::class, 'downloadStatement'])->name('student.download-statement');
    Route::get('/statements/{student}/export/', [StatementController::class, 'exportStatements'])->name('student.export-statements');
});

// Student Grades Routes
Route::post('/grades/{id}/edit', [GradeContoller::class, 'update'])->name('grades.edit');
