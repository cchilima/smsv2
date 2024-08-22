<?php

namespace App\Livewire\Accounting;

use Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Repositories\Accounting\{InvoiceRepository, CreditNoteRepository};

class ApproveCreditNotes extends Component
{

    private CreditNoteRepository $creditNoteRepo;
   
    public $approver;
    public $creditNote;

    public function mount()
    {
        $user = Auth::user();
        $this->approver = $user->userType->name;

    }

    public function boot(CreditNoteRepository $creditNoteRepo)
    {
        $this->creditNoteRepo = $creditNoteRepo;
    }


    public function approve($credit_note_id)
    {

        if($this->creditNoteRepo->approveCreditNote($credit_note_id, $this->approver)){

            return $this->dispatch('approved');

        } else {
            return $this->dispatch('approval-failed');
        }
    }

    #[Layout('components.layouts.administrator')]
    public function render()
    {
       // dd($this->creditNoteRepo->getCreditNotes($this->approver));
        return view('livewire.accounting.approve-credit-notes', ['credit_notes' => $this->creditNoteRepo->getCreditNotes($this->approver)]);
    }
}
