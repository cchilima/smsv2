<?php

namespace App\Livewire\DataTables\Academics;

use App\Models\Academics\ProgramCourses as Courses;
use App\Repositories\Academics\CourseLevelsRepository;
use App\Repositories\Academics\ProgramsRepository;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
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

final class ProgramCourses extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'ProgramCoursesTable';
    public string $programId;
    public bool $deferLoading = true;

    protected ProgramsRepository $programRepo;
    protected CourseLevelsRepository $courseLevelRepo;

    public function boot(): void
    {
        $this->programRepo = new ProgramsRepository();
        $this->courseLevelRepo = new CourseLevelsRepository();
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('program-courses-export')
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
        return $this->programRepo->getCoursesByProgram($this->programId, false);
    }

    public function relationSearch(): array
    {
        return [
            'course' => 'name'
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('courseLevel.name')
            ->add('course.name')
            ->add('course.code')
            ->add('prerequisites', function ($row) {
                return Blade::render(
                    '<x-table-fields.academics.prerequisites :row=$row />',
                    ['row' => $row->course]
                );
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Course', 'course.name'),
            Column::make('Code', 'course.code'),
            Column::make('Course Level', 'courseLevel.name'),
            Column::make('Prerequisites', 'prerequisites'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('courseLevel.name', 'course_level_id')
                ->dataSource($this->programRepo->getCourseLevelsByProgram($this->programId))
                ->optionLabel('name')
                ->optionValue('id')
        ];
    }
}
