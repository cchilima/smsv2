<?php

namespace App\Livewire\Datatables\Admissions\Students;

use App\Models\Accounting\Invoice;
use App\Repositories\Admissions\StudentRepository;
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

final class Invoices extends PowerGridComponent
{
    use WithExport;

    public string $studentId;
    public string $tableName = 'StudentInvoicesTable';
    public bool $deferLoading = true;

    protected StudentRepository $studentRepo;

    public function boot(): void
    {
        $this->studentRepo = app(StudentRepository::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->sortBy('created_at', 'desc');

        return [
            Exportable::make('student-invoices-export')
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
        return $this->studentRepo->getInvoicesByStudent($this->studentId, false);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('raised_by', function ($row) {
                $user = $row->raisedBy;
                return $user->first_name . ' ' . $user->last_name;
            })
            ->add('period.name')
            ->add('total', function ($row) {
                return number_format($row->details->sum('amount'), 2, '.', ',');
            })
            ->add('created_at_formatted', function ($row) {
                return $row->created_at->format('F j Y, H:i');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Invoice ID', 'id'),
            Column::make('Raised By', 'raised_by'),

            Column::make('Academic Period', 'period.name'),

            Column::make('Total', 'total'),

            Column::make('Created At', 'created_at_formatted')
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(Invoice $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.admissions.student-invoices', ['row' => $row])
        ];
    }
}
