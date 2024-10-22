<?php

namespace App\Livewire\Datatables\Settings;

use App\Models\Sponsorship\Sponsor;
use App\Repositories\Sponsor\SponsorsRepository;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

use App\Helpers\Qs;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
class Sponsors extends PowerGridComponent
{

    use WithExport;

    public string $tableName = 'SponsorsTable';
    public string $sortField = 'name';
    public bool $deferLoading = true;

    protected SponsorsRepository $sponsorsRepo;

    public function boot(): void
    {
        $this->sponsorsRepo = app(SponsorsRepository::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('sponsors-export')
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
        return $this->sponsorsRepo->getAll(false);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('description')
            ->add('email')
            ->add('phone');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Description', 'description')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Phone', 'phone')
                ->sortable()
                ->searchable(),

            Column::action('Action')
                ->visibleInExport(false)
        ];
    }


    public function actions(Sponsor $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.settings.sponsors', ['row' => $row])
        ];
    }
}
