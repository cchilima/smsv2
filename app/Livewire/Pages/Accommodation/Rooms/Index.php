<?php

namespace App\Livewire\Pages\Accommodation\Rooms;

use App\Helpers\Qs;
use App\Repositories\Accommodation\HostelRepository;
use App\Traits\CanRefreshDataTable;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    use CanRefreshDataTable;

    protected HostelRepository $hostelRepo;

    public function boot()
    {
        $this->hostelRepo = app(HostelRepository::class);
    }

    public function mount()
    {
        Gate::allowIf(Qs::userIsTeamSA());
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.accommodation.rooms.index', [
            'hostels' => $this->hostelRepo->getAll()
        ]);
    }
}
