@section('page_title', 'Add or Drop Student Courses')

@php
    use App\Helpers\Qs;
@endphp

<div class="card">

    <div class="card-header header-elements-inline">
        <h6 class="card-title">{{ $student->user->first_name . ' ' . $student->user->last_name }}'s Courses</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form wire:confirm="Are you sure you want to add this course?" wire:submit.prevent="addCourse">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Course</label>
                        <select wire:model="course_id" class="form-control" required>
                            <option selected value=''>Select course</option>

                            @foreach ($courses as $course)
                                <option value="{{ $course->course_id }}">{{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <span class="text-danger d-inline-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="d-md-block" for="">&nbsp;</label>
                        <button class="btn btn-primary"><i class="icon-plus3 mr-2"></i>Add Course</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="">

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Student No.</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($enrolled_courses as $key => $course)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $course['course']->code }}</td>
                        <td>{{ $course['course']->name }}</td>
                        <td> {{ $student->id }}</td>

                        <td>
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-left">
                                        <a wire:confirm="Are you sure you want to drop this course?"
                                            wire:click="dropCourse({{ $course['enrollment_id'] }})"
                                            class="dropdown-item">Drop Course</a>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@script
    <script>
        $wire.on('course-dropped', () => {
            M.toast({
                html: 'course dropped successfully'
            })
        });

        $wire.on('course-drop-failed', () => {
            M.toast({
                html: 'course drop unsuccessful'
            })
        });

        $wire.on('course-added', () => {
            M.toast({
                html: 'course added successfully'
            })
        });

        $wire.on('course-add-failed', () => {
            M.toast({
                html: 'course add unsuccessful'
            })
        });
    </script>
@endscript
