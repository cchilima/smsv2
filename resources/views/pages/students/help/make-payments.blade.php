@extends('layouts.master')
@section('page_title', 'How to Make Payments')
@section('content')

    @php
        use App\Helpers\Qs;
    @endphp

    <div class="row">
        <div class="col col-12">
            <div id="payment-instructions-accordion">
                @if (count($paymentMethods) > 0)
                    @foreach ($paymentMethods as $paymentMethod)
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link pl-0" data-toggle="collapse"
                                        data-target="#collapse-{{ $loop->index }}"
                                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                        aria-controls="collapse-{{ $loop->index }}">
                                        <i class="icon-arrow-down22"></i> {{ $paymentMethod->name }}
                                    </button>
                                </h5>
                            </div>

                            <div id="collapse-{{ $loop->index }}" class="collapse {{ $loop->first ? 'show' : '' }}"
                                aria-labelledby="headingOne" data-parent="#payment-instructions-accordion">
                                <div class="card-body">
                                    <p class="card-text">{!! nl2br($paymentMethod->usage_instructions) !!}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
