<div>
    <div class="container p-10">

        <div class="form-wizard mt-10 mb-5 items-center">
            <a wire:click="setSection('personal_info')"
                class="{{ $currentSection === 'personal_info' ? 'active' : '' }}">Personal Info </a>

            <span><i class="material-icons small grey-text">arrow_forward</i></span>

            <a wire:click="setSection('academic_info')"
                class="{{ $currentSection === 'academic_info' ? 'active' : '' }}">Program Applied for</a>

            <span><i class="material-icons small grey-text">arrow_forward</i></span>

            <a wire:click="setSection('next_of_kin')" class="{{ $currentSection === 'next_of_kin' ? 'active' : '' }}">Next
                of Kin </a>

            <span><i class="material-icons small grey-text ">arrow_forward</i></span>

            <a wire:click="setSection('results')"
                class="{{ $currentSection === 'results' ? 'active' : '' }}">Results</a>
        </div>

        <div class="mb-5">
            <h6>Fill out application form</h6>
        </div>

        <div class="row">
            <div style="padding:15px; border-radius:9px;" class="col s12 white z-depth-1">

                <form wire:submit.prevent="saveProgress" class="col s12">

                    @if ($currentSection === 'personal_info')
                        <div id="personal_info">
                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <input wire:model="first_name" placeholder="First Name" type="text"
                                        class="validate">
                                    <label class="active">First Name</label>
                                    @error('first_name')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-field col m6 s12">
                                    <input wire:model="last_name" placeholder="Last Name" type="text"
                                        class="validate">
                                    <label class="active">Last Name</label>
                                    @error('last_name')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <input wire:model="middle_name" placeholder="Middle Name" type="text"
                                        class="validate">
                                    <label class="active">Middle Name</label>
                                    @error('middle_name')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-field col m6 s12">
                                    <input wire:model="date_of_birth" placeholder="Date of Birth" type="date"
                                        class="validate">
                                    <label class="active">Date of Birth</label>
                                    @error('date_of_birth')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <input wire:model="email" placeholder="Email" type="email" class="validate">
                                    <label class="active">Email</label>
                                    @error('email')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-field col m6 s12">
                                    <input wire:model="phone_number" placeholder="Phone" type="text"
                                        class="validate">
                                    <label class="active">Phone</label>
                                    @error('phone_number')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col m6 s12">
                                    <label class="active">Gender</label>
                                    <select wire:model="gender" class="browser-default custom-select input-field">
                                        <option selected>Select gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    @error('gender')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col m6 s12">
                                    <label class="active">Marital Status</label>
                                    <select wire:model="marital_status_id"
                                        class="browser-default custom-select input-field ">
                                        <option selected>Select marital status</option>
                                        @foreach ($marital_statuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->status }} </option>
                                        @endforeach
                                    </select>
                                    @error('marital_status_id')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <div class="row">

                                <div class="input-field col m12 s12">
                                    <input wire:model="address" placeholder="Address" type="text" class="validate">
                                    <label class="active">Address</label>
                                    @error('address')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <div class="row">
                                <div class="col m12 s12">
                                    <label class="active">Country</label>
                                    <select wire:model.live="country_id" class="browser-default custom-select ">
                                        <option selected>Select country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->country }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col m6 s12">
                                    <label class="active">Province</label>
                                    <select wire:model.live="province_id" class="browser-default custom-select ">
                                        <option selected>Select province</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col m6 s12">
                                    <label class="active">Town</label>
                                    <select wire:model.live="town_id" class="browser-default custom-select ">
                                        <option selected>Select town</option>
                                        @foreach ($towns as $town)
                                            <option value="{{ $town->id }}">{{ $town->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    @endif

                    @if ($currentSection === 'academic_info')
                        <div id="academic_info">
                            <div class="row">

                                <div class="col m12 s12">
                                    <label class="active">Year applying for </label>

                                    <select wire:model.live="year_applying_for"
                                        class="browser-default custom-select input-field">
                                        <option selected>Select year</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}"
                                                @if ($year == $year_applying_for) selected @endif>
                                                {{ $year }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col m6 s12">
                                    <label class="active">Program</label>
                                    <select wire:model.live="program_id"
                                        class="browser-default custom-select input-field">
                                        <option selected>Select program</option>
                                        @foreach ($programs as $program)
                                            <option value="{{ $program->id }}">{{ $program->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col m6 s12">
                                    <label class="active">Study mode</label>
                                    <select wire:model.live="study_mode_id"
                                        class="browser-default custom-select input-field">
                                        <option selected>Select study mode</option>
                                        @foreach ($studyModes as $mode)
                                            <option value="{{ $mode->id }}">{{ $mode->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col m6 s12">
                                    <label class="active">Intake</label>
                                    <select wire:model.live="academic_period_intake_id"
                                        class="browser-default custom-select input-field">
                                        <option selected>Select intake</option>
                                        @foreach ($periodIntakes as $intake)
                                            <option value="{{ $intake->id }}">{{ $intake->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col m6 s12">
                                    <label>Upload Results (PDF, 5Mb max)</label>
                                    <div class="file-field input-field">
                                        <div class="btn btn-small grey">
                                            <span>Select File</span>
                                            <input wire:model="results" type="file" accept="pdf" required>
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input class="file-path validate" type="text">
                                        </div>
                                        @error('results')
                                            <span class="red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="p-2 ">
                                <button wire:click="uploadDocument()" class="btn btn-small black"><i
                                        class="material-icons right">arrow_upward</i>Save Attachment</button>
                            </div>

                            @if ($applicant->attachment)
                                <h5>Single results & ID file</h5>

                                <table class="rounded white z-depth-0 centered striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Type</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody class="light-deca">

                                        <tr>
                                            <td>1. </td>
                                            <td>{{ $applicant->attachment->type }}</td>
                                            <td>{{ $applicant->attachment->attachment }}</td>
                                            <td><a href="{{ asset('storage/uploads/attachments/applications/' . $applicant->attachment->attachment) }}"
                                                    target="_blank">open</a></td>

                                        </tr>

                                    </tbody>
                                </table>
                            @endif

                        </div>
                    @endif

                    @if ($currentSection === 'next_of_kin')
                        <div id="next_of_kin">

                            <div class="row">

                                <div class="col m12 s12">
                                    <label class="active">Relationship</label>
                                    <select wire:model.live="kin_relationship_id"
                                        class="browser-default custom-select ">
                                        <option selected>Select relationship</option>
                                        @foreach ($relationships as $relationship)
                                            <option value="{{ $relationship->id }}">{{ $relationship->relationship }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="input-field col m12 s12">
                                    <input wire:model="kin_full_name" placeholder="Name" type="text"
                                        class="validate">
                                    <label class="active">Name</label>
                                    @error('name')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <input wire:model="kin_mobile" placeholder="Mobile 1" type="text"
                                        class="validate">
                                    <label class="active">Mobile</label>
                                    @error('kin_mobile')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="input-field col m6 s12">
                                    <input wire:model="kin_telephone" placeholder="Mobile 2" type="text"
                                        class="validate">
                                    <label class="active"></label>
                                    @error('kin_telephone')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">

                                <div class="input-field col m6 s12">
                                    <input wire:model="kin_address" placeholder="Address" type="text"
                                        class="validate">
                                    <label class="active">Address</label>
                                    @error('address')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col m6 s12">
                                    <label class="active">Country</label>
                                    <select wire:model.live="kin_country_id" class="browser-default custom-select ">
                                        <option selected>Select country</option>
                                        @foreach ($kin_countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->country }} </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col m6 s12">
                                    <label class="active">Province</label>
                                    <select wire:model.live="kin_province_id" class="browser-default custom-select ">
                                        <option selected>Select province</option>
                                        @foreach ($kin_provinces as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col m6 s12">
                                    <label class="active">Town</label>
                                    <select wire:model.live="kin_town_id" class="browser-default custom-select ">
                                        <option selected>Select town</option>
                                        @foreach ($kin_towns as $town)
                                            <option value="{{ $town->id }}">{{ $town->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    @endif

                    @if ($currentSection === 'results')
                        <div id="results">
                            <div class="row">
                                <div class="col m12 s12">
                                    <label class="active">Secondary School</label>
                                    <select wire:model.live="secondary_school" class="browser-default custom-select ">
                                        <option selected>Select secondary school</option>
                                        @foreach ($schools as $school)
                                            <option value="{{ $school['name'] }}">{{ $school['name'] }} </option>
                                        @endforeach
                                    </select>
                                    @error('secondary_school')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">

                                <div class="input-field col m6 s12">
                                    <label class="active">Subject</label>
                                    <select wire:model.live="subject" class="browser-default custom-select ">
                                        <option selected>Select subject</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject['name'] }}">{{ $subject['name'] }} </option>
                                        @endforeach
                                    </select>
                                    @error('subject')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="input-field col m6 s12">
                                    <input wire:model="grade" placeholder="Grade" type="number" pattern="[1-9]"
                                        class="validate">
                                    <label class="active">Grade</label>
                                    @error('grade')
                                        <span class="red-text darken-4 error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="p-2 ">
                                    <button wire:click="saveGrade()" class="btn btn-small black"><i
                                            class="material-icons right">arrow_upward</i>Save Grade</button>
                                </div>

                            </div>

                            @if (count($applicant->grades) > 0)

                                <h5>Grades</h5>

                                <table class="rounded white z-depth-0 centered striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Subject</th>
                                            <th>Grade</th>

                                        </tr>
                                    </thead>

                                    <tbody class="light-deca">
                                        @foreach ($applicant->grades as $key => $grade)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $grade->subject }}</td>
                                                <td>{{ $grade->grade }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                            @endif

                        </div>
                    @endif

                    <button type="submit" class="btn btn-small black mt-10 rounded">Save Progress</button>
                    <a href="{{ route('application.my-applications', $this->applicant->id) }}"
                        class="btn btn-small black mt-10 rounded">My Applications</a>
                </form>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col s12 center-align">
                <!-- Previous Button -->
                <a class="btn-floating waves-effect waves-light white" wire:click="previousSection"
                    {{ $currentSection === 'personal_info' ? 'disabled' : '' }}>
                    <i class="material-icons indigo-text">arrow_back</i>
                </a>

                <!-- Next Button -->
                <a class="btn-floating waves-effect waves-light white" wire:click="nextSection"
                    {{ $currentSection === 'results' ? 'disabled' : '' }}>
                    <i class="material-icons indigo-text">arrow_forward</i>
                </a>
            </div>
        </div>

    </div>
</div>

@script
    <script>
        $wire.on('grade-added', () => {
            M.toast({
                html: 'grade uploaded successfully'
            })
        });

        $wire.on('grade-failed', () => {
            M.toast({
                html: 'grade upload unsuccessful'
            })
        });

        $wire.on('fill-all-fields', () => {
            M.toast({
                html: 'Please provide all required information.'
            })
        });

        $wire.on('attachment-added', () => {
            M.toast({
                html: 'attachment uploaded successfully'
            })
        });

        $wire.on('attachment-failed', () => {
            M.toast({
                html: 'attachment upload unsuccessful'
            })
        });

        $wire.on('progress-saved', () => {
            M.toast({
                html: 'progress saved successfully'
            })
        });


        $wire.on('application-completed', () => {
            M.toast({
                html: 'application completed'
            })
        });
    </script>
@endscript
