<?php

namespace App\Livewire\DataTables\Academics;

use App\Models\Academics\CourseLevel;
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

final class CourseLevels extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'CourseLevelsTable';
    public string $sortField = 'name';
    public bool $deferLoading = true;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('course-levels-export')
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
        return CourseLevel::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function actions(CourseLevel $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.academics.course-levels', ['row' => $row])
        ];
    }
}
