@section('page_title', 'Manage Payment Methods')

@php
    use App\Helpers\Qs;
@endphp
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Payment Methods</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul wire:ignore class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-payment-methods" class="nav-link active" data-toggle="tab">Manage
                    Payment Methods</a>
            </li>
            <li class="nav-item"><a href="#new-payment-method" class="nav-link" data-toggle="tab"><i
                        class="icon-plus2"></i>
                    Create New Payment Method</a></li>
        </ul>

        <div class="tab-content">
            <div wire:ignore.self class="tab-pane fade show active" id="all-payment-methods">
                <livewire:datatables.accounting.payment-methods />
            </div>

            <div wire:ignore class="tab-pane fade" id="new-payment-method">
                <div class="row">
                    <div class="col-md-6">
                        <form class="ajax-store" method="post" action="{{ route('payment-methods.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Payment Method <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text"
                                        class="form-control" placeholder="Zanaco Bill Muster">
                                </div>
                            </div>

                            <div class="text-right">
                                <button wire:click.debounce.1000ms="refreshTable('PaymentMethodsTable')" id="ajax-btn"
                                    type="submit" class="btn btn-primary">Submit form <i
                                        class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
