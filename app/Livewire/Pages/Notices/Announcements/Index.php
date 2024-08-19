<?php

namespace App\Livewire\Pages\Notices\Announcements;

use App\Helpers\Qs;
use App\Repositories\Users\UserRepository;
use App\Traits\CanRefreshDataTable;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    use CanRefreshDataTable;

    protected UserRepository $userRepo;

    public function boot()
    {
        $this->userRepo = new UserRepository();
    }

    public function mount()
    {
        Gate::allowIf(Qs::userIsTeamSAT() || Qs::userIsSuperAdmin());
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.notices.announcements.index', [
            'userTypes' => $this->userRepo->getUserTypes(),
        ]);
    }
}
