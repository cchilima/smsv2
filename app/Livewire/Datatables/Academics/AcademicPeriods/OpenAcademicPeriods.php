<?php

namespace App\Livewire\Datatables\Academics\AcademicPeriods;

use Illuminate\Database\Eloquent\Builder;

final class OpenAcademicPeriods extends Base
{
    public string $tableName = 'OpenAcademicPeriodsTable';

    public function datasource(): Builder
    {
        return $this->academicPeriodRepo->getAllOpenedQuery();
    }
}
