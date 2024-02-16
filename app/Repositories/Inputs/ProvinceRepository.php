<?php

namespace App\Repositories\Inputs;


use App\Models\Residency\{Province, Town};

class ProvinceRepository
{
    public function getAll()
    {
        return Province::all(['id', 'name']);
    }

    public function getTownsByProvince($provinceId)
    {
        $towns = Town::where('province_id', $provinceId)->get(['id', 'name']);

        if ($towns->isEmpty()) {
            return Town::where('name', 'Other')->get(['id', 'name']);
        }

        return $towns;
    }
}
