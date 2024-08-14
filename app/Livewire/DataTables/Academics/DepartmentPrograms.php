<?php

namespace App\Livewire\DataTables\Academics;

use App\Models\Academics\Program;
use App\Repositories\Academics\DepartmentsRepository;
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

final class DepartmentPrograms extends PowerGridComponent
{
    use WithExport;

    public string $departmentId;
    public string $sortField = 'name';
    public bool $deferLoading = true;

    protected DepartmentsRepository $departmentRepo;

    public function boot(): void
    {
        $this->departmentRepo = new DepartmentsRepository();
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('department-programs-export')
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
        return $this->departmentRepo->getProgramsByDepartment($this->departmentId, false);
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
            ->add('qualification.name');
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

            Column::action('Action')
        ];
    }

    public function actions(Program $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.academics.assessments.department-programs', ['row' => $row])
        ];
    }
}
