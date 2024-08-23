@php
    use App\Helpers\Qs;
@endphp

<table class="table table-bordered table-hover table-striped">
    <tbody>
        <td>Fee Name</td>
        <td>Amount</td>
        <td>Type</td>

        @foreach ($row->details as $detail)
            <tr>
                <td>{{ $detail->fee ? $detail->fee->name : '' }}</td>
                <td>{{ $detail->amount }}</td>
                <td>{{ $detail->type }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
