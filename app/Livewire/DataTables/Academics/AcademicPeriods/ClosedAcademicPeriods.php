<?php

namespace App\Livewire\Datatables\Academics\AcademicPeriods;

use App\Models\Academics\AcademicPeriod;
use App\Repositories\Academics\AcademicPeriodRepository;
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

final class ClosedAcademicPeriods extends Base
{
    public function datasource(): Builder
    {
        return $this->academicPeriodRepo->getAllClosedQuery();
    }
}
