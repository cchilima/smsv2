<?php

namespace App\Livewire\Permissions;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class AddPermission extends Component
{

    public $name;

    // Validation rules
    protected $rules = [
        'name' => 'required|min:3|unique:permissions,name',
    ];

    // Save a new permission
    public function createPermission()
    {
        $this->validate();  // Validate input

        // Create the permission
        Permission::create(['name' => $this->name]);

        // Reset the form
        $this->reset('name');

        // Provide success feedback
        session()->flash('message', 'Permission successfully created!');
    }


    public function render()
    {
        return view('livewire.permissions.add-permission');
    }
}
