<div class="container">
    <h5>Create a New Permission</h5>

    <!-- Permission Creation Form -->
    <form wire:submit.prevent="createPermission">
        <div class="input-field mt-4">
            <input type="text" id="permission_name" wire:model="name" class="validate">
            <label for="permission_name">Permission Name</label>
            @error('name')
                <span class="red-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="category">Category</label>

            <select type="text" id="category" wire:model="category" class="browser-default">
                <option selected disabled>Select Category</option>
                <option value="Departments & Programs">Departments & Programs</option>
                <option value="Academics">Academics</option>
                <option value="Accounting">Accounting</option>
                <option value="Students">Students</option>
                <option value="Admissions">Admissions</option>
                <option value="Accommodation">Accommodation</option>
                <option value="Reports">Reports</option>
                <option value="Other">Other</option>
            </select>

            @error('catetory')
                <span class="red-text">{{ $message }}</span>
            @enderror
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
