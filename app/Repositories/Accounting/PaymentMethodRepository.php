<?php

namespace App\Repositories\Accounting;

use App\Models\Accounting\PaymentMethod;

class PaymentMethodRepository
{
    public function find($paymentMethodId)
    {
        return PaymentMethod::find($paymentMethodId);
    }

    public function getAll()
    {
        return PaymentMethod::all();
    }

    public function create($data)
    {
        return PaymentMethod::create($data);
    }

    public function update($paymentMethod, $data)
    {
        return $paymentMethod->update($data);
    }

    public function delete($paymentMethod)
    {
        return $paymentMethod->delete();
    }
}
