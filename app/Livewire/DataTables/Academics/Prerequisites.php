<?php

namespace App\Livewire\Datatables\Academics;

use App\Models\Academics\Course;
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

final class Prerequisites extends PowerGridComponent
{
    use WithExport;

    public bool $deferLoading = true;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('prerequisites-export')
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
        return Course::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            // ->add('prerequisites.name');
            ->add('prerequisites', function ($row) {
                return Blade::render(
                    '<x-table-fields.academics.prerequisites :row=$row />',
                    ['row' => $row]
                );
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Course', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Prerequisites', 'prerequisites'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(Course $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.academics.prerequisites', ['row' => $row])
        ];
    }
}
