@php
    use App\Helpers\Qs;
@endphp

<div class="">
    <table class="table table-hover table-striped-columns mb-3">
        <div class="justify-content-between">
            <h5>
                <strong>{{ $entry->name }}</strong>
                <p>{{ $entry->id }}</p>
            </h5>
            <h5><strong>{{ $entry->id }}</strong></h5>
            <input type="hidden" name="academic" value="{{ $academicPeriod->id }}">
            <input type="hidden" name="program" value="{{ $program->id }}">
            <input type="hidden" name="level_name" value="{{ $program->id }}">
            <input type="hidden" name="level_name" value="{{ $level->id }}">
            <input type="hidden" name="type" value="0">
        </div>

        <thead>
            <tr>
                <th>S/N</th>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Assessments</th>
                <th>CA</th>
                <th>Modify</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entry->courses as $course)
                <tr>
                    <th>{{ $loop->iteration }}</th>
                    <td>{{ $course['course_code'] }}</td>
                    <td>{{ $course['course_title'] }}</td>
                    @php
                        $totalSum = 0;
                        $totalCA = 0;
                    @endphp
                    <td>
                        <table class="table table-bordered table-hover table-striped">
                            <tbody>
                                <tr>
                                    <td>Assessment Type</td>
                                    <td>Total</td>
                                    <td>Out of</td>
                                    {{--                                                                <td>Grade</td> --}}
                                </tr>
                                {{--                                                            @foreach ($courses->class->course->grades as $grade) --}}
                                @foreach ($course['grades'] as $grade)
                                    <tr>
                                        <td>{{ $grade['type'] }}</td>
                                        <td>{{ $grade['total'] }}</td>
                                        <td>{{ $grade['outof'] }}</td>
                                        {{--                                                                    <td>{{ $grade['grade'] }}</td> --}}
                                        @php
                                            $totalCA += $grade['total'];
                                            $totalSum += $grade['outof'];
                                        @endphp
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                    <td>{{ $totalCA . '  out of  ' . $totalSum }}</td>

                    <td>
                        @if (Qs::userIsTeamSA())
                            <a onclick="modifyMarksCAsL('{{ $entry->id }}','{{ $entry->name }}','{{ $course['course_code'] }}','{{ $course['course_title'] }}','{{ json_encode($course['grades']) }}')"
                                class="nav-link"><i class="icon-pencil"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    @php
        $commentLower = str()->lower($entry->calculated_grade['comment']);

        $commentBgColor = match (true) {
            str()->startsWith($commentLower, 'proceed & repeat') => 'bg-warning',
            str()->startsWith($commentLower, 'part time') => 'bg-danger',
            default => 'bg-success',
        };
    @endphp

    <p class="{{ $commentBgColor }} p-4 align-bottom">
        {{ $entry->calculated_grade['comment'] }}
        {{-- {{ Form::checkbox('ckeck_user', 1, false, ['class' => 'ckeck_user  float-right p-5', 'data-id' => $entry->id]) }} --}}
        {{-- {{ Form::label('publish', 'Publish', ['class' => 'mr-3 float-right']) }} --}}
    </p>
</div>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content row col card card-body">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <div class="list-icons">
                    <a type="submit" class="btn btn-outline-light" onclick="CloseModal()"><i
                            class="icon-close2 ml-2"></i></a>
                </div>
            </div>
            <div class="modal-body p-3">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeModalButton" onclick="CloseModal()"
                    id="closeModalButton" data-bs-dismiss="modal">
                    Close
                </button>
                <button wire:click.debounce.1000ms="$refresh" type="button" onclick="SubmitData()"
                    class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
