<?php

namespace App\Livewire\Datatables\Academics\AcademicPeriods;

use Illuminate\Database\Eloquent\Builder;

final class ClosedAcademicPeriods extends Base
{
    public string $tableName = 'ClosedAcademicPeriodsTable';

    public function datasource(): Builder
    {
        return $this->academicPeriodRepo->getAllClosedQuery();
    }
}
