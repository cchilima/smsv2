<?php

namespace App\Exports;

use App\Models\Admissions\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InvoicesExport implements FromView
{
    protected $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function view(): View
    {
        return view('templates.spreadsheets.all-invoices', [
            'invoices' => $this->student->invoices
        ]);
    }
}
