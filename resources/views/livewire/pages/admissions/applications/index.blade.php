@section('page_title', 'Student Applications')

@php
    use App\Helpers\Qs;
@endphp

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Student Applications</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#applications" class="nav-link active" data-toggle="tab">Applications</a>
            </li>
            <li class="nav-item"><a href="#collect-payment" class="nav-link" data-toggle="tab">Collect Payment</a>
            </li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="applications">
                <livewire:datatables.admissions.applications />
            </div>

            <div wire:ignore.self class="tab-pane fade show" id="collect-payment">
                <form class="ajax-store" method="post" action="{{ route('application.collect_fee') }}">
                    @csrf

                    <div class="form-group">
                        <label for="applicant">Applicant Code</label>
                        <input type="text" class="form-control" id="applicant" name="applicant"
                            placeholder="Applicant Code" required>
                    </div>

                    <div class="form-group">
                        <label for="amount">Enter Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" placeholder="ZMW"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="method">Method <span class="text-danger">*</span></label>
                        <select data-placeholder="Payment method" required class="select-search form-control"
                            name="payment_method_id" id="method">
                            <option value=""></option>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-left">
                        <button wire:click="refreshTable('ApplicationsTable')" id="ajax-btn" type="submit"
                            class="btn btn-primary">Submit <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
