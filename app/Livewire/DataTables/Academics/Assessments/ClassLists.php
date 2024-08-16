<?php

namespace App\Livewire\DataTables\Academics\Assessments;

use App\Models\Academics\AcademicPeriodClass;
use App\Repositories\Academics\AcademicPeriodClassRepository;
use App\Repositories\Academics\AcademicPeriodRepository;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

final class ClassLists extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'AssessmentsClassLists';
    public bool $deferLoading = true;

    protected AcademicPeriodClassRepository $academicPeriodClassRepo;
    protected AcademicPeriodRepository $academicPeriodRepo;

    public function boot(): void
    {
        $this->academicPeriodClassRepo = new AcademicPeriodClassRepository();
        $this->academicPeriodRepo = new AcademicPeriodRepository();
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('assessments-class-lists-export')
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
        return $this->academicPeriodClassRepo->getAssessmentClassListDataTableQuery();
    }

    public function relationSearch(): array
    {
        return [
            'course' => [
                'name',
                'code'
            ]
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('course.name')
            ->add('course.code')
            ->add('instructor', function (AcademicPeriodClass $row) {
                return $row->instructor->first_name . ' ' . $row->instructor->last_name;
            })
            ->add('academicPeriod.name')
            ->add('studentCount', function (AcademicPeriodClass $row) {
                return $row->enrollments->count();
            })
            ->add('gradedStudentCount', function (AcademicPeriodClass $row) {
                return $row->enrollments->filter(function ($enrollment) use ($row) {
                    return $enrollment->student->grades->contains(function ($grade) use ($row) {
                        return $grade->course_id === $row->course_id
                            && $grade->academic_period_id === $row->academic_period_id;
                    });
                })->count();
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Course', 'course.name'),
            Column::make('Code', 'course.code'),
            Column::make('Instructor', 'instructor'),
            Column::make('Academic Period', 'academicPeriod.name'),
            Column::make('Students', 'studentCount'),
            Column::make('Graded Students', 'gradedStudentCount'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('academicPeriod.name', 'academic_period_id')
                ->dataSource($this->academicPeriodRepo->getAllOpenedAc())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }
    public function actions(AcademicPeriodClass $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.academics.assessments.class-lists', ['row' => $row])
        ];
    }
}
