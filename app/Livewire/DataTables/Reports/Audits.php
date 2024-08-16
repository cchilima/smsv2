<?php

namespace App\Livewire\DataTables\Reports;

use OwenIt\Auditing\Models\Audit;
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

final class Audits extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'AuditsTable';
    public bool $deferLoading = true;

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->sortBy('created_at');
        $this->sortDirection = 'desc';

        return [
            Exportable::make('audits-export')
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
        return Audit::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('user', function (Audit $row) {
                return $row->user->first_name . ' ' . $row->user->last_name;
            })
            ->add('event')
            ->add('old_values', function (Audit $row) {
                return json_encode($row->old_values);
            })
            ->add('new_values', function (Audit $row) {
                return json_encode($row->new_values);
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('User', 'user'),

            Column::make('Old Values', 'old_values'),

            Column::make('New Values', 'new_values'),

            Column::make('Created At', 'created_at')
                ->sortable()
                ->searchable(),

        ];
    }

    public function filters(): array
    {
        return [];
    }
}
