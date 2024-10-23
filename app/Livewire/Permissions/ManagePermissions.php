<?php

namespace App\Livewire\Permissions;

use Livewire\Component;
use App\Models\Users\UserType;
use Spatie\Permission\Models\Permission;


class ManagePermissions extends Component
{

    public $userTypes;
    public $permissions;
    public $selectedUserType = null;
    public $selectedPermissions = [];

    public function mount()
    {
        $this->userTypes = UserType::all();
        $this->permissions = Permission::all()->groupBy('category')->toArray();
    }

    public function updatedSelectedUserType($userTypeId)
    {
        // Fetch the current permissions for the selected user type
        $userType = UserType::find($userTypeId);
        $this->selectedPermissions = $userType->permissions->pluck('name')->toArray();
    }

    public function savePermissions()
    {
        $userType = UserType::find($this->selectedUserType);
        $permissions = Permission::whereIn('name', $this->selectedPermissions)->get();

        // Sync permissions to the user type
        $userType->permissions()->sync($permissions->pluck('id')->toArray());

        session()->flash('message', 'Permissions updated successfully!');
    }

    public function render()
    {
        return view('livewire.permissions.manage-permissions');
    }
}
