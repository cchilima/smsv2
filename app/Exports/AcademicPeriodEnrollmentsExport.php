<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AcademicPeriodEnrollmentsExport implements FromView
{
    protected $academicPeriods;

    public function __construct(array $academicPeriods)
    {
        $this->academicPeriods = $academicPeriods;
    }

    public function view(): View
    {
        return view('templates.spreadsheets.academic-period-enrollments', [
            'academicPeriods' => $this->academicPeriods
        ]);
    }
}
