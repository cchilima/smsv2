<?php

namespace App\Livewire\Pages\Academics\Departments;

use App\Repositories\Academics\SchooolRepository;
use App\Traits\CanRefreshDataTable;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    use CanRefreshDataTable;

    protected SchooolRepository $schoolRepo;

    public function boot()
    {
        $this->schoolRepo = new SchooolRepository();
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.academics.departments.index', [
            'schools' => $this->schoolRepo->getAll(),
        ]);
    }
}
