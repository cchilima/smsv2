<?php

namespace App\Livewire\Accounting;

use Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Repositories\Accounting\{QuotationRepository};

class ViewQuotationDetails extends Component
{

    private QuotationRepository $quotationRepo;

    public $quotation;

    public function mount($quotation_id)
    {
        $this->quotation = $this->quotationRepo->getQuotation($quotation_id);
    }

    public function boot(QuotationRepository $quotationRepo)
    {
        $this->quotationRepo = $quotationRepo;
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.accounting.view-quotation-details');
    }
}
