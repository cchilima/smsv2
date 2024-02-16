<?php

namespace App\Repositories\Inputs;


use App\Models\Residency\{Country, Province};

class CountryRepository
{
    public function find($countryId)
    {
        return Country::find($countryId);
    }

    public function getAll()
    {
        return Country::all(['id', 'name']);
    }

    public function getProvincesByCountry($countryId)
    {
        $provinces = Province::where('country_id', $countryId)->get(['id', 'name']);

        if ($provinces->isEmpty()) {
            return Province::where('name', 'Other')->get(['id', 'name']);
        }

        return $provinces;
    }
}
