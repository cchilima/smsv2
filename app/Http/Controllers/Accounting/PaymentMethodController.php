<?php

namespace App\Http\Controllers\Accounting;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Accounting\PaymentMethod as PaymentMethodRequest;
use App\Models\Accounting\PaymentMethod;
use App\Repositories\Accounting\PaymentMethodRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    protected $paymentMethodRepo;

    public function __construct(PaymentMethodRepository $paymentMethodRepo)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->paymentMethodRepo = $paymentMethodRepo;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentMethodRequest $request)
    {
        try {
            $data = $request->only(['name', 'usage_instructions']);
            $this->paymentMethodRepo->create($data);

            return Qs::jsonStoreOk('Payment method created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create record: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('pages.paymentMethods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        try {
            $data = $request->only(['name', 'usage_instructions']);
            $this->paymentMethodRepo->update($paymentMethod, $data);

            return Qs::jsonUpdateOk();
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update payment method: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        try {
            $this->paymentMethodRepo->delete($paymentMethod);
            return Qs::goBackWithSuccess('Payment method deleted successfully');
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete payment method referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete payment method');
        }
    }
}
