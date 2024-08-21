<?php

namespace App\Livewire\Datatables\Admissions\Students;

use App\Models\Accounting\Receipt;
use App\Repositories\Accounting\ReceiptRepository;
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

final class PaymentHistory extends PowerGridComponent
{
    use WithExport;

    public string $studentId;
    public string $tableName = 'StudentReceiptsTable';
    public bool $deferLoading = true;

    protected ReceiptRepository $receiptRepo;

    public function boot(): void
    {
        $this->receiptRepo = app(ReceiptRepository::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->sortBy('created_at', 'desc');

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return $this->receiptRepo->getReceiptsByStudent($this->studentId, false);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('amount', function ($row) {
                return number_format((int)$row->amount, 2);
            })

            ->add('paymentMethod.name')

            ->add('date', function ($row) {
                return Carbon::parse($row->created_at)->format('d M Y, H:i');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Amount', 'amount')
                ->sortable()
                ->searchable(),

            Column::make('Payment Method', 'paymentMethod.name'),

            Column::make('Date', 'date', 'created_at')
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [];
    }
}
