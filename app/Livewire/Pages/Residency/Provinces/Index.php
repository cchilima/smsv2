<?php

namespace App\Livewire\Pages\Residency\Provinces;

use App\Helpers\Qs;
use App\Repositories\Residency\CountryRepository;
use App\Traits\CanRefreshDataTable;
use Illuminate\Support\Facades\Gate;
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

    public function mount()
    {
        Gate::allowIf(Qs::userIsTeamSA() || Qs::userIsSuperAdmin());
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.residency.provinces.index', [
            'countries' => $this->countryRepo->getAll(),
        ]);
    }
}
