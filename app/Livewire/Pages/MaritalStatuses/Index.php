<?php

namespace App\Livewire\Pages\MaritalStatuses;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    // public $listeners = ['refresh' => 'refresh'];

    public function refreshTable(string $tableName)
    {
        $this->dispatch('pg:eventRefresh-' . $tableName);
        // $this->render();
    }

    #[Layout('components.layouts.app-bootstrap')]
    public function render()
    {
        return view('livewire.pages.marital-statuses.index');
    }
}
