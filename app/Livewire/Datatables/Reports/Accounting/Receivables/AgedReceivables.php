<?php

namespace App\Livewire\Datatables\Reports\Accounting\Receivables;

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
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class AgedReceivables extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'AgedReceivablesReportTable';
    public bool $deferLoading = true;

    public string $toDate;

    protected AccountsReportsRepository $accountsReportsRepo;

    public function boot(): void
    {
        $this->accountsReportsRepo = app(AccountsReportsRepository::class);
    }

    public function datasource(): ?Collection
    {
        return collect($this->accountsReportsRepo->Aged_Receivables($this->toDate));
    }

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->sortBy('id', 'desc');

        return [
            Exportable::make('aged-receivables-report-export')
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
            ->add('last_receipt_days')
            ->add('payment_percentage', fn($entry) => Number::format((int)$entry->payment_percentage, 2) . '%')
            ->add('balance', fn($entry) => Number::format((int)$entry->balance, 2))
            ->add('formatted_days');
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

            Column::make('Payment %', 'payment_percentage')
                ->sortable(),

            Column::make('Balance', 'balance')
                ->sortable(),

            Column::make('Days Aging', 'formatted_days', 'last_receipt_days')
                ->sortable(),
        ];
    }
}
