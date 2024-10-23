<div class="container">
    <h5>Assign Permissions to User Types</h5>

    <a class="btn btn-small" href="{{ route('add-permissions') }}">add permission</a>

    <div class="input-field">
        <select class="browser-default" wire:model.live="selectedUserType" id="userType">
            <option value="" selected>-- Select a User Type --</option>
            @foreach ($userTypes as $userType)
                <option value="{{ $userType->id }}">{{ $userType->name }}</option>
            @endforeach
        </select>
        <label for="userType"></label>
    </div>

    @if ($selectedUserType)
        @foreach ($permissions as $category => $permissions)
            <div class="row">
                <div class="col l12 m12 s12 mb-4">
                    <h5 class="">{{ $category ?? 'Uncategorised' }} Permissions</h5>
                </div>

                @foreach ($permissions as $permission)
                    <div class="col l6 m6 s12">
                        <p class="mt-1">
                            <label>
                                <input type="checkbox" wire:model="selectedPermissions"
                                    value="{{ $permission['name'] }}" />
                                <span>{{ $permission['name'] }}</span>
                            </label>
                        </p>
                    </div>
                @endforeach
            </div>
        @endforeach

        <button wire:click="savePermissions" class="btn waves-effect waves-light">Save Permissions</button>
    @endif

    @if (session()->has('message'))
        <div class="card-panel green lighten-4 green-text text-darken-4 mt-2">
            {{ session('message') }}
        </div>
    @endif
</div>
