@section('page_title', 'Student Photos')

@push('scripts')
    @filepondScripts
@endpush

@php
    use App\Helpers\Qs;
@endphp

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Student Photos</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#student-photos" class="nav-link active" data-toggle="tab">Photos</a>
            </li>
            <li class="nav-item"><a href="#upload-photos" class="nav-link" data-toggle="tab">Upload Photos</a>
            </li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="student-photos">
                <livewire:datatables.admissions.students.photos />
            </div>

            <div wire:ignore class="tab-pane fade show" id="upload-photos">
                <form wire:submit="uploadPhotos()">
                    <x-filepond::upload wire:model="photos" required="true" multiple="true" max-files="20"
                        label-idle="Drag & drop or <span class='filepond--label-action'>browse</span> up to 20 photos  " />

                    <div class="errors"></div>

                    <div class="text-left">
                        <button wire:loading.attr="disabled" id="ajax-btn" type="submit" class="btn btn-primary">
                            Upload Photos
                        </button>
                    </div>
                </form>

                <p class="mt-3">JPG/PNG. 5MB max size per file. File name format: <code>STUDENT-ID.jpg/png</code>
                    e.g. <code>1234567.jpg</code>
                </p>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        $wire.on('show_validation_errors', function(params) {
            showErrors(params[0], 'errors');
        })

        $wire.on('show_invalid_id_errors', function(params) {
            showErrors(params[0], 'errors');
        })

        function showErrors(errors, containerClass) {
            errorsDiv = document.querySelector(`.${containerClass}`);
            errorsDiv.innerHTML = '';

            errors.forEach(function(message) {
                errorsDiv.insertAdjacentHTML('beforeend',
                    `<span class="d-block mt-2 mb-2 text-danger error-message">${message}</span>`);
            });
        }
    </script>
@endscript
