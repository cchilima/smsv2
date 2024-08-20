<?php

namespace App\Livewire\Datatables\Residency;

use App\Models\Residency\Country;
use App\Repositories\Residency\CountryRepository;
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

final class Countries extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'CountriesTable';
    public string $sortField = 'country';
    public bool $deferLoading = true;

    protected CountryRepository $countryRepo;

    public function boot(): void
    {
        $this->countryRepo = app(CountryRepository::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('countries-export')
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
        return $this->countryRepo->getAll(false);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('country')
            ->add('alpha_2_code')
            ->add('alpha_3_code')
            ->add('nationality')
            ->add('dialing_code');
    }

    public function columns(): array
    {
        return [
            Column::make('Country', 'country')
                ->sortable()
                ->searchable(),

            Column::make('Alpha 2 Code', 'alpha_2_code')
                ->sortable()
                ->searchable(),

            Column::make('Alpha 3 Code', 'alpha_3_code')
                ->sortable()
                ->searchable(),

            Column::make('Nationality', 'nationality')
                ->sortable()
                ->searchable(),

            Column::make('Dialing Code', 'dialing_code')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(Country $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.residency.countries', ['row' => $row])
        ];
    }
}
