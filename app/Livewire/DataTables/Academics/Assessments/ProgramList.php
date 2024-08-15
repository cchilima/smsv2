<?php

namespace App\Livewire\DataTables\Academics\Assessments;

use App\Models\Academics\Department;
use App\Models\Academics\Program;
use App\Repositories\Academics\AcademicPeriodClassRepository;
use App\Repositories\Academics\DepartmentsRepository;
use App\Repositories\Academics\QualificationsRepository;
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

final class ProgramList extends PowerGridComponent
{
    use WithExport;

    public $academicPeriodId;
    public bool $deferLoading = true;

    protected AcademicPeriodClassRepository $academicPeriodClassRepo;
    protected DepartmentsRepository $departmentRepo;
    protected QualificationsRepository $qualificationRepo;

    public function boot(): void
    {
        $this->academicPeriodClassRepo = new AcademicPeriodClassRepository();
        $this->departmentRepo = new DepartmentsRepository();
        $this->qualificationRepo = new QualificationsRepository();
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
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
        return $this->academicPeriodClassRepo->academicProgramStudents($this->academicPeriodId, false);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('code')
            ->add('name')
            ->add('department.name')
            ->add('qualification.name')
            ->add('students_count');
    }

    public function columns(): array
    {
        return [

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Code', 'code')
                ->sortable()
                ->searchable(),

            Column::make('Qualification', 'qualification.name'),

            Column::make('Department', 'department.name'),

            Column::make('Students', 'students_count'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('department.name', 'department_id')
                ->dataSource($this->departmentRepo->getAll())
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::select('qualification.name', 'qualification_id')
                ->dataSource($this->qualificationRepo->getAll())
                ->optionLabel('name')
                ->optionValue('id'),


        ];
    }

    public function actions(Program $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.academics.assessments.programs-list', ['row' => $row])
        ];
    }
}
