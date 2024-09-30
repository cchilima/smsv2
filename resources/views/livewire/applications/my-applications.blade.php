@php
    use App\Helpers\Qs;
@endphp

<div class="container mt-10">
    <div>


        <h6 class="mb-5">my applications </h6>

        <a class="btn btn-small black mb-5" wire:click="startNewApplication()" class="mb-5">start new <i class="material-icons right">add</i> </a>
        <a class="right" href="{{ route('login') }}" class="mb-5">Go Home </a>

        <div class="white z-depth-1 rounded">



            <table class="table centered">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Program</th>
                        <th>Status</th>
                        <th>Amount Paid</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applications as $key => $application)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $application->first_name }} {{ $application->last_name }}</td>
                            <td>{{ $application->program->name ?? '' }}</td>
                            <td>{{ $application->status ?? '' }}</td>
                            <td>K{{ $application->payment->sum('amount') ?? 'K0' }}</td>

                            <td>{{ $application->created_at ? $application->created_at->format('d M Y') : '' }}</td>


                            <td >

                            <a class='dropdown-trigger btn btn-small btn-floating black' href='#' data-target="dropdown{{$key}}"><i class="material-icons">more_vert</i></a>

                                <ul id='dropdown{{$key}}' class='dropdown-content'>
                                    <li> <a href="{{ route('application.show', $application->id) }}"
                                            class="dropdown-item black-text">view</a></li>
                                    <li>
                                        @if ($application->status === 'incomplete')
                                            <a href="/application/step-2/{{ $application->id }}"
                                                class="dropdown-item black-text">continue
                                                </a>
                                        @endif
                                    </li>

                                </ul>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>
</div>
