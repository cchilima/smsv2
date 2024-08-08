<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masked Input Example</title>
</head>
<body>

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
                                <input pattern="\d{6}/\d{2}/\d{1}" id="nrc" wire:model="nrc" maxlength="11" name="nrc" type="text"
                                    class="validate masked" placeholder="XXXXXX/XX/X" data-mask="000000/00/0">
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
                            <a class="right" href="{{ route('login') }}"> go to home</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var nrcInput = document.getElementById('nrc');
    
    nrcInput.addEventListener('input', function (e) {
        var value = e.target.value.replace(/\D/g, ''); // Remove all non-digit characters
        var formattedValue = '';

        if (value.length > 0) {
            formattedValue += value.substring(0, 6);
        }
        if (value.length > 6) {
            formattedValue += '/' + value.substring(6, 8);
        }
        if (value.length > 8) {
            formattedValue += '/' + value.substring(8, 9);
        }

        e.target.value = formattedValue;
    });
});
</script>

</body>
</html>
