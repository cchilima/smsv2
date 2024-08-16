<?php

namespace App\Livewire\DataTables\Accommodation;

use App\Models\Accomodation\Hostel;
use App\Repositories\Accommodation\HostelRepository;
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

final class Hostels extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'Hostels';
    public bool $deferLoading = true;
    public string $sortField = 'hostel_name';

    protected HostelRepository $hostelRepo;

    public function boot(): void
    {
        $this->hostelRepo = new HostelRepository();
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('hostels-export')
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
        return $this->hostelRepo->getAll('hostel_name', false);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('hostel_name')
            ->add('location');
    }

    public function columns(): array
    {
        return [
            Column::make('Hostel name', 'hostel_name')
                ->sortable()
                ->searchable(),

            Column::make('Location', 'location')
                ->sortable()
                ->searchable(),

            Column::action('Action')
                ->visibleInExport(false)
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(Hostel $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.accommodation.hostels', ['row' => $row])
        ];
    }
}
