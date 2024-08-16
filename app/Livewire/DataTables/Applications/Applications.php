<?php

namespace App\Livewire\DataTables\Applications;

use App\Models\Applications\Applicant;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class Applications extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'Applications';
    public bool $deferLoading = true;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('applications-export')
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
        return Applicant::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('applicant_code')
            ->add('nrc')
            ->add('passport')
            ->add('first_name')
            ->add('middle_name')
            ->add('last_name')
            ->add('gender')
            ->add('date_of_birth')
            ->add('fee_paid', function (Applicant $row) {
                return $row->payment->sum('amount');
            })
            ->add('status')
            ->add('program.name')
            ->add('study_mode.name')
            ->add('intake.name')
            ->add('application_date');
    }

    public function columns(): array
    {
        return [
            Column::make('First Name', 'first_name')
                ->sortable()
                ->searchable(),

            Column::make('Last Name', 'last_name')
                ->sortable()
                ->searchable(),

            Column::make('Application Code', 'applicant_code')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),

            Column::make('Fee Paid (K)', 'fee_paid'),

            Column::make('NRC', 'nrc')
                ->sortable()
                ->searchable(),

            Column::make('Passport', 'passport')
                ->sortable()
                ->searchable(),

            Column::make('Date of Birth', 'date_of_birth')
                ->sortable(),

            Column::make('Gender', 'gender')
                ->sortable()
                ->searchable(),


            Column::make('Program', 'program.name'),
            Column::make('Study Mode', 'study_mode.name'),
            Column::make('Intake', 'intake.name'),
            Column::make('Application Date', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            // Filter::enumSelect('status', 'Status')
            //     ->dataSource([
            //         'incomplete' => 'Incomplete',
            //         'pending' => 'Pending',
            //         'complete' => 'Complete',
            //         'rejected' => 'Rejected',
            //         'accepted' => 'Accepted'
            //     ])
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Applicant $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.applications.applications', ['row' => $row])
        ];
    }


    public function actionRules($row): array
    {
        return [
            // Rule::rows()
            //     ->when(fn ($row) => $row->status === 'rejected')
            //     ->setAttribute('class', 'text-danger'),
        ];
    }
}
