<?php

namespace App\Http\Controllers\Residency;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Residency\Country as CountryRequest;
use App\Models\Residency\Country;
use App\Repositories\Residency\CountryRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $countryRepo;

    public function __construct(CountryRepository $countryRepo)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->countryRepo = $countryRepo;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CountryRequest $request)
    {
        try {
            $data = $request->only(['alpha_2_code', 'alpha_3_code', 'nationality', 'dialing_code']);
            $data['country'] = $request['name'];

            $this->countryRepo->create($data);

            return Qs::jsonStoreOk('Country created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create country: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        return view('pages.countries.edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CountryRequest $request, Country $country)
    {
        try {
            $data = $request->only(['alpha_2_code', 'alpha_3_code', 'nationality', 'dialing_code']);
            $data['country'] = $request['name'];

            $this->countryRepo->update($country, $data);

            return Qs::jsonUpdateOk('Country updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update country: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        try {
            $this->countryRepo->delete($country);
            return Qs::goBackWithSuccess('Country deleted successfully');
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete a country referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete country: ' . $th->getMessage());
        }
    }

    /**
     * Get all provinces in a specified country from storage
     */
    public function getProvincesByCountry(CountryRequest $request)
    {
        $countryId = $request['countryId'];
        $provinces = $this->countryRepo->getProvincesByCountry($countryId);

        return response()->json($provinces);
    }
}
