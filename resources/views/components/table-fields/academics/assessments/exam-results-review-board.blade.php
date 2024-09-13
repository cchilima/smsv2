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

            <input type="hidden" name="academic" value="{{ $academicPeriod->id }}">
            <input type="hidden" name="program" value="{{ $program->id }}">
            <input type="hidden" name="level_name" value="{{ $program->id }}">
            <input type="hidden" name="level_name" value="{{ $entry->course_level_id }}">
            <input type="hidden" name="type" value="1">
        </div>

        <thead>
            <tr>
                <th>S/N</th>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Assessments</th>
                <th>Modify</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($entry->courses as $course)
                <tr>
                    <th>{{ $loop->iteration }}</th>
                    <td>{{ $course['course_code'] }}</td>
                    <td>{{ $course['course_title'] }}</td>
                    <td>
                        <table class="table table-bordered table-hover table-striped">
                            <tbody>
                                <tr>
                                    <td>CA</td>
                                    <td>Exam</td>
                                    <td>Total</td>
                                    <td>Grade</td>
                                </tr>
                                <tr>
                                    <td>{{ $course['grades']['ca'] }}</td>
                                    <td>{{ $course['grades']['exam'] . ' out of ' . $course['grades']['outof'] }}
                                    </td>
                                    <td>{{ $course['grades']['total_sum'] }}</td>
                                    <td>{{ $course['grades']['grade'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    <td>
                        @if (Qs::userIsTeamSA())
                            <a onclick="modifyMarksExam('{{ $entry->id }}','{{ $entry->name }}','{{ $course['course_code'] }}','{{ $course['course_title'] }}','{{ json_encode([$course['grades']]) }}')"
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
