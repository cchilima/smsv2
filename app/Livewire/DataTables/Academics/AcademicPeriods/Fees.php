<?php

namespace App\Livewire\DataTables\Academics\AcademicPeriods;

use App\Models\Academics\AcademicPeriodFee;
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

final class Fees extends PowerGridComponent
{
    use WithExport;

    public string $academicPeriodId;
    public bool $deferLoading = true;

    protected AcademicPeriodRepository $academicPeriodRepo;

    public function boot(): void
    {
        $this->academicPeriodRepo = app(AcademicPeriodRepository::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('academic-period-fees-export')
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
        return $this->academicPeriodRepo->getAPFeeInformation($this->academicPeriodId, false);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('amount')
            ->add('fee.name')
            ->add('status', function ($row) {
                return $row->status ? 'Published' : 'Not Published';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Amount', 'amount')
                ->sortable()
                ->searchable(),

            Column::make('Fee', 'fee.name'),

            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(AcademicPeriodFee $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.academics.academic-periods.fees', ['row' => $row])
        ];
    }
}
