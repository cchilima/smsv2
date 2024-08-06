@extends('layouts.master')
@section('page_title', 'Collect Application Payments')
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Applications</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#applications" class="nav-link active" data-toggle="tab">Applications</a></li>
                <li class="nav-item"><a href="#collect-payment" class="nav-link" data-toggle="tab">Collect Payment</a></li>

            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="applications">

                    <livewire:datatables.applications.applications />

                    {{-- <table class="table datatable-button-html5-columns">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Program</th>
                                <th>Status</th>
                                <th>Applicant Code</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $application)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $application->first_name ?? '' }} {{ $application->last_name ?? '' }}</td>
                                    <td>{{ $application->program->name ?? '' }}</td>
                                    <td>{{ $application->status ?? '' }}</td>
                                    <td>{{ $application->applicant_code ?? '' }}</td>
                                    <td>K{{ $application->payment->sum('amount') ?? 'K0' }}</td>
                                    <td>{{ $application->created_at ?? '' }}</td>

                                    <td class="">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    <a href="{{ route('application.show', $application->id) }}"
                                                        class="dropdown-item"><i class="icon-eye"></i>View</a>

                                                    <div class="dropdown-menu dropdown-menu-left">
                                                        <a href="{{ route('application.show', $application->id) }}"
                                                            class="dropdown-item"><i class="icon-eye"></i>Collect Fee</a>

                                                    </div>
                                                </div>
                                            </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}

                </div>

                <div class="tab-pane fade show" id="collect-payment">

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
                            <button id="ajax-btn" type="submit" class="btn btn-primary">Submit <i
                                    class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
