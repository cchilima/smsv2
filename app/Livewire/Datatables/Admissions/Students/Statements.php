<?php

namespace App\Livewire\Datatables\Admissions\Students;

use App\Models\Admissions\Student;
use App\Repositories\Admissions\StudentRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class Statements extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'StudentStatementsTable';
    public bool $deferLoading = true;
    public Student $student;

    public function datasource(): ?Collection
    {
        // Merge invoices, credit notes, and non-invoiced receipts into one collection
        $transactions = collect();

        foreach ($this->student->invoices as $invoice) {
            // Add the invoice as a debit transaction
            $transactions->push([
                'id' => 'INV' . $invoice->id,
                'type' => 'invoice',
                'date' => $invoice->created_at,
                'description' => 'Invoice',
                'debit' => $invoice->details->sum('amount'),
                'credit' => 0,
                'balance' => 0,
            ]);

            // Add associated receipts as credit transactions
            foreach ($invoice->receipts as $receipt) {
                $transactions->push([
                    'id' => 'RCT' . $receipt->id,
                    'type' => 'receipt',
                    'date' => $receipt->created_at,
                    'description' => 'Receipt',
                    'debit' => 0,
                    'credit' => $receipt->amount,
                    'balance' => 0,
                ]);
            }

            // Add associated credit notes as credit transactions
            foreach ($invoice->creditNotes as $creditNote) {
                $transactions->push([
                    'id' => 'CN' . $creditNote->id,
                    'type' => 'credit_note',
                    'date' => $creditNote->created_at,
                    'description' => 'Credit Note',
                    'debit' => 0,
                    'credit' => $creditNote->amount,
                    'balance' => 0,
                ]);
            }
        }

        // Add non-invoiced receipts to the collection
        foreach ($this->student->receiptsNonInvoiced as $receipt) {
            $transactions->push([
                'id' => 'RCT' . $receipt->id,
                'type' => 'receipt',
                'date' => $receipt->created_at,
                'description' => 'Receipt',
                'debit' => 0,
                'credit' => $receipt->amount,
                'balance' => 0,
            ]);
        }

        // Calculate balance
        $balance = 0;

        // Sort transactions by date and convert to array
        $transactions = $transactions->sortBy('date')->values()->toArray();

        // Loop over transactions and add a balance to each transaction
        foreach ($transactions as &$transaction) {
            $balance += $transaction['debit'] - $transaction['credit'];
            $transaction['balance'] = $balance;
        }

        // Return the collection of transactions
        return collect($transactions);
    }

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->sortBy('date');

        return [
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')

            ->add('date', function ($entry) {
                return Carbon::parse($entry->date)->format('d M Y, H:i');
            })

            ->add('description')

            ->add('debit', function ($entry) {
                return number_format((int)$entry->debit, 2);
            })

            ->add('credit', function ($entry) {
                return number_format((int)$entry->credit, 2);
            })

            ->add('balance', function ($entry) {
                return number_format((int)$entry->balance, 2);
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Date', 'date')->sortable(),
            Column::make('Reference', 'id')->sortable(),
            Column::make('Description', 'description')->sortable(),
            Column::make('Debit', 'debit')->sortable(),
            Column::make('Credit', 'credit')->sortable(),
            Column::make('Balance', 'balance')->sortable(),
        ];
    }
}
