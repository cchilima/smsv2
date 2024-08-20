<?php

namespace App\Livewire\Pages\Accommodation\Bookings;

use App\Helpers\Qs;
use App\Repositories\Accommodation\HostelRepository;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    protected HostelRepository $hostelRepo;

    public function boot()
    {
        $this->hostelRepo = new HostelRepository();
    }

    public function mount()
    {
        Gate::allowIf(Qs::userIsTeamSAT());
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.accommodation.bookings.index', [
            'hostels' => $this->hostelRepo->getAll(),
        ]);
    }
}
