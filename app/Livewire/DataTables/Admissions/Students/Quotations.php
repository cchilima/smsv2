<?php

namespace App\Livewire\DataTables\Admissions\Students;

use App\Repositories\Admissions\StudentRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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

final class Quotations extends PowerGridComponent
{
    use WithExport;
    public $student;
    public string $tableName = 'StudentQuotationsTable';
    public bool $deferLoading = true;

    protected StudentRepository $studentRepo;

    public function boot(): void
    {
        $this->studentRepo = app(StudentRepository::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();

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
        return $this->studentRepo->getQuotationsByStudent($this->student->id, false);
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
            ->add('created_at', function ($row) {
                return $row->created_at->format('F j Y, H:i');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Student', 'student_id'),
            Column::make('Academic Period', 'period.name'),
            Column::make('Raised By', 'raised_by'),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Actions')

        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions($row): array
    {
        return [
            Button::add('action')
                ->bladeComponent('table-actions.admissions.student-quotations', ['row' => $row])
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
