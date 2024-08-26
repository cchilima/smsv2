@section('page_title', 'Student Photos')

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
                <form class="ajax-store" method="post" action="{{ route('application.collect_fee') }}">
                    @csrf

                    <div class="form-group">
                        <label for="applicant">Applicant Code</label>
                        <input type="text" class="form-control" id="applicant" name="applicant"
                            placeholder="Applicant Code" required>
                    </div>

                    <div class="text-left">
                        <button wire:click.debounce.1000ms="refreshTable('ApplicationsTable')" id="ajax-btn"
                            type="submit" class="btn btn-primary">Submit <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
