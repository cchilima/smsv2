<?php

namespace App\Livewire\DataTables\Accommodation;

use App\Models\Accomodation\Booking;
use App\Repositories\Accommodation\BookingRepository;
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

final class Bookings extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'Bookings';
    public bool $deferLoading = true;

    protected BookingRepository $bookingRepo;

    public function boot(): void
    {
        $this->bookingRepo = app(BookingRepository::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('room-bookings-export')
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
        return $this->bookingRepo->getOpenBookings(false);
    }

    public function relationSearch(): array
    {
        return [
            'student.user' => [
                'first_name',
                'last_name'
            ]
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('student_id')
            ->add('student', function ($row) {
                return $row->student->user->first_name . ' ' . $row->student->user->last_name;
            })
            ->add('room', function ($row) {
                return $row->bedSpace->room->room_number;
            })
            ->add('hostel', function ($row) {
                return $row->bedSpace->room->hostel->hostel_name;
            })
            ->add('bedSpace', function ($row) {
                return $row->bedSpace->bed_number;
            })
            ->add('booking_date')
            ->add('expiration_date');
    }

    public function columns(): array
    {
        return [
            Column::make('Student', 'student'),

            Column::make('Student ID', 'student_id')
                ->sortable()
                ->searchable(),

            Column::make('Hostel', 'hostel'),
            Column::make('Room', 'room'),
            Column::make('Bed Space', 'bedSpace'),

            Column::make('Booking Date', 'booking_date')
                ->sortable(),

            Column::make('Expiration Date', 'expiration_date')
                ->sortable(),

            Column::action('Action')
                ->visibleInExport(false)
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(Booking $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.accommodation.bookings', ['row' => $row])
        ];
    }
}
