<?php

namespace App\Http\Controllers\Residency;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Residency\Country as CountryRequest;
use App\Models\Residency\Country;
use App\Repositories\Residency\CountryRepository;
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

    public function index()
    {
        return view('pages.countries.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CountryRequest $request)
    {
        $data = $request->only(['alpha_2_code', 'alpha_3_code', 'nationality', 'dialing_code']);
        $data['country'] = $request['name'];

        $country = $this->countryRepo->create($data);

        if (!$country) {
            return Qs::jsonError(__('msg.create_failed'));
        }

        return Qs::jsonStoreOk();
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        //
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
        $data = $request->only(['alpha_2_code', 'alpha_3_code', 'nationality', 'dialing_code']);
        $data['country'] = $request['name'];

        $this->countryRepo->update($country, $data);

        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {

        try {
            $this->countryRepo->delete($country);
            return back()->with('flash_success', __('msg.delete_ok'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', __('msg.delete_error'));
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
