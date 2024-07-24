@php
    use App\Helpers\Qs;
@endphp

<div class="container p-10">
    <div>
        <div class="mb-5">
            <h6>Start or continue your application</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div>
            <div class="row">
                <div class="col m10 s12 white rounded z-depth-1">
                    <form wire:submit.prevent="saveAndProceed" class="col s12">
                        <div class="row">
                            <div class="input-field col m12 s12">
                                <label class="active" for="nrc">NRC</label>
                                <input wire:model="nrc" id="nrc" maxlength="9" name="nrc" type="text"
                                    class="validate" placeholder="NRC">
                                @error('nrc')
                                    <span class="red-text darken-4 error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col m12 s12">
                                <label class="active" for="passport">Passport</label>
                                <input wire:model="passport" id="passport" name="passport" type="text"
                                    class="validate" placeholder="Passport">
                                @error('passport')
                                    <span class="red-text darken-4 error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="mb-2">
                            <button type="submit" class="btn btn-small black rounded">submit</button>
                            <a class="btn btn-small black rounded" href="{{ route('login') }}">Home</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
