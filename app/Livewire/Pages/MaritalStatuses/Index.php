<?php

namespace App\Livewire\Pages\MaritalStatuses;

use App\Traits\RefreshesDataTable;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    use RefreshesDataTable;

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.marital-statuses.index');
    }
}
