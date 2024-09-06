<div class="container mt-20">

    <p class="flow-text light-deca mb-2">{{ $student->user->first_name }}'s courses</p>

    <div class="row">
        <form wire:submit.prevent="addCourse">
            <div class="col m4 s12">
                <div class="input-field">
                    <select wire:model="course_id" class="browser-default custom-select">
                        <option></option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->course_id }}">{{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                    <label class="active">Course</label>
                    @error('course_id')
                        <span class="red-text darken-4 error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col m4 s12">
                <button class="btn btn-small btn-floating black darken-4 mt-4 rounded-md">
                    <i class="material-icons left tiny">add</i>
                </button>
            </div>
        </form>
    </div>

    <div class="white z-depth-1 rounded">


        <table class="table responsive-table striped centered">
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
                        <td> {{ $student->id}}</td>

                        <td>
                            <a class='dropdown-trigger btn btn-small btn-floating black' href='#'
                                data-target="dropdown{{ $key }}"><i class="material-icons">more_vert</i></a>

                            <ul id='dropdown{{ $key }}' class='dropdown-content'>
                                <li> <a wire:click="dropCourse({{ $course['enrollment_id'] }})"
                                        class="dropdown-item black-text">drop</a></li>
                            </ul>
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
