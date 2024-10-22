<?php

namespace App\Livewire\Accounting;

use Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Repositories\Accounting\{InvoiceRepository, CreditNoteRepository};
use App\Traits\CanShowAlerts;

class ApproveCreditNotes extends Component
{
    use CanShowAlerts;

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

        try {
            $this->creditNoteRepo->approveCreditNote($credit_note_id, $this->approver);
            return $this->flash('Credit note approved successfully');
        } catch (\Throwable $th) {
            return $this->flash('Failed to approve credit note: ' . $th->getMessage(), 'error');
        }
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.accounting.approve-credit-notes', ['credit_notes' => $this->creditNoteRepo->getCreditNotes($this->approver)]);
    }
}
