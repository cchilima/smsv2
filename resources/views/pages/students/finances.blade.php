@extends('layouts.master')
@section('page_title', 'Finances - ' . auth()->user()->first_name . ' ' . auth()->user()->last_name)
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Financial Information</h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-highlight">

                        <li class="nav-item">
                            <a href="#invoices" class="nav-link active" data-toggle="tab">{{ 'Invoices' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#quotations" class="nav-link " data-toggle="tab">{{ 'Quotations' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#statements" class="nav-link" data-toggle="tab">{{ 'Statements' }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="#payment-history" class="nav-link" data-toggle="tab">{{ 'Payment History' }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="invoices">
                            <div>
                                <h4 class="d-flex align-items-center justify-content-between">
                                    <span>Invoices</span>

                                    <div class="mb-2 d-flex justify-content-end">
                                        <form action="{{ route('student.export-invoices', $student->id) }}" method="get">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">
                                                <i class="icon-download4 mr-1 lr-lg-2"></i>
                                                <span>Export Invoices</span>
                                            </button>
                                        </form>
                                    </div>

                                </h4>
                            </div>

                            @livewire('datatables.admissions.students.invoices', [
                                'studentId' => $student->id,
                            ])

                        </div>

                        <div class="tab-pane fade show" id="quotations">

                            <h4 class="d-flex align-items-center justify-content-between">
                                <span>Quotations</span>

                                <div class="mb-2 d-flex justify-content-end">

                                    <form class="ajax-store" method="post" action="{{ route('quotations.quotation') }}">
                                        @csrf
                                        <input name="academic_period" hidden
                                            value="{{ $student->study_mode_id ? $student->study_mode_id : '' }}"
                                            type="text">
                                        <input name="student_id" hidden value="{{ $student->id }}" type="text">
                                        <div class="text-left">
                                            <button wire:click.debounce.5000ms="refreshTable('StudentQuotationsTable')"
                                                id="ajax-btn" type="submit" @disabled(!$periodInfo)
                                                class="btn btn-primary {{ !$periodInfo ? 'disabled' : '' }}">Get Quotation<i
                                                    class="icon-paperplane ml-2"></i></button>
                                        </div>
                                    </form>

                                </div>

                            </h4>

                            <livewire:datatables.admissions.students.quotations :student="$student" />

                        </div>

                        <div class="tab-pane fade show" id="statements">
                            <div class="mb-2 d-flex justify-content-end">
                                <form action="{{ route('student.export-statements', $student->id) }}" method="get">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="icon-download4 mr-1 lr-lg-2"></i>
                                        <span>Export Statements</span>
                                    </button>
                                </form>
                            </div>

                            <livewire:datatables.admissions.students.statements :student="$student" />

                        </div>

                        <div class="tab-pane fade show" id="payment-history">
                            @livewire('datatables.admissions.students.payment-history', [
                                'studentId' => $student->id,
                            ])
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
