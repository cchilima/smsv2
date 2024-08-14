<?php

namespace App\Livewire\Accounting;

use Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Repositories\Accounting\{InvoiceRepository, CreditNoteRepository};

class ViewInvoiceDetails extends Component
{

    private InvoiceRepository $invoiceRepo;
    private CreditNoteRepository $creditNoteRepo;
    
    public $invoice;
    public $creditNoteItems = [];
    public $checkedItems = [];
    public $currentSection = 'details';

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
        } else {
            // If item does not exist, add it
            $this->creditNoteItems[] = [
                'invoice_id' => $this->invoice->id,
                'invoice_detail_id' => $detail_id,
                'amount' => $amount,
                'issued_by' => Auth::user()->id
            ];
        }
    }
    


    public function raise()
    {

       // dd($this->creditNoteItems);
        try {

            $created = $this->creditNoteRepo->raiseCreditNote($this->creditNoteItems);
            $this->creditNoteItems = [];

           return $created ? $this->dispatch('credit-note-created') : $this->dispatch('credit-note-exists');

        } catch (\Throwable $th) {
            return $this->dispatch('credit-note-failed');
        }
    }

    public function setSection($section)
    {
        $this->currentSection = $section;
    }
    

    #[Layout('components.layouts.administrator')]
    public function render()
    {
        return view('livewire.accounting.view-invoice-details');
    }
}
