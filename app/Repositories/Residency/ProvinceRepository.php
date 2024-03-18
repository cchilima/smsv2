<?php

namespace App\Repositories\Residency;


use App\Models\Residency\{Province, Town};

class ProvinceRepository
{
    public function getAll()
    {
        return Province::all();
    }

    public function getTownsByProvince($provinceId)
    {
        $towns = Town::where('province_id', $provinceId)->get(['id', 'name']);

        if ($towns->isEmpty()) {
            return Town::where('name', 'Other')->get(['id', 'name']);
        }

        return $towns;
    }

    public function create($data)
    {
        return Province::create($data);
    }

    public function update($province, $data)
    {
        return $province->update($data);
    }

    public function delete($province)
    {
        return $province->delete();
    }
}
