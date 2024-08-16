<?php

namespace App\Livewire\DataTables\Academics\Assessments;

use App\Repositories\Academics\ClassAssessmentsRepo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class PublishResults extends PowerGridComponent
{
    public string $tableName = 'PublishResults';
    public string $academicPeriodId;
    public bool $deferLoading = true;
    public string $sortField = 'name';

    protected ClassAssessmentsRepo $classaAsessmentRepo;

    public function boot(): void
    {
        $this->classaAsessmentRepo = new ClassAssessmentsRepo();
    }

    public function datasource(): ?Collection
    {
        return collect($this->classaAsessmentRepo->publishAvailablePrograms($this->academicPeriodId));
    }

    public function setUp(): array
    {
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

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('program', function ($entry) {
                return $entry->name;
            })
            ->add('qualification', function ($entry) {
                return $entry->qualifications;
            })
            ->add('students', function ($entry) {
                return $entry->students;
            })
            ->add('status', function ($entry) {
                return $entry->status = 0 ? 'Not Published' : 'Published';
            })

            ->add('user-actions', function ($entry) {
                return Blade::render('<x-table-actions.academics.assessments.publish-results :entry="$entry" :periodId="$periodId" />', [
                    'entry' => $entry,
                    'periodId' => $this->academicPeriodId
                ]);
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Program', 'program')
                ->searchable(),

            Column::make('Qualification', 'qualification'),

            Column::make('Students', 'students'),

            Column::make('Status', 'status'),

            Column::make('Action', 'user-actions')
                ->visibleInExport(false)
        ];
    }
}
