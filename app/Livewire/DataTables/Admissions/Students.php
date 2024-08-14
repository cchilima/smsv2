<?php

namespace App\Livewire\DataTables\Admissions;

use App\Models\Admissions\Student;
use App\Repositories\Academics\AcademicPeriodRepository;
use App\Repositories\Academics\CourseLevelsRepository;
use App\Repositories\Academics\ProgramsRepository;
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

final class Students extends PowerGridComponent
{
    use WithExport;

    public bool $deferLoading = true;

    protected StudentRepository $studentRepo;
    protected ProgramsRepository $programRepo;
    protected CourseLevelsRepository $levelRepo;
    protected AcademicPeriodRepository $academicPeriodRepo;

    public function boot(): void
    {
        $this->studentRepo = new StudentRepository();
        $this->programRepo = new ProgramsRepository();
        $this->levelRepo = new CourseLevelsRepository();
        $this->academicPeriodRepo = new AcademicPeriodRepository();
    }

    public function setUp(): array
    {
        $this->sortBy('id', 'desc');
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
        return $this->studentRepo->getAll(false);
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
            ->add('level.name')
            ->add('academic_info.academic_period.name');
    }

    public function columns(): array
    {
        return [
            Column::make('Student ID', 'id')
                ->sortable()
                ->searchable(),

            Column::make('First Name', 'user.first_name'),

            Column::make('Last Name', 'user.last_name'),

            Column::make('Program', 'program.name'),

            Column::make('Admission Year', 'admission_year'),

            Column::make('Year of Study', 'level.name'),

            // Column::make('Academic Period', 'academic_info.academic_period.name'),

            Column::action('Action')
                ->visibleInExport(visible: false)
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('program.name', 'program_id')
                ->dataSource($this->programRepo->getAll())
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::inputText('admission_year', 'admission_year')
                ->operators(['is', 'is_not', 'contains', 'starts_with', 'ends_with']),

            Filter::select('level.name', 'course_level_id')
                ->dataSource($this->levelRepo->getAll())
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    public function actions(Student $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.admissions.students', ['row' => $row->user])
        ];
    }
}
