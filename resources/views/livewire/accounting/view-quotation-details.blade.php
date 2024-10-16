<div class="container mt-10">

@php
    use App\Helpers\Qs;
@endphp


    <div>

            <div class="mt-20">


                <h4>Quotation - {{ $quotation->period->name}}</h4>
                <div class="white z-depth-1 rounded mt-4 mb-10">


                    <table class="responsive-table centered">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Fee</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quotation->details as $key => $detail)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $detail->fee->name }}</td>
                                    <td>ZMW {{ $detail->amount }}</td>
                                    <td>{{ $detail->created_at->format('d F Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>


    </div>


</div>