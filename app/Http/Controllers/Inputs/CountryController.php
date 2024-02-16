<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Inputs\Country as CountryRequest;
use App\Repositories\Inputs\CountryRepository;
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

    public function getProvincesByCountry(CountryRequest $request)
    {
        $countryId = $request['countryId'];
        $provinces = $this->countryRepo->getProvincesByCountry($countryId);

        return response()->json($provinces);
    }
}
