<?php

namespace App\Livewire\Datatables\Reports\Accounting\Revenue;

use App\Models\Accounting\Invoice;
use App\Repositories\Academics\AcademicPeriodRepository;
use App\Repositories\Academics\ProgramsRepository;
use App\Repositories\Reports\Accounts\AccountsReportsRepository;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
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

final class InvoicesSummary extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'InvoicesSummaryTable';
    public bool $deferLoading = true;

    public string $fromDate;
    public string $toDate;

    protected ProgramsRepository $programRepo;
    protected AcademicPeriodRepository $academicPeriodRepo;
    protected AccountsReportsRepository $accountsReportsRepo;

    public function boot(): void
    {
        $this->programRepo = app(ProgramsRepository::class);
        $this->academicPeriodRepo = app(AcademicPeriodRepository::class);
        $this->accountsReportsRepo = app(AccountsReportsRepository::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->sortBy('created_at', 'desc');

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
        return $this->accountsReportsRepo->RevenueAnalysisSummary($this->fromDate, $this->toDate, false);
    }

    public function relationSearch(): array
    {
        return [
            'program' => ['name', 'code'],
            'period' => ['name', 'code'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('student_id')

            ->add('studentName', function ($row) {
                return $row->student->user->first_name . ' ' . $row->student->user->last_name;
            })

            ->add('program.code')
            ->add('period.name')

            ->add('details_sum_amount', function ($row) {
                $amount = $row->details_sum_amount;
                return $amount ? Number::format($amount, 2) : 0;
            })

            ->add('date', function ($row) {
                return Carbon::parse($row->created_at)->format('d M Y, H:i');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Invoide ID', 'id'),

            Column::make('Student ID', 'student_id'),

            Column::make('Student', 'studentName'),

            Column::make('Program', 'program.code'),

            Column::make('Amount', 'details_sum_amount'),

            Column::make('Date', 'date', 'created_at')
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('program.code')
                ->operators(['is'])
                ->filterRelation('program', 'code'),

            Filter::select('period.name', 'academic_period_id')
                ->dataSource($this->academicPeriodRepo->getAll())
                ->optionLabel('code')
                ->optionValue('id'),
        ];
    }
}
