<?php

namespace App\Livewire\Pages\Accounting\PaymentMethods;

use App\Traits\CanRefreshDataTable;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    use CanRefreshDataTable;

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.accounting.payment-methods.index');
    }
}
