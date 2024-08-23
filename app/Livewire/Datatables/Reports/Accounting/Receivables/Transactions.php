<?php

namespace App\Livewire\Datatables\Reports\Accounting\Receivables;

use App\Models\Accounting\Receipt;
use App\Repositories\Academics\ProgramsRepository;
use App\Repositories\Accounting\PaymentMethodRepository;
use App\Repositories\Reports\Accounts\AccountsReportsRepository;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
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

final class Transactions extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'TransactionsReportTable';
    public bool $deferLoading = true;

    public string $fromDate;
    public string $toDate;

    protected AccountsReportsRepository $accountsReportsRepo;
    protected PaymentMethodRepository $paymentMethodRepo;
    protected ProgramsRepository $programRepo;

    public function boot(): void
    {
        $this->accountsReportsRepo = app(AccountsReportsRepository::class);
        $this->paymentMethodRepo = app(PaymentMethodRepository::class);
        $this->programRepo = app(ProgramsRepository::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->sortBy('created_at', 'desc');

        return [
            Exportable::make('transactions-report-export')
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
        return $this->accountsReportsRepo->Transactions($this->fromDate, $this->toDate, false);
    }

    public function relationSearch(): array
    {
        return [
            'student.user' => [
                'first_name',
                'last_name'
            ],

            'student.program' => [
                'code',
                'name',
            ]
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('student_id')
            ->add('student.program.code')

            ->add('student', function ($row) {
                return $row->student->user->first_name . ' ' . $row->student->user->last_name;
            })

            ->add('amount', function ($row) {
                return Number::format($row->amount, 2);
            })

            ->add('paymentMethod.name')

            ->add('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d M Y, H:i');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Receipt ID', 'id'),

            Column::make('Student ID', 'student_id')
                ->sortable(),

            Column::make('Student', 'student'),
            Column::make('Program', 'student.program.code'),

            Column::make('Amount', 'amount')
                ->sortable(),

            Column::make('Payment Method', 'paymentMethod.name'),

            Column::make('Created at', 'created_at')
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('paymentMethod.name', 'payment_method_id')
                ->dataSource($this->paymentMethodRepo->getAll())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }
}
