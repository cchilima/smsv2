<?php

namespace App\Livewire\Datatables\Settings;

use App\Helpers\Qs;
use App\Models\Profile\MaritalStatus;
use App\Repositories\Profile\MaritalStatusRepository;
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

final class MaritalStatuses extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'MaritalStatusesTable';
    public string $sortField = 'status';
    public bool $deferLoading = true;

    protected MaritalStatusRepository $maritalStatusRepo;

    public function boot(): void
    {
        $this->maritalStatusRepo = app(MaritalStatusRepository::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('marital-statuses-export')
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
        return $this->maritalStatusRepo->getAll('status', false);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('status')
            ->add('description');
    }

    public function columns(): array
    {
        return [
            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),

            Column::make('Description', 'description')
                ->sortable()
                ->searchable(),

            Column::action('Action')
                ->visibleInExport(false)
        ];
    }


    public function actions(MaritalStatus $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.settings.marital-statuses', ['row' => $row])
        ];
    }
}
