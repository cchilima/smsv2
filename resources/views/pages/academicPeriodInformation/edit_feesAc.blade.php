@extends('layouts.master')
@section('page_title', 'Edit Fees for '.$feeInformation->academic_period->name )
@section('content')
    @php
        use App\Helpers\Qs;
    @endphp
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit fees for {{ $feeInformation->academic_period->name }}</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-store" method="post" action="{{ route('academic-period-fees.update',$feeInformation->id) }}">
                        @csrf
                        @method('PUT')
                        <!-- Add form fields for creating a new academic period -->
                        <div class="form-group row">
                            <label for="fees-id" class="col-lg-3 col-form-label font-weight-semibold">Fee Name <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select required data-placeholder="Select type" class="form-control select-search" name="fee_id" id="fees-id">
                                    <option value="{{ $feeInformation->fee_id }}">{{ $feeInformation->fee->name }}</option>
                                    @foreach ($fees as $fee)
                                        <option value="{{ $fee->id }}">{{ $fee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Amount <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="amount" value="{{ $feeInformation->amount }}" required type="number" class="form-control" placeholder="Amount">
                            </div>
                        </div>

                        <div class="text-right">
                            <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Academic Period Ends --}}
@endsection
