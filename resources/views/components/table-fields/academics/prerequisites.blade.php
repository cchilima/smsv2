@php
    use App\Helpers\Qs;
@endphp

<table class="table">
    <tbody>
        @if (count($row->prerequisites) > 0)
            @foreach ($row->prerequisites as $prerequisite)
                <tr>
                    <td>{{ $prerequisite->code . ' - ' . $prerequisite->name }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                No prerequisites
            </tr>
        @endif

    </tbody>
</table>
