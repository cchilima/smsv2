<?php

namespace App\Livewire\Pages\Academics\Prerequisites;

use App\Repositories\Academics\CourseRepository;
use App\Traits\CanRefreshDataTable;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    use CanRefreshDataTable;

    protected CourseRepository $courseRepo;

    public function boot()
    {
        $this->courseRepo = new CourseRepository();
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.academics.prerequisites.index', [
            'courses' => $this->courseRepo->getAll(),
        ]);
    }
}
