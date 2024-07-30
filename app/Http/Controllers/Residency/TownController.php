<?php

namespace App\Http\Controllers\Residency;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Residency\Town as TownRequest;
use App\Models\Residency\Town;
use App\Repositories\Residency\CountryRepository;
use App\Repositories\Residency\ProvinceRepository;
use App\Repositories\Residency\TownRepository;
use Illuminate\Http\Request;

class TownController extends Controller
{
    protected $countryRepo;
    protected $provinceRepo;
    protected $townRepo;

    public function __construct(
        TownRepository $townRepository,
        CountryRepository $countryRepository,
        ProvinceRepository $provinceRepository
    ) {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->countryRepo = $countryRepository;
        $this->provinceRepo = $provinceRepository;
        $this->townRepo = $townRepository;
    }

    public function index()
    {
        $countries = $this->countryRepo->getAll();
        $towns = $this->townRepo->getAll();

        return view('pages.towns.index', compact(['countries', 'towns']));
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
    public function store(TownRequest $request)
    {
        $data = $request->only(['name', 'country_id', 'province_id']);
        $town = $this->townRepo->create($data);

        if (!$town) {
            return Qs::jsonError(__('msg.create_failed'));
        }

        return Qs::jsonStoreOk();
    }

    /**
     * Display the specified resource.
     */
    public function show(Town $town)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Town $town)
    {
        $countries = $this->countryRepo->getAll();

        return view('pages.towns.edit', compact(['countries', 'town']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TownRequest $request, Town $town)
    {
        $data = $request->only(['name', 'country_id', 'province_id']);
        $this->townRepo->update($town, $data);

        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Town $town)
    {
        try {
            $this->townRepo->delete($town);
            return back()->with('flash_success', __('msg.delete_ok'));
        } catch (\Throwable $th) {
            // Qs::jsonError(__('msg.delete_failed'));
            return back()->with('flash_error', __('msg.delete_error'));
        }
    }
}
