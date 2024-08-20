<?php

namespace App\Livewire\Datatables\Academics;

use App\Models\Academics\Program;
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

final class Programs extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'ProgramsTable';
    protected DepartmentsRepository $departmentRepo;
    protected QualificationsRepository $qualificationRepo;

    public bool $deferLoading = true;
    public string $sortField = 'name';

    public function boot(): void
    {
        $this->departmentRepo = app(DepartmentsRepository::class);
        $this->qualificationRepo = app(QualificationsRepository::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('programs-export')
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
        return Program::query();
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
            ->add('department', function ($row) {
                return $row->department->name;
            })
            ->add('qualification', function ($row) {
                return $row->qualification->name;
            })
            ->add('description');
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

            Column::make('Department', 'department'),
            Column::make('Qualification', 'qualification'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('department', 'department_id')
                ->dataSource($this->departmentRepo->getAll())
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::select('qualification', 'qualification_id')
                ->dataSource($this->qualificationRepo->getAll())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    public function actions(Program $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.academics.programs', ['row' => $row])
        ];
    }
}
