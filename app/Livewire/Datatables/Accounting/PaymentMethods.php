<?php

namespace App\Livewire\Datatables\Accounting;

use App\Models\Accounting\PaymentMethod;
use App\Repositories\Accounting\PaymentMethodRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class PaymentMethods extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'PaymentMethodsTable';
    public string $sortField = 'name';
    public bool $deferLoading = true;

    protected PaymentMethodRepository $paymentMethodRepo;

    public function boot(): void
    {
        $this->paymentMethodRepo = app(PaymentMethodRepository::class);
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('payment-methods-export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return PaymentMethod::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('usage_instructions', function ($row) {
                return Str::limit($row->usage_instructions, 75);
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Usage Instructions', 'usage_instructions'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(PaymentMethod $row): array
    {
        return [
            Button::add('actions')
                ->bladeComponent('table-actions.accounting.payment-methods', ['row' => $row])
        ];
    }
}
