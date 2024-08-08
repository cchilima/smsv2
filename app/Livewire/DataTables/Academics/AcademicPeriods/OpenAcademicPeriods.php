<?php

namespace App\Livewire\Datatables\Academics\AcademicPeriods;

use Illuminate\Database\Eloquent\Builder;

final class OpenAcademicPeriods extends Base
{
    public function datasource(): Builder
    {
        return $this->academicPeriodRepo->getAllOpenedQuery();
    }
}
