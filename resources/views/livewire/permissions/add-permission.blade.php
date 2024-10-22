<div class="container">
    <h5>Create a New Permission</h5>

    <!-- Permission Creation Form -->
    <form wire:submit.prevent="createPermission">
        <div class="input-field">
            <input type="text" id="permission_name" wire:model="name" class="validate">
            <label for="permission_name">Permission Name</label>
            @error('name') <span class="red-text">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn waves-effect waves-light">Create Permission</button>
    </form>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="card-panel green lighten-4 green-text text-darken-4 mt-2">
            {{ session('message') }}
        </div>
    @endif
</div>
