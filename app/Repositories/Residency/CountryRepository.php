<?php

namespace App\Repositories\Residency;


use App\Models\Residency\{Country, Province};

class CountryRepository
{
    public function find($countryId)
    {
        return Country::find($countryId);
    }

    public function getAll()
    {
        return Country::all();
    }

    public function getProvincesByCountry($countryId)
    {
        $provinces = Province::where('country_id', $countryId)->get(['id', 'name']);

        if ($provinces->isEmpty()) {
            return Province::where('name', 'Other')->get(['id', 'name']);
        }

        return $provinces;
    }

    public function create($data)
    {
        return Country::create($data);
    }

    public function update($country, $data)
    {
        return $country->update($data);
    }

    public function delete($country)
    {
        return $country->delete();
    }
}
