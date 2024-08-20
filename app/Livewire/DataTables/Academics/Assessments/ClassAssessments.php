<?php

namespace App\Livewire\Datatables\Academics\Assessments;

use App\Models\Academics\AcademicPeriod;
use App\Models\Academics\AcademicPeriodClass;
use App\Repositories\Academics\AcademicPeriodClassRepository;
use App\Repositories\Academics\AcademicPeriodRepository;
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

final class ClassAssessments extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'ClassAssessmentsTable';
    public bool $deferLoading = true;

    protected AcademicPeriodClassRepository $academicPeriodClassRepo;
    protected AcademicPeriodRepository $academicPeriodRepo;

    public function boot(): void
    {
        $this->academicPeriodClassRepo = app(AcademicPeriodClassRepository::class);
        $this->academicPeriodRepo = app(AcademicPeriodRepository::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('class-assessments-export')
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
        return $this->academicPeriodClassRepo->getAcademicPeriodClassDataTableQuery();
    }

    public function relationSearch(): array
    {
        return [
            'course' => ['name', 'code'],
            'academicPeriod' => 'name'
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('course.name')
            ->add('course.code')
            ->add('assessmentDetails', function (AcademicPeriodClass $row) {
                if ($row->class_assessments->count() > 0) {
                    return Blade::render(
                        '<x-table-fields.academics.assessments.class-assessments :row=$row />',
                        ['row' => $row]
                    );
                }

                return "No assessments";
            })
            ->add('academicPeriod.name');
    }

    public function columns(): array
    {
        return [
            Column::make('Course', 'course.name'),

            Column::make('Code', 'course.code'),

            Column::make('Academic Period', 'academicPeriod.name'),

            Column::make('Assessment Details', 'assessmentDetails'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('academicPeriod.name', 'academic_period_id')
                ->dataSource($this->academicPeriodRepo->getAcadeperiodClassAssessments())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    public function actions(AcademicPeriodClass $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.academics.assessments.class-assessments', ['row' => $row])
        ];
    }
}
