<?php

namespace App\Livewire\DataTables\Residency;

use App\Models\Residency\Town;
use App\Repositories\Residency\CountryRepository;
use App\Repositories\Residency\ProvinceRepository;
use App\Repositories\Residency\TownRepository;
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

final class Towns extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'Towns';
    public bool $deferLoading = true;
    public string $sortField = 'name';

    protected TownRepository $townRepo;
    protected ProvinceRepository $provinceRepo;
    protected CountryRepository $countryRepo;

    public function boot(): void
    {
        $this->townRepo = new TownRepository();
        $this->provinceRepo = new ProvinceRepository();
        $this->countryRepo = new CountryRepository();
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('towns-export')
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
        return $this->townRepo->getAll(false);
    }

    public function relationSearch(): array
    {
        return [
            'province.country' => 'country'
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')

            ->add('province', function ($row) {
                return $row->province?->name ?? 'Other';
            })

            ->add('country', function ($row) {
                return $row->province?->country?->country ?? 'Other';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Province', 'province', 'province_id'),

            Column::make('Country', 'country'),

            // TODO: Hide all action columns from export
            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('province', 'province_id')
                ->dataSource($this->provinceRepo->getAll())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    public function actions(Town $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.residency.provinces', ['row' => $row])
        ];
    }
}
