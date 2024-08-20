<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Repositories\Accounting\{InvoiceRepository, CreditNoteRepository};

class ApproveCreditNotes extends Component
{
    public $creditNoteRepo;

    public function boot(CreditNoteRepository $creditNoteRepo)
    {
        $this->creditNoteRepo = $creditNoteRepo;
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.accounting.approve-credit-notes');
    }
}
