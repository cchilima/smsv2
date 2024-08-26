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

                <form class="ajax-update" wire:submit="uploadPhotos()">
                    <x-filepond::upload wire:model="photos" required="true" multiple="true" max-files="10"
                        label-idle="Drag & drop or <span class='filepond--label-action'>browse.</span> up to 10 photos  " />

                    <div class="text-left">
                        <button wire:loading.attr="disabled"
                            wire:click.debounce.1000ms="refreshTable('StudentPhotosTable')" id="ajax-btn"
                            type="submit" class="btn btn-primary">
                            Upload Photos
                        </button>
                    </div>
                </form>

                <p class="mt-3">JPG/PNG & 2MB max size per file. File name format: <code>STUDENT-ID.jpg/png</code>
                    e.g., <code>1234567.jpg</code>
                </p>
            </div>
        </div>
    </div>
</div>
