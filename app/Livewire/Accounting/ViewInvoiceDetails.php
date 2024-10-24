<?php

namespace App\Livewire\Accounting;

use Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Mail\ApproveCreditNote;
use Illuminate\Support\Facades\Mail;
use App\Repositories\Accounting\{InvoiceRepository, CreditNoteRepository};
use App\Traits\CanShowAlerts;

class ViewInvoiceDetails extends Component
{
    use CanShowAlerts;

    protected $listeners = ['refresh' => '$refresh'];

    private InvoiceRepository $invoiceRepo;
    private CreditNoteRepository $creditNoteRepo;

    public $invoice;
    public $creditNoteItems = [];
    public $checkedItems = [];
    public $creditNoteReason = '';

    public function mount($invoice_id)
    {
        $this->invoice = $this->invoiceRepo->getInvoice($invoice_id);
    }

    public function boot(InvoiceRepository $invoiceRepo, CreditNoteRepository $creditNoteRepo)
    {
        $this->invoiceRepo = $invoiceRepo;
        $this->creditNoteRepo = $creditNoteRepo;
    }

    public function addItem($detail_id, $amount)
    {

        // check if reason has been given
        if ($this->creditNoteReason == '') {

            $this->flash('Specify a reason for raising credit note.', 'error');

            return $this->dispatch('refresh');
        }

        // Check if the item is already in the array
        $exists = array_filter($this->creditNoteItems, function ($item) use ($detail_id) {
            return $item['invoice_detail_id'] === $detail_id;
        });

        if ($exists) {
            // If item exists, remove it
            $this->creditNoteItems = array_filter($this->creditNoteItems, function ($item) use ($detail_id) {
                return $item['invoice_detail_id'] !== $detail_id;
            });

            // Remove from checkedItems array
            $this->checkedItems = array_diff($this->checkedItems, [$detail_id]);

            $this->reset(['creditNoteReason']);
        } else {
            // If item does not exist, add it
            $this->creditNoteItems[] = [
                'invoice_id' => $this->invoice->id,
                'invoice_detail_id' => $detail_id,
                'amount' => $amount,
                'issued_by' => Auth::user()->id,
                'reason' => $this->creditNoteReason
            ];

            $this->reset(['creditNoteReason']);
        }
    }

    public function raise()
    {
        try {

            // check if invoice is not in negative

            $invoice_payments = $this->invoiceRepo->paymentAgainstInvoice($this->invoice->id);
            $invoice_total = $this->invoiceRepo->invoiceTotal($this->invoice->id);

            // credit notes total
            $creditNotesTotal = 0;

            foreach ($this->creditNoteItems as $item) {
                $creditNotesTotal += $item['amount'];
            }

            if ($creditNotesTotal > ($invoice_total -  $invoice_payments)) {
                return $this->flash('Raise a new invoice first', 'error');
            }

            $created = $this->creditNoteRepo->raiseCreditNote($this->creditNoteItems);

            $this->reset(['creditNoteReason', 'creditNoteItems']);

            if ($created) {
                // Queue the email
                Mail::to('stembo@zut.edu.zm')->bcc(['stembo@zut.edu.zm'])->queue(new ApproveCreditNote());

                $this->mount($this->invoice->id);

                return $this->flash('Credit note created successfully');
            } else {
                return $this->flash('Credit note already exists', 'error');
            }
        } catch (\Throwable $th) {
            $this->flash('Failed to create credit note: ' . $th->getMessage(), 'error');
        }
    }


    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.accounting.view-invoice-details');
    }
}
