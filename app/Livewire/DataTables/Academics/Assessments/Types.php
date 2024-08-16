<?php

namespace App\Livewire\DataTables\Academics\Assessments;

use App\Models\Academics\AssessmentType;
use App\Repositories\Academics\AssessmentTypesRepo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
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

final class Types extends PowerGridComponent
{
    public string $tableName = 'AssessmentTypesTable';
    public bool $deferLoading = true;
    public string $sortField = 'name';

    protected AssessmentTypesRepo $assessmentTypeRepo;

    public function boot(): void
    {
        $this->assessmentTypeRepo = new AssessmentTypesRepo();
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('assessment-types-export')
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
        return $this->assessmentTypeRepo->getAll('name', false);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->searchable()
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function actions(AssessmentType $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.academics.assessments.types', ['row' => $row])
        ];
    }
}
