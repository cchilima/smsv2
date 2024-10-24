<?php

namespace App\Livewire\Datatables\Academics\Assessments;

use App\Models\Academics\AcademicPeriodClass;
use App\Models\Academics\AssessmentType;
use App\Models\Enrollments\Enrollment;
use App\Repositories\Academics\AssessmentTypesRepo;
use App\Repositories\Academics\ClassAssessmentsRepo;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class StudentList extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'AssessmentsStudentListTable';
    public $class;
    public $assessID;
    // public bool $deferLoading = true;
    public $assessmentName;

    public ?AcademicPeriodClass $class_ass;

    protected ClassAssessmentsRepo $classaAsessmentRepo;
    protected AssessmentTypesRepo $assessmentTypeRepo;

    public function boot(): void
    {
        $this->classaAsessmentRepo = app(ClassAssessmentsRepo::class);
        $this->assessmentTypeRepo = app(AssessmentTypesRepo::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->assessmentName = $this->assessmentTypeRepo->find($this->assessID)?->name;

        return [
            Exportable::make('assessments-student-list-export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return $this->classaAsessmentRepo->getClassAssessmentsDatatableQuery($this->class, $this->assessID);
    }

    public function relationSearch(): array
    {
        return [
            'user' => [
                'first_name',
                'last_name'
            ]
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('student', function (Enrollment $row) {
                return $row->student->user->first_name . ' ' . $row->student->user->last_name;
            })
            ->add('student_id')
            // ->add('assesment.assessment_type.name')
            ->add('assessmentType', function () {
                return $this->assessmentName ?? '-';
            })
            ->add('class.academicPeriod.name')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [

            Column::make('Student', 'student'),
            Column::make('Student ID', 'student_id')->searchable(),
            Column::make('Assessment Type', 'assessmentType'),
            Column::make('Academic Period', 'class.academicPeriod.name'),
            Column::action('Enter Grade (%)')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(Enrollment $row): array
    {
        return [
            Button::add('enter-grade')
                ->bladeComponent('table-fields.academics.assessments.student-list', [
                    'row' => $row,
                    'class_ass' => $this->class_ass
                ])
        ];
    }
}
