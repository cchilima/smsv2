<?php

namespace App\Livewire\Datatables\Admissions\Students;

use App\Models\Admissions\Student;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Users\UserPersonalInfoRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
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

final class Photos extends PowerGridComponent
{
    public string $tableName = 'StudentPhotosTable';
    public bool $deferLoading = true;

    protected UserPersonalInfoRepository $userPersonalInfoRepo;
    protected StudentRepository $studentRepo;

    public function boot(): void
    {
        $this->userPersonalInfoRepo = app(UserPersonalInfoRepository::class);
        $this->studentRepo = app(StudentRepository::class);
    }

    public function setUp(): array
    {
        $this->sortBy('id', 'desc');

        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function relationSearch(): array
    {
        return [
            'user' => ['first_name', 'last_name']
        ];
    }

    public function datasource(): Builder
    {
        return $this->studentRepo->getAll(executeQuery: false);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')

            ->add('student', function ($row) {
                return $row->user->first_name . ' ' . $row->user->last_name;
            })

            ->add('program.name')

            ->add('photo', function ($row) {
                return Blade::render('components.table-fields.admissions.students.photos', [
                    'row' => $row,
                ]);
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Student ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Student', 'student'),

            Column::make('Program', 'program.name')
                ->sortable(),

            Column::make('Photo', 'photo'),
        ];
    }
}
