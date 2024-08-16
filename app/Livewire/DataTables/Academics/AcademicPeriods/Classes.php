<?php

namespace App\Livewire\DataTables\Academics\AcademicPeriods;

use App\Models\Academics\AcademicPeriodClass;
use App\Repositories\Academics\AcademicPeriodClassRepository;
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

final class Classes extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'AcademicPeriodClasses';
    public string $academicPeriodId;
    public bool $deferLoading = true;

    protected AcademicPeriodClassRepository $academicPeriodClassRepo;

    public function boot(): void
    {
        $this->academicPeriodClassRepo = new AcademicPeriodClassRepository();
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
        return $this->academicPeriodClassRepo->getAllAcClasses(
            $this->academicPeriodId,
            'academic_period_id',
            false
        );
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('course.name')
            ->add('course.code')

            ->add('instructor', function ($row) {
                return $row->instructor->first_name . ' ' . $row->instructor->last_name;
            })

            ->add('students', function ($row) {
                return $row->enrollments->count();
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Code', 'course.code'),
            Column::make('Course', 'course.name'),
            Column::make('Instructor', 'instructor'),
            Column::make('Students', 'students'),

            Column::action('Action')
        ];
    }

    public function actions(AcademicPeriodClass $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.academics.academic-periods.classes', [
                    'row' => $row,
                    'academicPeriodId' => $this->academicPeriodId,
                ])
        ];
    }
}
