<?php

namespace App\Livewire\DataTables\Academics\AcademicPeriods;

use App\Models\Academics\Program;
use App\Repositories\Academics\AcademicPeriodClassRepository;
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

final class RunningPrograms extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'AcademicPeriodRunningPrograms';
    public string $sortField = 'name';
    public bool $deferLoading = true;
    public string $academicPeriodId;

    protected AcademicPeriodClassRepository $academicPeriodClassRepo;

    public function boot(): void
    {
        $this->academicPeriodClassRepo = new AcademicPeriodClassRepository();
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('academic-period-running-programs-export')
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
            ->add('code')
            ->add('name')
            ->add('department.name')
            ->add('qualification.name')
            ->add('students_count');
    }

    public function columns(): array
    {
        return [
            Column::make('Code', 'code')
                ->sortable()
                ->searchable(),

            Column::make('Name', 'name')
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
        return [];
    }

    public function actions(Program $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.academics.academic-periods.running-programs', [
                    'row' => $row,
                    'academicPeriodId' => $this->academicPeriodId,
                ])
        ];
    }
}
