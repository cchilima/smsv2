<?php

namespace App\Livewire\DataTables\Accommodation;

use App\Enums\Settings\TrueFalseEnum;
use App\Models\Accomodation\BedSpace;
use App\Repositories\Accommodation\BedSpaceRepository;
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

final class BedSpaces extends PowerGridComponent
{
    use WithExport;

    public bool $deferLoading = true;
    public string $sortField = 'bed_number';

    protected BedSpaceRepository $bedSpaceRepo;
    protected RoomRepository $roomRepo;

    public function boot(): void
    {
        $this->bedSpaceRepo = new BedSpaceRepository();
        $this->roomRepo = new RoomRepository();
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('bed-spaces-export')
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
        return BedSpace::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('room', function ($row) {
                return $row->room->room_number;
            })
            ->add('bed_number')
            ->add('is_available', function ($row) {
                return $row->is_available === 'true' ? 'Yes' : 'No';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Room', 'room'),
            Column::make('Bed Space Number', 'bed_number')
                ->sortable()
                ->searchable(),

            Column::make('Is Available', 'is_available')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('room', 'room_id')
                ->dataSource($this->roomRepo->getAll())
                ->optionLabel('room_number')
                ->optionValue('id'),

            Filter::enumSelect('is_available', 'is_available')
                ->dataSource(TrueFalseEnum::cases())
                ->optionLabel('name')
                ->optionValue('name'),
        ];
    }

    public function actions(BedSpace $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.accommodation.bed-spaces', ['row' => $row])
        ];
    }
}
