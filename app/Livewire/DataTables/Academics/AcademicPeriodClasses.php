<?php

namespace App\Livewire\Datatables\Academics;

use App\Models\Academics\AcademicPeriodClass;
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

final class AcademicPeriodClasses extends PowerGridComponent
{
    use WithExport;

    public bool $deferLoading = true;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('academic-periods-classes-export')
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
        return AcademicPeriodClass::query();
    }

    public function relationSearch(): array
    {
        return [
            'course' => ['name'],
            'instructor' => ['first_name', 'last_name'],
            'academicPeriod' => ['code'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('academicPeriod.code')
            ->add('course.name')
            ->add('instructor', function ($academicPeriodClass) {
                return $academicPeriodClass->instructor->first_name . ' ' . $academicPeriodClass->instructor->last_name;
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Academic Period', 'academicPeriod.code')
                ->searchable(),

            Column::make('Course', 'course.name')
                ->searchable(),

            Column::make('Instructor', 'instructor'),
            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(AcademicPeriodClass $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.academics.academic-period-classes', ['row' => $row])
        ];
    }
}
