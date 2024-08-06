<?php

namespace App\Livewire\Datatables\Academics\AcademicPeriods;

use App\Models\Academics\AcademicPeriod;
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

class Base extends PowerGridComponent
{
    use WithExport;

    protected AcademicPeriodRepository $academicPeriodRepo;

    public function boot(): void
    {
        $this->academicPeriodRepo = new AcademicPeriodRepository();
    }

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->sortBy('name');

        return [
            Exportable::make('academic-periods-export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }


    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('code')
            ->add('ac_start_date')
            ->add('ac_end_date')
            ->add('period_types.name');
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

            Column::make('Start Date', 'ac_start_date')
                ->sortable(),

            Column::make('End Date', 'ac_end_date')
                ->sortable(),

            Column::make('Period Type', 'period_types.name'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            // Filter::boolean('code', 'code')
        ];
    }

    public function actions(AcademicPeriod $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.academics.academic-periods', ['row' => $row])
        ];
    }
}
