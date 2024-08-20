<?php

namespace App\Livewire\Pages\Accommodation\BedSpaces;

use App\Helpers\Qs;
use App\Repositories\Accommodation\RoomRepository;
use App\Traits\CanRefreshDataTable;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    use CanRefreshDataTable;

    protected RoomRepository $roomRepo;

    public function boot()
    {
        $this->roomRepo = new RoomRepository();
    }

    public function mount()
    {
        Gate::allowIf(Qs::userIsTeamSA());
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.accommodation.bed-spaces.index', [
            'rooms' => $this->roomRepo->getAll(),
        ]);
    }
}
