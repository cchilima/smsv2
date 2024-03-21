<?php

namespace App\Exports;

use App\Models\Accounting\Invoice;
use App\Models\Admissions\Student;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoicesExport implements FromQuery, WithMapping, WithHeadings
{
    protected $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    // /**
    //  * @return \Illuminate\Support\Collection
    //  */
    // public function collection()
    // {
    //     return $this->student->invoices;
    // }

    public function headings(): array
    {
        return [
            '#',
            'Fee Type',
            'Amount'
        ];
    }

    public function query()
    {
        return Invoice::where('student_id', $this->student->id);
    }

    public function map($invoices): array
    {
        $array = [];

        foreach ($invoices->get() as $i => $invoice) {
            $details = $invoice->details;

            foreach ($details as $index => $detail) {
                // Add invoice title
                if ($index === 0) {
                    $array[] = [
                        'invoiceTitle' => 'INVOICE - ' . $invoice->created_at->format('d-m-Y')
                    ];
                }

                // Add invoice rows/details
                $array[] = [
                    '#' => ++$index,
                    'feeType' => $detail->fee->name,
                    'amount' => $detail->amount
                ];

                // Add blank row/space between invoices
                if ($index === count($details)) {
                    $array[] = [];
                }
            }
        }

        return $array;
    }
}
