@if (count($enabledFilters))
    <div class="col-md-12 d-flex table-responsive" style="margin-top: 5px">
        @if (count($enabledFilters) > 1)
            <div wire:click.prevent="clearAllFilters()" style="cursor: pointer; padding-right: 4px">
                <span
                    class="badge badge-pill d-flex align-items-center bg-primary text-white">{{ trans('livewire-powergrid::datatable.buttons.clear_all_filters') }}
                    <svg class="ml-2" width="10" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                    </svg>
                </span>
            </div>
        @endif
        @foreach ($enabledFilters as $filter)
            @isset($filter['label'])
                <div wire:click.prevent="clearFilter('{{ $filter['field'] }}')" style="cursor: pointer; padding-right: 4px">
                    <span class="badge badge-pill d-flex align-items-center bg-primary text-white">{{ $filter['label'] }}
                        <svg class="ml-2" width="10" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                            <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                        </svg>
                    </span>
                </div>
            @endisset
        @endforeach
    </div>
@endif
