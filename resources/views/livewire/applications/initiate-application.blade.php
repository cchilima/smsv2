@section('page_title', 'Start or Continue Application')

@php
    use App\Helpers\Qs;
@endphp

<div class="row justify-content-center">
    <div class="col col-md-9 col-lg-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">Enter either your NRC or passport number</h6>
                {{-- {!! Qs::getPanelOptions() !!}   --}}
            </div>
            <div class="card-body">
                <div wire:ignore class="tab-pane fade show" id="collect-payment">
                    <form wire:submit.prevent="saveAndProceed">
                        <div class="form-group">
                            <label for="nrc">NRC Number</label>
                            <input type="text" class="form-control" id="nrc" wire:model="nrc"
                                placeholder="XXXXXX/XX/X" x-mask="999999/99/9">
                            @error('passport')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="passport">Passport Number</label>
                            <input wire:model="passport" id="passport" type="text" class="form-control"
                                placeholder="Passport" x-mask="**99999999">
                            @error('passport')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center">
                            <button id="ajax-btn" type="submit" class="btn btn-primary">Proceed</button>
                            <a class="ml-2" href="{{ route('login') }}"> Go Home</a>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div>
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
                            <input id="nrc" wire:model="nrc" maxlength="11" name="nrc" type="text"
                                class="validate" placeholder="XXXXXX/XX/X" x-mask="999999/99/9">
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
                        <a class="right" href="{{ route('login') }}"> Go Home</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}
