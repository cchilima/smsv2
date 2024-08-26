<?php

namespace App\Livewire\Pages\Admissions\Students\Uploads;

use App\Traits\CanRefreshDataTable;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Photos extends Component
{
    use CanRefreshDataTable;

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.admissions.students.uploads.photos');
    }
}
