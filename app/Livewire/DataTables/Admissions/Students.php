<?php

namespace App\Livewire\DataTables\Admissions;

use App\Models\Admissions\Student;
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

final class Students extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('students-export')
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
        return Student::query()->with(['user', 'program', 'level'])->orderBy('created_at', 'desc');
    }

    public function relationSearch(): array
    {
        return [
            'user' => [
                'first_name',
                'last_name',
            ]
        ];
    }


    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('user.first_name')
            ->add('user.last_name')
            ->add('program.name')
            ->add('admission_year')
            ->add('level.name');
    }

    public function columns(): array
    {
        return [
            Column::make('Student ID', 'id')
                ->sortable()
                ->searchable(),

            Column::make('First Name', 'user.first_name')
                ->sortable()
                ->searchable(),

            Column::make('Last Name', 'user.last_name')
                ->sortable()
                ->searchable(),

            Column::make('Program', 'program.name')
                ->sortable(),

            Column::make('Admission Year', 'admission_year')
                ->sortable(),

            Column::make('Year of Study', 'level.name')
                ->sortable(),


            Column::action('Action')
                ->visibleInExport(visible: false)
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(Student $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.admissions.students', ['row' => $row->user])
        ];
    }
}
