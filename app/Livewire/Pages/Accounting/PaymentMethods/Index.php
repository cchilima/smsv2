<?php

namespace App\Livewire\Pages\Accounting\PaymentMethods;

use App\Helpers\Qs;
use App\Traits\CanRefreshDataTable;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    use CanRefreshDataTable;

    public function mount()
    {
        Gate::allowIf(Qs::userIsAdministrative());
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.accounting.payment-methods.index');
    }
}
