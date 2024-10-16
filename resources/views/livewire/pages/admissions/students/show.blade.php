@section('page_title', 'Student Profile - ' . $student->user->first_name . ' ' . $student->user->last_name)

@php
    use App\Helpers\Qs;
    $latestInvoice = $student->invoices->sortByDesc('created_at')->first();
@endphp

@if (session('status'))
    <?php Qs::goBackWithSuccess(session('status')); ?>
@endif

<div class="row">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body p-4">

                @php
                    $passportPhotoUrl = !$student->user->userPersonalInfo?->passport_photo_path
                        ? asset('images/default-avatar.png')
                        : asset($student->user->userPersonalInfo?->passport_photo_path);
                @endphp

                <div class="rounded-circle w-100 h-100">
                    <img style="aspect-ratio: 1/1; object-fit: cover" src="{{ $passportPhotoUrl }}" alt="photo"
                        class="rounded-circle w-100 h-100">
                </div>
                <h3 class="mt-2">{{ $student->user->first_name . ' ' . $student->user->last_name }}</h3>
                <h6 class="mt-1">{{ $student->id }}</h6>
            </div>
        </div>

        <div wire:ignore class="card">
            <div class="card-body p-4">
                <form wire:submit="uploadPassportPhoto">

                    <h6 class="card-title">Update Student Photo:</h6>

                    <div class="form-group">
                        <input accept="image/*" type="file" wire:model="passportPhoto" class="form-input-styled"
                            required>
                        <span class="form-text text-muted">JPG or PNG. 2MB Max</span>
                    </div>

                    <div class="text-left">
                        <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                            Update <i class="icon-pencil ml-2"></i>
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
    <div class="col-md-9">
        <div wire:ignore class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">Student General Information</h6>
                {!! Qs::getPanelOptions() !!}
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-highlight">
                    <li class="nav-item active">
                        <a href="#account-info" class="nav-link active" data-toggle="tab">Academic Information</a>
                    </li>
                    <li class="nav-item">
                        <a href="#profile-info" class="nav-link" data-toggle="tab">Personal Information</a>
                    </li>
                    <li class="nav-item">
                        <a href="#next-kin" class="nav-link" data-toggle="tab">Next of Kin Information</a>
                    </li>
                    <li class="nav-item">
                        <a href="#sponsor" class="nav-link" data-toggle="tab">Sponsor Information</a>
                    </li>
                    <li class="nav-item">
                        <a href="#downloads-info" class="nav-link" data-toggle="tab">Student Downloads</a>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- Basic Info --}}
                    <div class="tab-pane fade show active" id="account-info">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold">Student ID</td>
                                    <td>{{ $student->id }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Year of Study</td>
                                    <td class="academic-infor">{{ $student->level->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Registered Academic Period</td>
                                    <td>{{ count($student->invoices) > 0 ? $student->invoices->last()->period->name : 'Not registered' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Semester</td>
                                    <td>{{ $student->semester }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Study Category</td>
                                    <td class="academic-infor">{{ $student->study_mode->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Status</td>
                                    <td>{{ $student->admission_status == 'active' ? 'Active' : 'Inactive' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Programme Name</td>
                                    <td class="academic-infor">{{ $student->program->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Programme Code</td>
                                    <td>{{ $student->program->code }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Intake</td>
                                    <td class="academic-infor">{{ $student->intake->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Period Type</td>
                                    <td class="academic-infor">
                                        <span>{{ $student->period_type->name }}</span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade show" id="sponsor">
                        <table class="table table-bordered">
                            <tbody>
                            @if(!empty($student->sponsors[0]))
                            <tr>
                                <td class="font-weight-bold">Name</td>
                                <td class="next-of-kin-infor">
                                    <span>{{ $student->sponsors[0]->name }}</span>

                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-justify">Description</td>
                                <td class="next-of-kin-infor">
                                    <span>{{ $student->sponsors[0]->description }}</span>

                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-justify">Mobile</td>
                                <td class="next-of-kin-infor">
                                    <span>{{ $student->sponsors[0]->mobile }}</span>

                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-justify">Email</td>
                                <td class="next-of-kin-infor">
                                    <span>{{ $student->sponsors[0]->email }}</span>

                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-justify">Level</td>
                                <td class="next-of-kin-infor">
                                    <span>{{ $student->sponsors[0]->pivot->level }}</span>

                                </td>
                            </tr>
                            @else
                                <tr>
                                    <td class="font-weight-bold text-justify">Name</td>
                                    <td class="next-of-kin-infor">
                                        <span>Self</span>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Level</td>
                                    <td class="next-of-kin-infor">
                                        <span>100</span>

                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade show" id="next-kin">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold">Full Name</td>
                                    <td class="next-of-kin-infor">{{ $student->user->userNextOfKin->full_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Mobile</td>
                                    <td class="next-of-kin-infor">{{ $student->user->userNextOfKin->mobile }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Telephone</td>
                                    <td class="next-of-kin-infor">{{ $student->user->userNextOfKin->telephone }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Relationship</td>
                                    <td class="next-of-kin-infor">
                                        {{ $student->user->userNextOfKin->relationship->relationship }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Town</td>
                                    <td class="next-of-kin-infor">{{ $student->user->userNextOfKin->town->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Province</td>
                                    <td class="next-of-kin-infor">{{ $student->user->userNextOfKin->province->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Country</td>
                                    <td class="next-of-kin-infor">{{ $student->user->userNextOfKin->country->country }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade show" id="profile-info">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold">First Name</td>
                                    <td class="personal-infor">{{ $student->user->first_name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Middle Name</td>
                                    <td class="personal-infor">{{ $student->user->middle_name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Last Names</td>
                                    <td class="personal-infor">{{ $student->user->last_name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Gender</td>
                                    <td class="personal-infor">{{ $student->user->gender }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Email</td>
                                    <td class="personal-infor">{{ $student->user->email }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">NRC</td>
                                    <td class="personal-infor">{{ $student->user->userPersonalInfo?->nrc }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Passport Number</td>
                                    <td class="personal-infor">{{ $student->user->userPersonalInfo?->passport }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Date of Birth</td>
                                    <td class="personal-infor">
                                        {{ date('j F Y', strtotime($student->user->userPersonalInfo?->date_of_birth)) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Marital Status</td>
                                    <td class="personal-infor">
                                        {{ $student->user->userPersonalInfo?->userMaritalStatus->status }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Mobile</td>
                                    <td class="personal-infor">{{ $student->user->userPersonalInfo?->mobile }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Street</td>
                                    <td class="personal-infor">{{ $student->user->userPersonalInfo?->street_main }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Town</td>
                                    <td class="personal-infor">{{ $student->user->userPersonalInfo?->town->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Province</td>
                                    <td class="personal-infor">
                                        <span>{{ $student->user->userPersonalInfo?->province->name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-justify">Country</td>
                                    <td class="personal-infor">
                                        {{ $student->user->userPersonalInfo?->country->country }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade show" id="downloads-info">
                        <a href="{{ route('student.id.download', $student->id) }}" class="btn btn-primary"
                            type="button">Download ID</a>
                        <a href="{{ route('student.transcript.download', $student->id) }}" class="btn btn-primary"
                            type="button">Download Transcript</a>
                        <a href="{{ route('student.exam.slip.download', $student->id) }}" class="btn btn-primary"
                            type="button">Download Exam slip</a>
                    </div>

                </div>
            </div>
        </div>

        <div wire:ignore wire:ignore class="card card-collapsed">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">Enrollments Information</h6>
                {!! Qs::getPanelOptions() !!}
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-highlight">
                    @foreach ($enrollments as $innerIndex => $academicData)
                        <li class="nav-item">
                            <a href="#account-{{ $academicData['academic_period_id'] }}" class="nav-link"
                                data-toggle="tab">{{ $academicData['academic_period_code'] }}</a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    {{-- Basic Info --}}
                    @foreach ($enrollments as $innerIndex => $academicData)
                        <div class="tab-pane fade show" id="account-{{ $academicData['academic_period_id'] }}">

                            <table class="table table-hover table-striped-columns mb-3">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <h5 class="p-2"> Code :
                                            <strong>{{ $academicData['academic_period_code'] }}</strong>
                                        </h5>
                                        <h5 class="p-2">Name :
                                            <strong>{{ $academicData['academic_period_name'] }}</strong>
                                        </h5>
                                    </div>
                                    <div>
                                        <form action="{{ route('registration.summary') }}" method="get">
                                            @csrf
                                            <input name="academic_period_id" type="hidden"
                                                value="{{ $academicData['academic_period_id'] }}" />
                                            <input name="student_number" type="hidden"
                                                value="{{ $student->id }}" />
                                            <button type="submit" class="btn btn-primary mt-2">Download
                                                summary
                                            </button>
                                        </form>

                                    </div>

                                </div>
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($academicData['courses'] as $course)
                                        <tr>
                                            <th>{{ $loop->iteration }}</th>
                                            <td>{{ $course['course_code'] }}</td>
                                            <td>{{ $course['course_title'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

        <div wire:ignore.self class="card card-collapsed">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">Financial Information</h6>
                {!! Qs::getPanelOptions() !!}
            </div>
            <div class="card-body">
                <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">

                    <li class="nav-item">
                        <a href="#financial-stats-overview" class="nav-link  active show"
                            data-toggle="tab">{{ 'Financial Stats Overview' }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="#invoice" class="nav-link"
                            data-toggle="tab">{{ 'Invoice Student - Academic period' }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="#invoice-custom" class="nav-link" data-toggle="tab">{{ 'Invoice Student' }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="#invoices" class="nav-link" data-toggle="tab">{{ 'Invoices' }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="#statements" class="nav-link" data-toggle="tab">{{ 'Statements of Account' }}</a>
                    </li>

                    <li class="nav-item">
                        <a href="#credit" class="nav-link" data-toggle="tab">{{ 'Credit Notes' }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="#payment-history" class="nav-link" data-toggle="tab">{{ 'Payment History' }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="#collect-payment" class="nav-link" data-toggle="tab">{{ 'Collect Payment' }}</a>
                    </li>
                </ul>

                <div class="tab-content">

                    <div wire:ignore class="tab-pane fade show" id="invoice-custom">
                        @if ($allInvoicesBalance >= 100 || $allInvoicesBalance == 0)
                            <form class="ajax-store" method="post" action="{{ route('invoices.custom-invoice') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fee">Fees: <span class="text-danger">*</span></label>
                                            <select data-placeholder="Select Fee" required
                                                class="select-search form-control" name="fee_id" id="fee">
                                                <option value=""></option>
                                                @foreach ($fees as $fee)
                                                    <option value="{{ $fee->id }}">{{ $fee->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="invoice-amount">Enter Amount</label>
                                            <input type="number" class="form-control" id="invoice-amount"
                                                name="amount" placeholder="ZMW" required>
                                        </div>
                                    </div>
                                </div>

                                <input name="student_id" type="hidden" value="{{ $student->id }}">

                                <div class="form-group text-left">
                                    <button wire:click.debounce.1000ms="refreshTable('StudentInvoicesTable')"
                                        id="ajax-btn" type="submit" class="btn btn-primary">Invoice Student <i
                                            class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        @else
                            <p>Student has a balance or is not eligible to be invoiced</p>
                        @endif
                    </div>

                    <div wire:ignore class="tab-pane fade show" id="invoice">
                        @if (!$isInvoiced && $student->academic_info)
                            <form class="ajax-store" method="post" action="{{ route('invoices.invoice') }}">
                                @csrf
                                <input name="academic_period" hidden
                                    value="{{ $student->study_mode_id ? $student->study_mode_id : '' }}"
                                    type="text">
                                <input name="student_id" hidden value="{{ $student->id }}" type="text">
                                <div class="text-left">
                                    @if ($hasOpenAcademicPeriod)
                                        @if ($allInvoicesBalance >= 100 || $allInvoicesBalance == 0)
                                            <button
                                                wire:click.debounce.5000ms="invoiceStudentRefresh(['StudentInvoicesTable'])"
                                                id="ajax-btn" type="submit" class="btn btn-primary">Invoice
                                                student<i class="icon-paperplane ml-2"></i></button>
                                        @else
                                            <p>Student has a balance or is not eligible to be invoiced</p>
                                        @endif
                                    @else
                                        <p>There is no open academic period for this student</p>
                                    @endif

                                </div>
                            </form>
                        @elseif(!$isInvoiced && !$student->academic_info)
                            <div class="container">
                                <p>{{ $student->user->first_name . ' ' . $student->user->last_name }}, has no
                                    attached academic information.</p>
                            </div>
                        @else
                            <div class="container">
                                <p>{{ $student->user->first_name . ' ' . $student->user->last_name }}, has already
                                    been invoice for this academic period.</p>
                            </div>
                        @endif
                    </div>

                    <div wire:ignore.self class="tab-pane fade active show" id="financial-stats-overview">

                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-3">
                                <span class="font-weight-semibold">Fees Total: </span>
                                K{{ number_format($totalFees, 2) }}
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <span class="font-weight-semibold">Payments Total: </span>
                                K{{ number_format($totalPayments, 2) }}
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <span class="font-weight-semibold">Payment Percentage: </span>
                                {{ number_format($paymentPercentage, 2) }}%
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <span class="font-weight-semibold">Payment Balance: </span>
                                K{{ number_format($paymentBalance, 2) }}
                            </div>
                        </div>

                    </div>

                    <div wire:ignore class="tab-pane fade show" id="collect-payment">
                        @if ($student->academic_info)
                            <form class="ajax-store" method="post" action="{{ route('statements.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="amount">Enter Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        placeholder="ZMW" required>
                                </div>
                                <div class="form-group">
                                    <label for="method">Method <span class="text-danger">*</span></label>
                                    <select data-placeholder="Payment method" required
                                        class="select-search form-control" name="payment_method_id" id="method">
                                        <option value=""></option>
                                        @foreach ($paymentMethods as $method)
                                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input hidden type="number" class="form-control" name="academic_period"
                                        value="{{ $student->academic_info ? $student->academic_info->academic_period_id : '' }}"
                                        required>
                                </div>

                                <div class="form-group">
                                    <input hidden type="text" class="form-control" name="student_id"
                                        value="{{ $student->id }}" required>
                                </div>

                                <div class="text-left">
                                    <button
                                        wire:click.debounce.1000ms="collectPaymentRefresh([ 'StudentPaymentHistoryTable'
                                        , 'StudentStatementsTable' ])"
                                        id="ajax-btn" type="submit" class="btn btn-primary">Submit <i
                                            class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        @endif

                    </div>

                    <div wire:ignore.self class="tab-pane fade show" id="credit">

                        <table class="table table-bordered mb-3 mb-lg-4">
                            <thead>
                                <th>S/N</th>
                                <th>Invoice No.</th>
                                <th>Fee</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Issued by</th>
                                <th>Date</th>
                            </thead>
                            <tbody>
                                @foreach ($student->invoices as $invoice)
                                    @foreach ($invoice->creditNotes as $key => $note)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>inv{{ $invoice->id }}</td>
                                            <td>{{ $note->invoiceDetail->fee->name }}</td>
                                            <td>{{ $note->amount }}</td>
                                            <td>{{ $note->status }} Office</td>
                                            <td>{{ $note->issuer->first_name }} {{ $note->issuer->last_name }}</td>
                                            <td>{{ $note->created_at->format('d F Y') }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach

                            </tbody>
                        </table>

                    </div>

                    <div wire:ignore class="tab-pane fade show" id="statements">
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

                    <div wire:ignore class="tab-pane fade show" id="invoices">

                        <div>
                            <h4 class="d-flex align-items-center justify-content-between">
                                <span>Invoices</span>

                                <div class="mb-2 d-flex justify-content-end">
                                    <form action="{{ route('student.export-invoices', $student->id) }}"
                                        method="get">
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

                    <div class="tab-pane fade show" id="payment-history">
                        @livewire('datatables.admissions.students.payment-history', [
                            'studentId' => $student->id,
                        ])
                    </div>

                </div>
            </div>
        </div>

        <div wire:ignore class="card card-collapsed">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">Manage user information</h6>
                {!! Qs::getPanelOptions() !!}
            </div>

            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-highlight">
                    <li class="nav-item"><a href="#account-information" class="nav-link active"
                            data-toggle="tab">Account Info</a></li>
                    <li class="nav-item"><a href="#academic-info" class="nav-link" data-toggle="tab">Academic
                            Info</a></li>
                    <li class="nav-item"><a href="#profile-information" class="nav-link" data-toggle="tab">Personal
                            Info</a></li>
                    <li class="nav-item"><a href="#next-of-kin-info" class="nav-link" data-toggle="tab">Next of Kin
                            Info</a></li>
                    <li class="nav-item"><a href="#sponsor-info" class="nav-link" data-toggle="tab">Sponsor
                            Info</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="account-information">
                        <!-- Add your account info content here -->
                        <div class="row">
                            <div class="col-md-12">
                                <form class="ajax-update" data-reload="#page-header" method="post"
                                    action="{{ route('students.resetAccountPassword', $student->user->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Password: <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input value="{{ old('password') }}" required class="form-control"
                                                placeholder="Password" name="password" type="password">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Confirm
                                            Password:
                                            <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input value="{{ old('password_confirmation') }}" required
                                                class="form-control" placeholder="Confirm Password"
                                                name="password_confirmation" type="password">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label font-weight-semibold">Force Change
                                            Password<span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <input required class="form-control" placeholder="Confirm Password"
                                                name="force_password_reset" type="checkbox">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-9">
                                            <input value="{{ $student->user->id }}" required hidden
                                                class="form-control" name="user_id" type="number">
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">Submit form <i
                                                class="icon-paperplane ml-2"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="academic-info">

                        <!-- Add your academic info content here -->
                        <div class="row">
                            <div class="col-md-12">
                                <form class="ajax-update" data-reload="#page-header" method="post"
                                    action="{{ route('students.update', $student->user->id) }}">
                                    @csrf @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="program_id">Program: <span
                                                        class="text-danger">*</span></label>
                                                <select disabled data-placeholder="Select Program" required
                                                    class="select-search form-control" name="program_id"
                                                    id="program_id">
                                                    <option value=""></option>
                                                    @foreach ($programs as $program)
                                                        <option value="{{ $program->id }}"
                                                            {{ $student->program_id === $program->id ? 'selected' : '' }}>
                                                            {{ $program->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="academic_period_intake_id">Academic Period Intake: <span
                                                        class="text-danger">*</span></label>
                                                <select disabled data-placeholder="Select Academic Period Intake"
                                                    required class="select-search form-control"
                                                    name="academic_period_intake_id" id="academic_period_intake_id">
                                                    <option value=""></option>
                                                    @foreach ($periodIntakes as $intake)
                                                        <option value="{{ $intake->id }}"
                                                            {{ $student->academic_period_intake_id === $intake->id ? 'selected' : '' }}>
                                                            {{ $intake->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="study_mode_id">Study Mode: <span
                                                        class="text-danger">*</span></label>
                                                <select data-placeholder="Select Study Mode" required
                                                    class="select-search form-control" name="study_mode_id"
                                                    id="study_mode_id">
                                                    <option value=""></option>
                                                    @foreach ($studyModes as $mode)
                                                        <option value="{{ $mode->id }}"
                                                            {{ $student->study_mode_id === $mode->id ? 'selected' : '' }}>
                                                            {{ $mode->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="course_level_id">Course Level: <span
                                                        class="text-danger">*</span></label>
                                                <select disabled data-placeholder="Select Course Level" required
                                                    class="select-search form-control" name="course_level_id"
                                                    id="course_level_id">
                                                    <option value=""></option>
                                                    @foreach ($course_levels as $level)
                                                        <option value="{{ $level->id }}"
                                                            {{ $student->course_level_id === $level->id ? 'selected' : '' }}>
                                                            {{ $level->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="period_type_id">Period Type: <span
                                                        class="text-danger">*</span></label>
                                                <select disabled data-placeholder="Select Period Type" required
                                                    class="select-search form-control" name="period_type_id"
                                                    id="period_type_id">
                                                    <option value=""></option>
                                                    @foreach ($periodTypes as $type)
                                                        <option value="{{ $type->id }}"
                                                            {{ $student->period_type_id === $type->id ? 'selected' : '' }}>
                                                            {{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="admission_year">Admission Year: <span
                                                        class="text-danger">*</span></label>
                                                <input disabled value="{{ $student->admission_year }}" required
                                                    type="year" name="admission_year" class="form-control"
                                                    placeholder="Admission Year">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="graduated">Graduated: <span
                                                        class="text-danger">*</span></label>
                                                <select class="select form-control" required name="graduated"
                                                    data-fouc data-placeholder="Select Graduation Status">
                                                    <option value=""></option>
                                                    <option value="1"
                                                        {{ $student->graduated === 1 ? 'selected' : '' }}>Yes
                                                    </option>
                                                    <option value="0"
                                                        {{ $student->graduated === 0 ? 'selected' : '' }}>No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-left">
                                        <button type="submit" class="btn btn-primary">Submit form <i
                                                class="icon-paperplane ml-2"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="profile-information">

                        <!-- Add your profile info content here -->
                        <div class="row">
                            <div class="col-md-12">
                                <form class="ajax-update" data-reload="#page-header" method="post"
                                    action="{{ route('students.update', $student->user->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>First Name: <span class="text-danger">*</span></label>
                                                <input value="{{ $student->user->first_name }}" required
                                                    type="text" name="first_name" placeholder="First Name"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Middle Name:</label>
                                                <input value="{{ $student->user->middle_name }}" type="text"
                                                    name="middle_name" placeholder="Middle Name"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Last Name: <span class="text-danger">*</span></label>
                                                <input value="{{ $student->user->last_name }}" required
                                                    type="text" name="last_name" placeholder="Last Name"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email: <span class="text-danger">*</span></label>
                                                <input value="{{ $student->user->email }}" required type="text"
                                                    name="email" placeholder="Email" class="form-control">
                                            </div>
                                        </div>

                                        <!-- Add more rows as needed -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lga_id">Gender <span
                                                        class="text-danger">*</span></label>
                                                <select data-placeholder="Select Gender" required
                                                    class="select-search form-control" name="gender" id="lga_id">
                                                    <option value="Male"
                                                        {{ $student->user->userPersonalInfo?->gender == 'Male' ? 'selected' : '' }}>
                                                        Male</option>
                                                    <option value="Female"
                                                        {{ $student->user->userPersonalInfo?->gender == 'Female' ? 'selected' : '' }}>
                                                        Female</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Add more rows as needed -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lga_id">Town: <span class="text-danger">*</span></label>
                                                <select data-placeholder="Select Town" required
                                                    class="select-search form-control" name="town_id" id="lga_id">
                                                    <option value=""></option>
                                                    @foreach ($towns as $town)
                                                        <option value="{{ $town->id }}"
                                                            {{ $town->id == $student->user->userPersonalInfo?->town->id ? 'selected' : '' }}>
                                                            {{ $town->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="province_id">Province: <span
                                                        class="text-danger">*</span></label>
                                                <select data-placeholder="Select Province" required
                                                    class="select-search form-control" name="province_id"
                                                    id="province_id">
                                                    <option value=""></option>
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->id }}"
                                                            {{ $province->id == $student->user->userPersonalInfo?->province->id ? 'selected' : '' }}>
                                                            {{ $province->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="country_id">Country: <span
                                                        class="text-danger">*</span></label>
                                                <select data-placeholder="Select Country" required
                                                    class="select-search form-control" name="country_id"
                                                    id="country_id">
                                                    <option value=""></option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}"
                                                            {{ $country->id == $student->user->userPersonalInfo?->country->id ? 'selected' : '' }}>
                                                            {{ $country->country }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="d-block">Upload Passport Photo:</label>
                                                <input
                                                    value="{{ $student->user->userPersonalInfo?->passport_photo_path }}"
                                                    accept="image/*" type="file" name="passport_photo_path"
                                                    class="form-input-styled" data-fouc>
                                                <span class="form-text text-muted">Accepted Images: jpeg, png. Max file
                                                    size 2Mb</span>
                                            </div>
                                        </div> --}}
                                    </div>

                                    <div class="text-left">
                                        <button type="submit" class="btn btn-primary">Submit form <i
                                                class="icon-paperplane ml-2"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="next-of-kin-info">

                        <!-- Add your next of kin info content here -->
                        <div class="row">
                            <div class="col-md-12">
                                <form class="ajax-update" data-reload="#page-header" method="post"
                                    action="{{ route('students.update', $student->user->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Full Name: <span class="text-danger">*</span></label>
                                                <input value="{{ $student->user->userNextOfKin->full_name }}"
                                                    required type="text" name="kin_full_name"
                                                    placeholder="Full Name" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone: <span class="text-danger">*</span></label>
                                                <input value="{{ $student->user->userNextOfKin->mobile }}" required
                                                    type="text" name="kin_mobile" class="form-control"
                                                    placeholder="">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Telephone:</label>
                                                <input value="{{ $student->user->userNextOfKin->telephone }}"
                                                    type="text" name="kin_telephone" class="form-control"
                                                    placeholder="">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="kin_relationship_id">Relationship: <span
                                                        class="text-danger">*</span></label>
                                                <select data-placeholder="Select Relationship" required
                                                    class="select-search form-control" name="kin_relationship_id"
                                                    id="kin_relationship_id">
                                                    <option value=""></option>
                                                    @foreach ($relationships as $relationship)
                                                        <option value="{{ $relationship->id }}"
                                                            {{ $relationship->id == $student->user->userNextOfKin->relationship->id ? 'selected' : '' }}>
                                                            {{ $relationship->relationship }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="kin_town_id">Town: <span
                                                        class="text-danger">*</span></label>
                                                <select data-placeholder="Select Town" required
                                                    class="select-search form-control" name="kin_town_id"
                                                    id="kin_town_id">
                                                    <option value=""></option>
                                                    @foreach ($towns as $town)
                                                        <option value="{{ $town->id }}"
                                                            {{ $town->id == $student->user->userNextOfKin->town->id ? 'selected' : '' }}>
                                                            {{ $town->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="kin_province_id">Province: <span
                                                        class="text-danger">*</span></label>
                                                <select data-placeholder="Select Province" required
                                                    class="select-search form-control" name="kin_province_id"
                                                    id="kin_province_id">
                                                    <option value=""></option>
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->id }}"
                                                            {{ $province->id == $student->user->userNextOfKin->province->id ? 'selected' : '' }}>
                                                            {{ $province->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="kin_country_id">Country: <span
                                                        class="text-danger">*</span></label>
                                                <select data-placeholder="Select Country" required
                                                    class="select-search form-control" name="kin_country_id"
                                                    id="kin_country_id">
                                                    <option value=""></option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}"
                                                            {{ $country->id == $student->user->userNextOfKin->country->id ? 'selected' : '' }}>
                                                            {{ $country->country }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-left">
                                        <button type="submit" class="btn btn-primary">Submit form <i
                                                class="icon-paperplane ml-2"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="sponsor-info">

                        <!-- Add your sponsor content here -->
                        <div class="row">
                            <div class="col-md-12">
{{--                                <form class="ajax-update" data-reload="#page-header" method="post"--}}
{{--                                      action="{{ route('students.sponsor.update', $student->id ) }}">--}}
{{--                                    @csrf--}}
{{--                                    @method('POST')--}}
                                <form class="ajax-update" data-reload="#page-header" method="post"
                                      action="{{ !empty($student->sponsors[0]) ? route('students.sponsor.update', $student->id) : route('students.sponsor.create', $student->id) }}">
                                    @csrf
                                    @if($student->sponsors()->exists())
                                        @method('PUT')
                                    @else
                                        @method('POST')
                                    @endif

                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="kin_relationship_id">Name: <span
                                                        class="text-danger">*</span></label>
                                                <select data-placeholder="Select Relationship" required
                                                        class="select-search form-control" name="sponsor_id"
                                                        id="sponsor_id">
                                                    <option value=""></option>
                                                    @foreach ($sponsors as $sponsor)
                                                        <option value="{{ $sponsor->id }}"
                                                            {{ !empty($student->sponsors[0]) && $sponsor->id == $student->sponsors[0]->pivot->sponsor_id ? 'selected' : '' }}>
                                                            {{ $sponsor->name .' '.$sponsor->description }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Sponsorship Level: <span class="text-danger">*</span></label>
                                                <input value="{{ !empty($student->sponsors[0]) ? $student->sponsors[0]->pivot->level : ''}}"
                                                       required type="number" name="level"
                                                       placeholder="Sponsorship Level" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-left">
                                        <button type="submit" class="btn btn-primary">Submit form <i
                                                class="icon-paperplane ml-2"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore class="card card-collapsed">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">Results Information</h6>
                {!! Qs::getPanelOptions() !!}
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-highlight">
                    @foreach ($results as $innerIndex => $academicData)
                        <li class="nav-item {{ $innerIndex == 0 ? 'active' : '' }}">
                            <a href="#results-{{ $academicData['academic_period_id'] }}" class="nav-link"
                                data-toggle="tab">{{ $academicData['academic_period_code'] }}</a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    {{-- Basic Info --}}
                    @foreach ($results as $innerIndex => $academicData)
                        <div class="tab-pane fade {{ $innerIndex == 0 ? 'show active' : '' }}"
                            id="results-{{ $academicData['academic_period_id'] }}">
                            <h5 class="p-2">
                                <strong>{{ $academicData['academic_period_code'] . ' - ' . $academicData['academic_period_name'] }}</strong>
                            </h5>
                            <h5 class="p-2"><strong>{{ $student->id }}</strong></h5>
                            <table class="table table-hover table-striped-columns mb-3">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                        <th>Mark</th>
                                        <th>Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{--                                        @foreach ($academicData['grades'] as $course) --}}
                                    {{--                                            @if ($course['student_id'] == $student->id) --}}
                                    {{--                                            <tr> --}}
                                    {{--                                                <th>{{ $loop->iteration }}</th> --}}
                                    {{--                                                <td>{{ $course['course_code'] }}</td> --}}
                                    {{--                                                <td>{{ $course['course_title'] }}</td> --}}
                                    {{--                                                <td> --}}
                                    {{--                                                    @php --}}
                                    {{--                                                        $apStartDate = \Carbon\Carbon::make( --}}
                                    {{--                                                            $academicData['academic_period_start_date'], --}}
                                    {{--                                                        ); --}}

                                    {{--                                                        $apEndDate = \Carbon\Carbon::make( --}}
                                    {{--                                                            $academicData['academic_period_end_date'], --}}
                                    {{--                                                        ); --}}

                                    {{--                                                        $apIsOngoing = $apStartDate <= now() && now() <= $apEndDate; --}}
                                    {{--                                                    @endphp --}}

                                    {{--                                                    @if ($apIsOngoing) --}}
                                    {{--                                                        <a class="editable" id="{{ $loop->iteration }}" --}}
                                    {{--                                                            data-type="number" data-pk="{{ $course['grade_id'] }}" --}}
                                    {{--                                                            data-url="/grades/{{ $course['grade_id'] }}/edit" --}}
                                    {{--                                                            data-name="total" data-title="Enter total marks"> --}}
                                    {{--                                                            {{ $course['total'] }} --}}
                                    {{--                                                        </a> --}}
                                    {{--                                                    @else --}}
                                    {{--                                                        {{ $course['total'] }} --}}
                                    {{--                                                    @endif --}}

                                    {{--                                                    --}}{{-- <form action="#" method="POST"> --}}
                                    {{--                                                        @csrf @method('PUT') --}}
                                    {{--                                                        <input class="form-control " type="number" name="total" --}}
                                    {{--                                                            value="{{ $course['total'] }}"> --}}
                                    {{--                                                    </form> --}}
                                    {{--                                                </td> --}}
                                    {{--                                                <td>{{ $course['grade'] }}</td> --}}
                                    {{--                                            </tr> --}}
                                    {{--                                            @endif --}}
                                    {{--                                            @foreach ($academicData['comments']['coursesFailed'] as $gra) --}}
                                    {{--                                                @if ($course['course_code'] != $gra['course_code']) --}}
                                    {{--                                                    <tr> --}}
                                    {{--                                                        <th>{{ $loop->iteration }}</th> --}}
                                    {{--                                                        <td>{{ $gra['course_code'] }}</td> --}}
                                    {{--                                                        <td>{{ $gra['course_title'] }}</td> --}}
                                    {{--                                                        <td> --}}
                                    {{--                                                            @php --}}
                                    {{--                                                                $apStartDate = \Carbon\Carbon::make( --}}
                                    {{--                                                                    $academicData['academic_period_start_date'], --}}
                                    {{--                                                                ); --}}

                                    {{--                                                                $apEndDate = \Carbon\Carbon::make( --}}
                                    {{--                                                                    $academicData['academic_period_end_date'], --}}
                                    {{--                                                                ); --}}

                                    {{--                                                                $apIsOngoing = $apStartDate <= now() && now() <= $apEndDate; --}}
                                    {{--                                                            @endphp --}}

                                    {{--                                                            @if ($apIsOngoing) --}}
                                    {{--                                                                <a class="editable" id="{{ $loop->iteration }}" --}}
                                    {{--                                                                   data-type="number" data-pk="" --}}
                                    {{--                                                                   data-url="/grades/{{ $gra['course_code'] }}/edit" --}}
                                    {{--                                                                   data-name="total" data-title="Enter total marks"> --}}
                                    {{--                                                                    {{ $gra['total_score'] }} --}}
                                    {{--                                                                </a> --}}
                                    {{--                                                            @else --}}
                                    {{--                                                                {{ $gra['total_score'] }} --}}
                                    {{--                                                            @endif --}}

                                    {{--                                                            --}}{{-- <form action="#" method="POST"> --}}
                                    {{--                                                                @csrf @method('PUT') --}}
                                    {{--                                                                <input class="form-control " type="number" name="total" --}}
                                    {{--                                                                    value="{{ $course['total'] }}"> --}}
                                    {{--                                                            </form> --}}
                                    {{--                                                        </td> --}}
                                    {{--                                                        <td>NE</td> --}}
                                    {{--                                                    </tr> --}}
                                    {{--                                                @endif --}}
                                    {{--                                            @endforeach --}}
                                    {{--                                        @endforeach --}}
                                    @foreach ($academicData['grades'] as $course)
                                        @if ($course['student_id'] == $student->id)
                                            <tr>
                                                <th>{{ $loop->iteration }}</th>
                                                <td>{{ $course['course_code'] }}</td>
                                                <td>{{ $course['course_title'] }}</td>
                                                <td>
                                                    {{ $course['total'] }}

                                                    {{-- @php
                                                        $apStartDate = \Carbon\Carbon::make(
                                                            $academicData['academic_period_start_date'],
                                                        );
                                                        $apEndDate = \Carbon\Carbon::make(
                                                            $academicData['academic_period_end_date'],
                                                        );
                                                        $apIsOngoing = $apStartDate <= now() && now() <= $apEndDate;
                                                    @endphp

                                                    @if ($apIsOngoing)
                                                        <a class="editable" id="{{ $loop->iteration }}"
                                                            data-type="number" data-pk="{{ $course['grade_id'] }}"
                                                            data-url="{{ route('grades.edit', $course['grade_id']) }}"
                                                            data-name="total" data-title="Enter total marks">
                                                            {{ $course['total'] }}
                                                        </a>
                                                    @else
                                                        {{ $course['total'] }}
                                                    @endif --}}
                                                </td>
                                                <td>{{ $course['grade'] }}</td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    @foreach ($academicData['comments']['coursesFailed'] as $gra)
                                        @php
                                            $courseExists = false;
                                        @endphp
                                        @foreach ($academicData['grades'] as $course)
                                            @if ($course['course_code'] == $gra['course_code'])
                                                @php
                                                    $courseExists = true;
                                                @endphp
                                            @break
                                        @endif
                                    @endforeach
                                    @if (!$courseExists)
                                        <tr>
                                            <th>{{ $loop->iteration }}</th>
                                            <td>{{ $gra['course_code'] }}</td>
                                            <td>{{ $gra['course_title'] }}</td>
                                            <td>
                                                {{ $gra['total_score'] }}

                                                {{-- @php
                                                    $apStartDate = \Carbon\Carbon::make(
                                                        $academicData['academic_period_start_date'],
                                                    );
                                                    $apEndDate = \Carbon\Carbon::make(
                                                        $academicData['academic_period_end_date'],
                                                    );
                                                    $apIsOngoing = $apStartDate <= now() && now() <= $apEndDate;
                                                @endphp

                                                @dd($gra)
                                                @if ($apIsOngoing)
                                                    <a class="editable" id="{{ $loop->iteration }}"
                                                        data-type="number" data-pk="{{ $gra['grade_id'] }}"
                                                        data-url="{{ route('grades.edit', $gra['grade_id']) }}"
                                                        data-name="total" data-title="Enter total marks">
                                                        {{ $gra['total_score'] }}
                                                    </a>
                                                @else
                                                    {{ $gra['total_score'] }}
                                                @endif --}}
                                            </td>
                                            <td>NE</td>
                                        </tr>
                                    @endif
                                @endforeach

                            </tbody>

                        </table>

                        @php
                            $commentLower = str()->lower($academicData['comments']['comment']);

                            $commentBgColor = match (true) {
                                str()->startsWith($commentLower, 'proceed & repeat') => 'bg-warning',
                                str()->startsWith($commentLower, 'part time') => 'bg-danger',
                                default => 'bg-success',
                            };
                        @endphp

                        <p class="{{ $commentBgColor }} p-3 align-bottom">
                            Comment : {{ $academicData['comments']['comment'] }}
                        </p>

                        <hr>

                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div wire:ignore class="card card-collapsed">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">CA Results Information </h6>
            {!! Qs::getPanelOptions() !!}
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                @foreach ($caresults as $innerIndex => $academicData)
                    <li class="nav-item {{ $innerIndex == 0 ? 'active' : '' }}">
                        <a href="#results-cs{{ $academicData['academic_period_id'] }}" class="nav-link"
                            data-toggle="tab">{{ $academicData['academic_period_code'] }}</a>
                    </li>
                @endforeach
                {{--                        <li class="nav-item"> --}}
                {{--                            <a href="#profile-info" class="nav-link" data-toggle="tab">{{ 'Profile Details' }}</a> --}}
                {{--                        </li> --}}
            </ul>

            <div class="tab-content">
                {{-- Basic Info --}}
                @foreach ($caresults as $innerIndex => $academicData)
                    <div class="tab-pane fade {{ $innerIndex == 0 ? 'show active' : '' }}"
                        id="results-cs{{ $academicData['academic_period_id'] }}">
                        <h5 class="p-2">
                            <strong>{{ $academicData['academic_period_code'] . ' - ' . $academicData['academic_period_name'] }}</strong>
                        </h5>
                        <h5 class="p-2"><strong>{{ $student->id }}</strong></h5>
                        <table class="table table-hover table-striped-columns mb-3">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Mark</th>
                                    <th>out of</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($academicData['grades'] as $course)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $course['course_code'] }}</td>
                                        <td>{{ $course['course_title'] }}</td>
                                        <td> {{ $course['total'] }}</td>
                                        <td>{{ $course['outof'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <p class="bg-success p-3 align-bottom">Comment
                            : {{ $academicData['comments']['comment'] }}</p>
                        <hr>

                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @if ($isRegistered)

        <div wire:ignore class="card card-collapsed">

            <div class="card-header header-elements-inline">
                <h6 class="card-title">Registration Summary</h6>
                {!! Qs::getPanelOptions() !!}
            </div>

            <div class="card-body">

                <form action="{{ route('registration.summary') }}" method="get">
                    @csrf
                    <input name="academic_period_id" type="hidden"
                        value="{{ $latestInvoice->academic_period_id }}" />
                    <input name="student_number" type="hidden" value="{{ $student->id }}" />
                    <button type="submit" class="btn btn-primary mt-2">Download summary</button>
                </form>
            </div>
        </div>
    @else
        <div wire:ignore.self class="card card-collapsed">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">Courses available for registration</h6>
                {!! Qs::getPanelOptions() !!}
            </div>
            @if ($courses)
                <div class="card-body">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Code</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $course->code }}</td>
                                    <td>{{ $course->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($isWithinRegistrationPeriod && !$isRegistered && $registrationBalance <= 0)
                        <form action="{{ route('enrollments.store') }}" method="post">
                            @csrf
                            <input name="student_number" type="hidden" value="{{ $student->id }}" />
                            <button id="ajax-btn" type="submit" class="btn btn-primary mt-2">Register
                            </button>
                        </form>
                    @endif
                </div>
            @else
                <div class="card-body">
                    <h6> No courses available</h6>
                    <p><i>Student either has no invoice or is not within the registration period.</i></p>
                </div>
            @endif
        </div>

    @endif

    <div wire:ignore class="card card-collapsed">

        <div class="card-header header-elements-inline">
            <h6 class="card-title">Course Management</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">

            @if (count($enrolled_courses) > 0)

                <div class="text-right ">
                    <a href="{{ route('students.add-drop-course', $student->id) }}"
                        class="btn btn-primary mt-2 mb-2 right text-white">Course Add \ Drop</a>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Code</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($enrolled_courses as $course)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $course['course']->code }}</td>
                                <td>{{ $course['course']->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="container ">
                    <p>No course available</p>
                </div>
            @endif

        </div>
    </div>

</div>

</div>
