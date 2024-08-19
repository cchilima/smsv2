<?php

namespace App\Livewire\Pages\Admissions\Applications;

use App\Repositories\Accounting\PaymentMethodRepository;
use App\Traits\CanRefreshDataTable;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    use CanRefreshDataTable;

    protected PaymentMethodRepository $paymentMethodRepo;

    public function boot()
    {
        $this->paymentMethodRepo = new PaymentMethodRepository();
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.admissions.applications.index', [
            'paymentMethods' => $this->paymentMethodRepo->getAll()
        ]);
    }
}
