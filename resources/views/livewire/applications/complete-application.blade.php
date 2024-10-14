@section('page_title', 'New Student Application')

@php
    use App\Helpers\Qs;
@endphp

<div class="container p-10">

    <div class="card">
        <div class="card-body">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <a wire:click="setSection('personal_info')"
                    class="{{ $currentSection === 'personal_info' ? 'active btn-small' : '' }} btn">Personal
                    Information </a>
                <span><i class="icon-arrow-right8 text-muted"></i></span>
                <a wire:click="setSection('academic_info')"
                    class="{{ $currentSection === 'academic_info' ? 'active btn-small' : '' }} btn">Program
                    Information</a>
                <span><i class="icon-arrow-right8 text-muted"></i></span>
                <a wire:click="setSection('next_of_kin')"
                    class="{{ $currentSection === 'next_of_kin' ? 'active btn-small' : '' }} btn">Next
                    of Kin Information </a>
                <span><i class="icon-arrow-right8 text-muted"></i></span>
                <a wire:click="setSection('results')"
                    class="{{ $currentSection === 'results' ? 'active btn-small' : '' }} btn">Results
                    Information</a>
            </div>

            <div class="mb-4">
                <h5>Fill out the form to apply</h5>
            </div>

            <form wire:submit.prevent="saveProgress" class="">
                @if ($currentSection === 'personal_info')
                    <div id="personal_info">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>First Name <span class="text-danger">*</span></label>
                                    <input wire:model="first_name" type="text" placeholder="First Name"
                                        class="form-control">
                                    @error('first_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Middle Name</label>
                                    <input wire:model="middle_name" type="text" placeholder="Middle Name"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Last Name <span class="text-danger">*</span></label>
                                    <input wire:model="last_name" type="text" placeholder="Last Name"
                                        class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender">Gender <span class="text-danger">*</span></label>
                                    <select wire:model="gender" class="form-control" id="gender">
                                        <option disabled selected>Select gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date of Birth <span class="text-danger">*</span></label>
                                    <input name="date_of_birth" wire:model="date_of_birth" type="date"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="marital_status">Marital Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="marital_status" wire:model="marital_status_id"
                                    data-placeholder="Select marital status">
                                    <option selected>Select marital status</option>
                                    @foreach ($marital_statuses as $marital_status)
                                        <option value="{{ $marital_status->id }}">{{ $marital_status->status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email Address <span class="text-danger">*</span></label>
                                    <input wire:model="email" type="email" placeholder="Email Address"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number <span class="text-danger">*</span></label>
                                    <input wire:model="phone_number" type="text" placeholder="Phone Number"
                                        class="form-control" x-mask="+9999999999999">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="country">Country <span class="text-danger">*</span></label>
                                    <select class="form-control" id="country" wire:model.live="country_id">
                                        <option selected>Select country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->country }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="province">Province <span class="text-danger">*</span></label>
                                    <select class="form-control" id="province" wire:model.live="province_id">
                                        <option selected>Select province</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="town">Town <span class="text-danger">*</span></label>
                                    <select class="form-control" id="town" wire:model="town_id">
                                        <option selected>Select town</option>
                                        @foreach ($towns as $town)
                                            <option value="{{ $town->id }}">{{ $town->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="address">Address <span class="text-danger">*</span></label>
                                    <input wire:model="address" id="address" type="text" placeholder="Address"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($currentSection === 'academic_info')
                    <div id="academic_info">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="year-applying-for">Year Applying For <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" id="year-applying-for"
                                        wire:model.live="year_applying_for">
                                        <option selected>Select year of admission</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="intake">Intake <span class="text-danger">*</span></label>
                                    <select class="form-control" id="intake"
                                        wire:model.live="academic_period_intake_id">
                                        <option selected>Select intake</option>
                                        @foreach ($periodIntakes as $intake)
                                            <option value="{{ $intake->id }}">{{ $intake->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="program">Program <span class="text-danger">*</span></label>
                                    <select class="form-control" id="program" wire:model.live="program_id">
                                        <option selected>Select program</option>
                                        @foreach ($programs as $program)
                                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="study-mode">Study Mode <span class="text-danger">*</span></label>
                                    <select class="form-control" id="study-mode" wire:model.live="study_mode_id">
                                        <option selected>Select mode of study</option>
                                        @foreach ($studyModes as $studyMode)
                                            <option value="{{ $studyMode->id }}">{{ $studyMode->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="d-block">Upload Results</label>
                                    <input wire:model="results" accept="pdf" type="file" name="results"
                                        class="form-control form-input-styled">
                                    @error('results')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="d-block" for="">&nbsp;</label>
                                    <button wire:click="uploadDocument()" class="btn btn-primary">Upload
                                        Results</button>
                                </div>
                            </div>
                        </div>

                        @if ($applicant->attachment)
                            <h6>Uploaded Attachments</h6>
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Filename</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="light-deca">
                                    <tr>
                                        <td>1. </td>
                                        <td>{{ $applicant->attachment->type }}</td>
                                        <td>{{ $applicant->attachment->attachment }}</td>
                                        <td><a class=""
                                                href="{{ asset('storage/uploads/attachments/applications/' . $applicant->attachment->attachment) }}"
                                                target="_blank">Download</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                @endif
                @if ($currentSection === 'next_of_kin')
                    <div id="next_of_kin">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="relationship">Relationship<span class="text-danger">*</span></label>
                                    <select class="form-control" id="relationship"
                                        wire:model.live="kin_relationship_id">
                                        <option selected>Select relationship</option>
                                        @foreach ($relationships as $relationship)
                                            <option value="{{ $relationship->id }}">{{ $relationship->relationship }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Full Name<span class="text-danger">*</span></label>
                                    <input wire:model="kin_full_name" type="text" placeholder="Full Name"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number<span class="text-danger">*</span></label>
                                    <input wire:model="kin_mobile" x-mask="+9999999999999" type="text"
                                        placeholder="Mobile number" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telephone Number</label>
                                    <input wire:model="kin_telephone" type="text" placeholder="Telephone number"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kin_country">Country <span class="text-danger">*</span></label>
                                    <select class="form-control" id="kin_country" wire:model.live="kin_country_id">
                                        <option selected>Select country</option>
                                        @foreach ($kin_countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->country }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kin_province">Province <span class="text-danger">*</span></label>
                                    <select class="form-control" id="kin_province" wire:model.live="kin_province_id">
                                        <option selected>Select province</option>
                                        @foreach ($kin_provinces as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kin_town">Town <span class="text-danger">*</span></label>
                                    <select class="form-control" id="kin_town" wire:model="kin_town_id">
                                        <option selected>Select town</option>
                                        @foreach ($kin_towns as $town)
                                            <option value="{{ $town->id }}">{{ $town->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="kin_address">Address <span class="text-danger">*</span></label>
                                    <input wire:model="kin_address" id="kin_address" type="text"
                                        placeholder="Address" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($currentSection === 'results')
                    <div id="results">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="secondary_school">Secondary School <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" id="secondary_school"
                                        wire:model.live="secondary_school">
                                        <option selected>Select secondary school</option>
                                        @foreach ($schools as $school)
                                            <option value="{{ $school['name'] }}">{{ $school['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="subject">Subject <span class="text-danger">*</span></label>
                                    <select class="form-control" id="subject" wire:model.live="subject">
                                        <option selected>Select subject</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject['name'] }}">{{ $subject['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="grade">Grade <span class="text-danger">*</span></label>
                                    <select class="form-control" id="grade" wire:model.live="grade">
                                        <option selected>Select grade</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="d-block" for="">&nbsp;</label>
                                    <button wire:click="saveGrade()" class="btn btn-primary">Save Grade</button>
                                </div>
                            </div>
                        </div>
                        @if (count($applicant->grades) > 0)
                            <div class="row">
                                <div class="col">
                                    <h5>Grades</h5>
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Subject</th>
                                                <th>Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($applicant->grades as $key => $grade)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ $grade->subject }}</td>
                                                    <td>{{ $grade->grade }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary rounded">Save Progress</button>
                    <a href="{{ route('application.my-applications', $this->applicant->id) }}"
                        class="ml-2 rounded">My
                        Applications</a>

                </div>
            </form>
            <div class="row mt-3">
                <div class="col s12 d-flex justify-content-center">
                    <!-- Previous Button -->
                    <a class="mr-2 btn {{ $currentSection === 'personal_info' ? 'text-secondary disabled' : 'btn-primary text-white d-flex' }} align-items-center"
                        @disabled($currentSection === 'personal_info') wire:click="previousSection">
                        <i class="icon-arrow-left8 mr-2"></i>Previous Section
                    </a>
                    <!-- Next Button -->
                    <a class="btn {{ $currentSection === 'results' ? 'text-secondary disabled' : 'btn-primary text-white d-flex' }} align-items-center"
                        wire:click="nextSection" @disabled($currentSection === 'results')>Next
                        Section
                        <i class="icon-arrow-right8 ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
