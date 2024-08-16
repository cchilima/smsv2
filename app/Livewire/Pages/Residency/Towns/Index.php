<?php

namespace App\Livewire\Pages\Residency\Towns;

use App\Repositories\Residency\CountryRepository;
use App\Traits\CanRefreshDataTable;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    use CanRefreshDataTable;

    protected CountryRepository $countryRepo;

    public function boot()
    {
        $this->countryRepo = new CountryRepository();
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.residency.towns.index', [
            'countries' => $this->countryRepo->getAll(),
        ]);
    }
}
