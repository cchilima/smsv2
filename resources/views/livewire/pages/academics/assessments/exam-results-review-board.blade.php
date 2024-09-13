@if (!empty($grades))
    @section('page_title', $period->name . 's Results')
@else
    @section('page_title', 'No results found')
@endif

@php
    use App\Helpers\Qs;
@endphp

<div class="card overflow-scroll">

    <div class="card-body">
        <div class="row p-3">
            <div class="container">
                <div class="row justify-content-end">
                    <div class="col-md-12">
                        <h3>Program: {{ $program_data->name }}
                            ({{ $program_data->code }}
                            )</h3>
                        <h4>{{ $level->name }}'s Results</h4>
                        <h4 class="mb-4 mt-0">Results for {{ $students }}
                            Students out</h4>
                        <div class="row">
                            <label for="assesmentID" class="col-lg-3 col-form-label font-weight-semibold">Course(Moderate
                                for all): <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                @php
                                    $uniqueCourseCodes = [];
                                @endphp
                                <select data-placeholder="Choose..." required name="assesmentID" id="assesmentID"
                                    class=" select-search form-control" onchange="StrMod4All(this.value,1)">
                                    <option value=""></option>
                                    @foreach ($grades['students'] as $student)
                                        @foreach ($student['courses'] as $course)
                                            @php
                                                $code = $course['course_details']['course_code'];
                                                $title = $course['course_details']['course_title'];
                                                $optionValue = $code . ' - ' . $title;
                                            @endphp
                                            @if (!in_array($optionValue, $uniqueCourseCodes))
                                                <option value="{{ $course['course_details']['class_id'] }}">
                                                    {{ $optionValue }}</option>
                                                @php
                                                    $uniqueCourseCodes[] = $optionValue;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr>

                        @livewire('datatables.academics.assessments.exam-results-review-board', [
                            'level' => $level,
                            'program' => $program_data,
                            'academicPeriod' => $period,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
