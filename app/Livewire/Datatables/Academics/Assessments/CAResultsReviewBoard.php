<?php

namespace App\Livewire\Datatables\Academics\Assessments;

use App\Models\Academics\AcademicPeriod;
use App\Models\Academics\CourseLevel;
use App\Models\Academics\PeriodType;
use App\Models\Academics\Program;
use App\Repositories\Academics\ClassAssessmentsRepo;
use App\Traits\CanShowAlerts;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class CAResultsReviewBoard extends PowerGridComponent
{
    use CanShowAlerts;

    public CourseLevel $level;
    public Program $program;
    public AcademicPeriod $academicPeriod;
    public PeriodType $periodType;

    public string $tableName = 'ExamResultsReviewBoardTable';
    public bool $deferLoading = true;

    protected ClassAssessmentsRepo $classAssessmentsRepo;

    public function boot(): void
    {
        $this->classAssessmentsRepo = app(ClassAssessmentsRepo::class);
    }

    public function datasource(): ?Collection
    {
        $studentGrades = $this->classAssessmentsRepo->getCaGradesDatatableCollection(
            $this->level->id,
            $this->program->id,
            $this->academicPeriod->id
        );

        return $studentGrades;
    }

    #[On('publish-results.{tableName}')]
    public function publishAllResults(array $studentIds)
    {
        try {
            if (empty($studentIds)) throw new \Exception('No student IDs provided');

            $this->classAssessmentsRepo->publishGrades(
                $studentIds,
                $this->academicPeriod->id,
                $this->periodType->id
            );

            return $this->flash(message: 'Results published successfully');
        } catch (\Throwable $th) {
            return $this->flash(message: 'Failed to publish results', type: 'error');

            // dd($th->getMessage());
        }
    }

    public function header(): array
    {
        return [
            Button::add('publish-all-results')
                ->class('btn btn-primary')
                ->slot('Publish All (' . $this->datasource()->count() . ')')
                ->dispatch('publish-results.' . $this->tableName, [
                    'studentIds' => $this->datasource()->pluck('id')->toArray()
                ]),

            Button::add('publish-selected-results')
                ->class('btn btn-primary ' . (count($this->checkboxValues) > 0 ? '' : 'disabled'))
                ->slot('Publish Selected (' . count($this->checkboxValues) . ')')
                ->dispatch('publish-results.' . $this->tableName, [
                    'studentIds' => $this->checkboxValues
                ]),
        ];
    }

    public function setUp(): array
    {
        $this->showCheckBox();
        // $this->sortBy('name');
        $this->periodType = $this->academicPeriod->period_types;

        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage(3, [3, 5, 10, 25, 50, 100, 0])
                ->showRecordCount(),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('entry', function ($entry) {
                return Blade::render('<x-table-fields.academics.assessments.ca-results-review-board :entry="$entry" :academicPeriod="$academicPeriod" :program="$program" :level="$level" />', [
                    'entry' => $entry,
                    'academicPeriod' => $this->academicPeriod,
                    'program' => $this->program,
                    'level' => $this->level,
                ]);
            });
    }

    public function columns(): array
    {
        return [
            Column::make('', 'entry'),

            Column::make('Name', 'name')
                ->hidden()
                ->searchable()
        ];
    }
}
