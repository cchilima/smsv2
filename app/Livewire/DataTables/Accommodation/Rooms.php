<?php

namespace App\Livewire\DataTables\Accommodation;

use App\Enums\Settings\GenderEnum;
use App\Models\Accomodation\Room;
use App\Repositories\Accommodation\HostelRepository;
use App\Repositories\Accommodation\RoomRepository;
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

final class Rooms extends PowerGridComponent
{
    use WithExport;

    public bool $deferLoading = true;
    public string $sortField = 'room_number';

    protected RoomRepository $roomRepo;
    protected HostelRepository $hostelRepo;

    public function boot(): void
    {
        $this->roomRepo = new RoomRepository();
        $this->hostelRepo = new HostelRepository();
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('rooms-export')
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
        return $this->roomRepo->getAll(false);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('hostel', function ($row) {
                return $row->hostel->hostel_name;
            })
            ->add('room_number')
            ->add('capacity')
            ->add('gender');
    }

    public function columns(): array
    {
        return [
            Column::make('Hostel', 'hostel'),
            Column::make('Room Number', 'room_number')
                ->sortable()
                ->searchable(),

            Column::make('Capacity', 'capacity')
                ->sortable()
                ->searchable(),

            Column::make('Gender', 'gender')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('hostel', 'hostel_id')
                ->dataSource($this->hostelRepo->getAll('hostel_name'))
                ->optionLabel('hostel_name')
                ->optionValue('id'),

            Filter::enumSelect('gender', 'gender')
                ->dataSource(GenderEnum::cases())
                ->optionLabel('name')
                ->optionValue('name'),
        ];
    }

    public function actions(Room $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.accommodation.rooms', ['row' => $row])
        ];
    }
}
