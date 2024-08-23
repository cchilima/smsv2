<?php

namespace App\Livewire\Datatables\Reports\Accounting\General;

use App\Repositories\Reports\Accounts\AccountsReportsRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class StudentList extends PowerGridComponent
{
    protected AccountsReportsRepository $accountsReportsRepo;

    public string $tableName = 'StudentListReportTable';
    public bool $deferLoading = true;

    public string $fromDate;
    public string $toDate;

    public function boot(): void
    {
        $this->accountsReportsRepo = app(AccountsReportsRepository::class);
    }

    public function datasource(): ?Collection
    {
        return collect($this->accountsReportsRepo->StudentList($this->fromDate, $this->toDate));
    }

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->sortBy('id', 'desc');

        return [
            Exportable::make('accounting-student-list-export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('study_mode')
            ->add('program')
            ->add('level')
            ->add('gender')
            ->add('payment_percentage', fn($entry) => Number::format((int)$entry->payment_percentage, 2) . '%')
            ->add('balance', fn($entry) => Number::format((int)$entry->balance, 2));
    }

    public function columns(): array
    {
        return [
            Column::make('Student ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Name', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Study Mode', 'study_mode')
                ->sortable(),

            Column::make('Program', 'program')
                ->sortable()
                ->searchable(),

            Column::make('Level', 'level')
                ->sortable(),

            Column::make('Gender', 'gender')
                ->sortable(),

            Column::make('Payment %', 'payment_percentage')
                ->sortable(),

            Column::make('Balance', 'balance')
                ->sortable(),
        ];
    }
}
