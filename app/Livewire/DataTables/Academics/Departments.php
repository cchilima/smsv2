<?php

namespace App\Livewire\DataTables\Academics;

use App\Models\Academics\Department;
use App\Repositories\Academics\SchooolRepository;
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

final class Departments extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'DepartmentsTable';
    protected SchooolRepository $schoolRepo;
    public bool $deferLoading = true;


    public function boot(): void
    {
        $this->schoolRepo = app(SchooolRepository::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('departments-export')
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
        return Department::query();
    }

    public function relationSearch(): array
    {
        return [
            'school' => ['name']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('school', function ($row) {
                return $row->school->name;
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('School', 'school'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('school', 'school_id')
                ->dataSource($this->schoolRepo->getAll())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    public function actions(Department $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.academics.departments', ['row' => $row])
        ];
    }
}
