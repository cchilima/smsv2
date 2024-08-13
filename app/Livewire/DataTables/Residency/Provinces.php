<?php

namespace App\Livewire\DataTables\Residency;

use App\Models\Residency\Province;
use App\Repositories\Residency\CountryRepository;
use App\Repositories\Residency\ProvinceRepository;
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

final class Provinces extends PowerGridComponent
{
    use WithExport;

    public bool $deferLoading = true;
    public string $sortField = 'name';

    protected ProvinceRepository $provinceRepo;
    protected CountryRepository $countryRepo;

    public function boot(): void
    {
        $this->provinceRepo = new ProvinceRepository();
        $this->countryRepo = new CountryRepository();
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
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
        return $this->provinceRepo->getAll(false);
    }

    public function relationSearch(): array
    {
        return [
            'country' => [
                'country'
            ]
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('country', function ($row) {
                return $row->country->country;
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Country', 'country', 'country_id')
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('country', 'country_id')
                ->dataSource($this->countryRepo->getAll())
                ->optionLabel('country')
                ->optionValue('id')
        ];
    }


    public function actions(Province $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.residency.provinces', ['row' => $row])
        ];
    }
}
