@php
    use App\Helpers\Qs;
@endphp

<table class="w-full">
    <tr>
        <td class="w-full text-center">
            <img class="logo" src="{{ public_path('images/logo-v2.png') }}" alt="Logo" height="65">
            <h2 class="v-spacer">{{ Qs::getSystemName() }}</h2>
            <span>{{ Qs::getSetting('po_box') }},</span>
            <span>{{ Qs::getSetting('address') }},</span>
            <span>{{ Qs::getSetting('town') }},</span>
            <span>{{ Qs::getSetting('country') }}.</span>
            <h3 class="top-spacer">{{ $title }}</h3>
        </td>
    </tr>
</table>
